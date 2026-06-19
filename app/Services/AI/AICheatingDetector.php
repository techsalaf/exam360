<?php

namespace App\Services\AI;

use App\Models\StudentExamSession;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AICheatingDetector
{
    /**
     * Analyze the session and update risk scores.
     *
     * @param StudentExamSession $session
     * @return void
     */
    public function analyzeSession(StudentExamSession $session): void
    {
        // 1. Determine which provider to use
        $provider = $this->getActiveProvider();

        // 2. Get Analysis Data (Score & Reason)
        $analysis = match ($provider) {
            'gemini' => $this->analyzeWithGemini($session),
            'openai' => $this->analyzeWithOpenAI($session),
            default  => $this->analyzeWithLocalHeuristics($session),
        };

        // 3. Calculate Risk Level
        $riskLevel = $this->determineRiskLevel($analysis['score']);

        // 4. Update the Session
        // We assume 'flagged_events' is cast to an array in the model
        $currentEvents = $session->flagged_events ?? [];
        
        // Only append event if it's new and significant
        if (!empty($analysis['event'])) {
            $timestamp = Carbon::now()->format('H:i:s');
            $currentEvents[] = "[{$timestamp}] {$analysis['event']}";
        }

        $session->update([
            'risk_score'       => $analysis['score'],
            'risk_level'       => $riskLevel,
            'flagged_events'   => $currentEvents,
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Determine the active provider based on config priority.
     */
    protected function getActiveProvider(): string
    {
        $preferred = Config::get('ai.default_detector_provider', 'disabled');
        
        if ($preferred === 'gemini' && Config::get('ai.gemini.enabled')) {
            return 'gemini';
        }

        if ($preferred === 'openai' && Config::get('ai.openai_key')) {
            return 'openai';
        }

        return 'local';
    }

    /**
     * Local Heuristics (Fallback).
     * Calculates risk based on basic metrics like tab switches.
     */
    protected function analyzeWithLocalHeuristics(StudentExamSession $session): array
    {
        // Base score starts at 0
        $score = 0;
        $event = null;

        // Example Logic: Increase score based on tab switches
        // Assuming session has a 'tab_switches_count' or we check metadata
        $switches = $session->attempt_count ?? 0; // fallback to attempt_count or similar field

        if ($switches > 5) {
            $score = 85;
            $event = "High frequency of tab switching detected ($switches times).";
        } elseif ($switches > 2) {
            $score = 45;
            $event = "Moderate tab switching detected.";
        }

        return ['score' => $score, 'event' => $event];
    }

    /**
     * Google Gemini Analysis.
     */
    protected function analyzeWithGemini(StudentExamSession $session): array
    {
        $apiKey = Config::get('ai.gemini.api_key');
        $model  = Config::get('ai.gemini.model', 'gemini-1.5-flash');

        if (empty($apiKey)) return $this->analyzeWithLocalHeuristics($session);

        try {
            $prompt = $this->buildAnalysisPrompt($session);

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

            if ($response->successful()) {
                $rawText = $response->json('candidates.0.content.parts.0.text');
                return $this->parseAiResponse($rawText);
            }

            Log::error('Gemini Risk Analysis Error: ' . $response->body());
            return $this->analyzeWithLocalHeuristics($session);

        } catch (\Exception $e) {
            Log::error('Gemini Connection Failed: ' . $e->getMessage());
            return $this->analyzeWithLocalHeuristics($session);
        }
    }

    /**
     * OpenAI Analysis.
     */
    protected function analyzeWithOpenAI(StudentExamSession $session): array
    {
        $apiKey = Config::get('ai.openai_key');
        $model  = Config::get('ai.default_model', 'gpt-3.5-turbo');

        if (empty($apiKey)) return $this->analyzeWithLocalHeuristics($session);

        try {
            $prompt = $this->buildAnalysisPrompt($session);

            $response = Http::withToken($apiKey)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a strict JSON data analyzer for exam proctoring.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.5,
                ]);

            if ($response->successful()) {
                $rawText = $response->json('choices.0.message.content');
                return $this->parseAiResponse($rawText);
            }

            Log::error('OpenAI Risk Analysis Error: ' . $response->body());
            return $this->analyzeWithLocalHeuristics($session);

        } catch (\Exception $e) {
            Log::error('OpenAI Connection Failed: ' . $e->getMessage());
            return $this->analyzeWithLocalHeuristics($session);
        }
    }

    /**
     * Builds the prompt context for the AI.
     */
    protected function buildAnalysisPrompt(StudentExamSession $session): string
    {
        // Gather relevant data points
        // In a real app, you would pass log arrays here.
        // We simulate "recent logs" for the AI to analyze.
        $metrics = [
            'total_time_spent' => Carbon::parse($session->created_at)->diffInMinutes(now()) . ' minutes',
            'tab_switches'     => $session->attempt_count ?? 0,
            'browser_agent'    => $session->user_agent ?? 'Unknown',
            'ip_address'       => $session->ip_address,
        ];

        $metricsJson = json_encode($metrics);

        return "Analyze this student exam session metadata for cheating risk.
        Data: {$metricsJson}
        
        Rules:
        1. If tab_switches > 3, risk is HIGH (>70).
        2. If tab_switches > 1, risk is MEDIUM (>40).
        3. Return strictly JSON in this format: {\"score\": int, \"event\": \"string or null\"}
        4. The 'event' string should explain the reason briefly if risk is > 0.";
    }

    /**
     * Parses the JSON output from AI.
     */
    protected function parseAiResponse(?string $rawText): array
    {
        if (!$rawText) return ['score' => 0, 'event' => null];

        $cleanJson = Str::of($rawText)->replace(['```json', '```'], '')->trim();
        $data = json_decode($cleanJson, true);

        if (json_last_error() === JSON_ERROR_NONE && isset($data['score'])) {
            return [
                'score' => (int) $data['score'],
                'event' => $data['event'] ?? null
            ];
        }

        return ['score' => 0, 'event' => null];
    }

    /**
     * Map numerical score to risk level string.
     */
    protected function determineRiskLevel(int $score): string
    {
        $thresholds = Config::get('ai.risk_thresholds', ['warning' => 30, 'critical' => 70]);

        if ($score >= $thresholds['critical']) {
            return 'critical';
        }
        
        if ($score >= $thresholds['warning']) {
            return 'warning';
        }

        return 'normal';
    }
}
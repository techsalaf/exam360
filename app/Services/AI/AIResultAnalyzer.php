<?php

namespace App\Services\AI;

use App\Models\StudentExamSession; // FIX: Use the correct model name
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AIResultAnalyzer
{
    /**
     * Entry point for analysis, accepting the StudentExamSession model.
     */
    public function analyze(StudentExamSession $session): array // FIX: Type hint changed from Result to StudentExamSession
    {
        $provider = Config::get('ai.default_detector_provider', 'local');

        $context = $this->buildContext($session); // Use $session
        $localContext = $this->buildContext($session);

        if ($provider === 'gemini' && Config::get('ai.gemini.enabled')) {
            return $this->analyzeWithGemini($context);
        }

        if ($provider === 'openai' && Config::get('ai.openai_key')) {
            return $this->analyzeWithOpenAI($context);
        }

        // Fallback to local heuristics
        return $this->analyzeWithLocalHeuristics($session);
    }

    /**
     * Builds the context array needed for the AI prompt.
     */
    protected function buildContext(StudentExamSession $session): array // FIX: Type hint changed
    {
        $exam = $session->exam;

        $duration = 0;
        if ($session->end_time && $session->start_time) {
            // Calculate actual time taken
            $duration = $session->start_time->diffInMinutes($session->end_time);
        }

        return [
            'student' => $session->user->name ?? 'Unknown Student',
            'exam_title' => $exam->title ?? 'Unknown Exam',
            'score_percentage' => $session->progress_percentage,
            'is_passed' => $session->is_passed,
            'correct_count' => $session->correct_answers,
            'total_questions' => $session->total_questions,
            'time_taken_minutes' => $duration,
            'allowed_duration' => $exam->duration_minutes ?? 60,
        ];
    }

    protected function buildSystemPrompt(array $context): string
    {
        $status = $context['is_passed'] ? 'Passed' : 'Failed';

        $jsonStructure = '{
            "summary": "Brief overall performance summary.",
            "strengths": ["Key strength 1", "Key strength 2"],
            "weaknesses": ["Key weakness 1", "Key weakness 2"],
            "recommendation": "Actionable advice for improvement."
        }';

        return "Act as an educational consultant. Analyze this student exam result and provide feedback.
        
        Context:
        - Exam: {$context['exam_title']}
        - Score: {$context['score_percentage']}% ({$status})
        - Time: {$context['time_taken_minutes']} mins (Allowed: {$context['allowed_duration']} mins).
        
        Rules:
        1. If time taken is very low (< 20% of allowed) and score is low, mention rushing/guessing.
        2. Return strictly raw JSON matching this structure: $jsonStructure
        3. Keep tone professional and encouraging.";
    }

    protected function analyzeWithGemini(array $context): array
    {
        $apiKey = Config::get('ai.gemini.api_key');
        $model = Config::get('ai.gemini.model', 'gemini-1.5-flash');

        try {
            $prompt = $this->buildSystemPrompt($context);
            
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

            if ($response->successful()) {
                return $this->parseResponse($response->json('candidates.0.content.parts.0.text'), 'Gemini AI');
            }

            Log::error("Gemini Analysis Error: " . $response->body());
            return $this->analyzeWithLocalHeuristicsMock($context, 'Gemini Error');

        } catch (\Exception $e) {
            Log::error("Gemini Analysis Exception: " . $e->getMessage());
            return $this->analyzeWithLocalHeuristicsMock($context, 'Connection Error');
        }
    }

    protected function analyzeWithOpenAI(array $context): array
    {
        $apiKey = Config::get('ai.openai_key');
        $model = Config::get('ai.default_model', 'gpt-3.5-turbo');

        try {
            $prompt = $this->buildSystemPrompt($context);

            $response = Http::withToken($apiKey)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an educational data analyst.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                return $this->parseResponse($response->json('choices.0.message.content'), 'OpenAI');
            }
            
            return $this->analyzeWithLocalHeuristicsMock($context, 'OpenAI Error');

        } catch (\Exception $e) {
            return $this->analyzeWithLocalHeuristicsMock($context, 'Connection Error');
        }
    }

    /**
     * Local Heuristics Fallback - Accepts the new model type for compatibility.
     */
    protected function analyzeWithLocalHeuristics(StudentExamSession $session): array // FIX: Type hint changed
    {
        return $this->analyzeWithLocalHeuristicsMock($this->buildContext($session), 'Local Logic');
    }

    /**
     * Local Heuristics Mock (Used internally for error handling and local analysis)
     */
    protected function analyzeWithLocalHeuristicsMock(array $context, string $source): array
    {
        // Ensure context keys exist for safe math/logic
        $score = $context['score_percentage'] ?? 0;
        $allowedDuration = $context['allowed_duration'] ?? 60;
        $timeTaken = $context['time_taken_minutes'] ?? 0;
        
        $timeRatio = $allowedDuration > 0 ? ($timeTaken / $allowedDuration) : 1;

        $summary = "Student scored {$score}%. ";
        $strengths = [];
        $weaknesses = [];
        $recommendation = "";

        if ($score >= 80) {
            $summary .= "Excellent performance showing strong command of the subject.";
            $strengths[] = "High accuracy rate";
            $strengths[] = "Concept mastery";
            $recommendation = "Ready for advanced modules.";
        } elseif ($score >= 50) {
            $summary .= "Average performance. Concepts are understood but application needs work.";
            $strengths[] = "Passing grade achieved";
            $weaknesses[] = "Inconsistent answers";
            $recommendation = "Review incorrect answers and revise core topics.";
        } else {
            $summary .= "Performance indicates a struggle with core concepts.";
            $weaknesses[] = "Significant knowledge gaps";
            $recommendation = "Remedial study recommended before retaking.";
        }

        if ($timeRatio < 0.2) {
            $weaknesses[] = "Rushed attempt (Finished too fast)";
            $recommendation .= " Take more time to read questions thoroughly.";
        }

        return [
            'status' => 'success',
            'source' => $source,
            'data' => [
                'summary' => $summary,
                'strengths' => array_values(array_unique($strengths)), // Clean up unique strengths
                'weaknesses' => array_values(array_unique($weaknesses)), // Clean up unique weaknesses
                'recommendation' => $recommendation
            ]
        ];
    }

    protected function parseResponse(?string $rawText, string $source): array
    {
        if (!$rawText) return $this->analyzeWithLocalHeuristicsMock([], 'Empty Response');

        // Extract JSON block (handles common LLM markdown formatting)
        if (Str::contains($rawText, '```json')) {
            $cleanJson = Str::between($rawText, '```json', '```');
        } else {
            $cleanJson = $rawText;
        }
        
        $data = json_decode(trim($cleanJson), true);

        if (json_last_error() === JSON_ERROR_NONE && isset($data['summary'])) {
            return [
                'status' => 'success',
                'source' => $source,
                'data' => $data
            ];
        }

        // Fallback if parsing failed
        Log::warning("AI JSON Parse Failed: " . json_last_error_msg() . " Raw: " . Str::limit($rawText, 100));
        return $this->analyzeWithLocalHeuristicsMock([], 'JSON Parse Error');
    }
}
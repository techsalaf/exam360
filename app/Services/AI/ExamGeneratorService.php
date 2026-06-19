<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class ExamGeneratorService
{
    protected $systemInstruction = "You are an exam administrative assistant. Analyze the user prompt and generate exam metadata in strict JSON format. 
    Fields required: 
    - title (string)
    - duration (int, in minutes)
    - pass_percentage (int)
    - total_marks (int) 
    - description (string). 
    Do not include questions. Return only raw JSON.";

    public function generate(string $prompt, ?string $requestedProvider = null): array
    {
        $provider = $requestedProvider ?: SystemSetting::where('key', 'ai_driver')->value('value');

        if ($provider === 'gemini') {
            return $this->generateWithGemini($prompt);
        }

        if ($provider === 'openai') {
            return $this->generateWithOpenAI($prompt);
        }

        return $this->generateWithCustomLogic($prompt);
    }

    protected function generateWithGemini(string $prompt): array
    {
        $apiKey = SystemSetting::where('key', 'ai_gemini_api_key')->value('value');
        $model = SystemSetting::where('key', 'ai_gemini_model')->value('value') ?: 'gemini-1.5-flash';

        if (empty($apiKey)) {
            return $this->handleFallback('Gemini API key not configured', $prompt);
        }

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
            
            $payload = [
                'contents' => [
                    ['parts' => [['text' => $this->systemInstruction . "\n\nUser Prompt: " . $prompt]]]
                ],
                'config' => [
                    'responseMimeType' => 'application/json',
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                ]
            ];
            
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            if ($response->successful()) {
                $rawText = $response->json('candidates.0.content.parts.0.text');
                return $this->parseJsonResponse($rawText, $prompt);
            }

            Log::error('Gemini API Error: ' . $response->body());
            return $this->handleFallback('Gemini API request failed: ' . $response->status(), $prompt);

        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return $this->handleFallback($e->getMessage(), $prompt);
        }
    }

    protected function generateWithOpenAI(string $prompt): array
    {
        $apiKey = SystemSetting::where('key', 'ai_openai_api_key')->value('value');
        $model = SystemSetting::where('key', 'ai_openai_model')->value('value') ?: 'gpt-3.5-turbo';

        if (empty($apiKey)) {
            return $this->handleFallback('OpenAI API key not configured', $prompt);
        }

        try {
            $response = Http::withToken($apiKey)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $this->systemInstruction . " Return ONLY the raw JSON object."],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $rawText = $response->json('choices.0.message.content');
                return $this->parseJsonResponse($rawText, $prompt);
            }

            Log::error('OpenAI API Error: ' . $response->body());
            return $this->handleFallback('OpenAI API request failed: ' . $response->status(), $prompt);

        } catch (\Exception $e) {
            Log::error('OpenAI Exception: ' . $e->getMessage());
            return $this->handleFallback($e->getMessage(), $prompt);
        }
    }

    protected function generateWithCustomLogic(string $prompt): array
    {
        $promptLower = strtolower($prompt);
        
        $duration = 30;
        if (preg_match('/(\d+)\s*-?\s*(minute|min|m)/i', $prompt, $matches)) {
            $duration = (int)$matches[1];
        } elseif (preg_match('/(\d+)\s*-?\s*(hour|hr|h)/i', $prompt, $matches)) {
            $duration = (int)$matches[1] * 60;
        } elseif (str_contains($promptLower, 'hour')) {
            $duration = 60;
        }

        $pass = 40;
        if (preg_match('/pass.*?(\d+)%?/i', $prompt, $matches)) {
            $pass = (int)$matches[1];
        } elseif (str_contains($promptLower, 'hard') || str_contains($promptLower, 'strict')) {
            $pass = 60;
        } elseif (str_contains($promptLower, 'easy')) {
            $pass = 33;
        }

        $marks = 100;
        if (preg_match('/(\d+)\s*(mark|point)/i', $prompt, $matches)) {
            $marks = (int)$matches[1];
        } elseif ($duration <= 15) {
            $marks = 25;
        } elseif ($duration <= 30) {
            $marks = 50;
        }

        $title = Str::title($prompt);
        if (strlen($title) > 60) {
            $words = explode(' ', $title);
            $title = implode(' ', array_slice($words, 0, 8)) . '...';
        }

        return [
            'status' => 'success',
            'source' => 'local_logic',
            'data' => [
                'title' => str_replace(['"', "'"], '', $title),
                'duration' => $duration,
                'pass_percentage' => min(100, max(1, $pass)),
                'total_marks' => max(1, $marks),
                'description' => "Auto-generated based on keywords from: " . ucfirst($prompt)
            ]
        ];
    }

    private function parseJsonResponse(?string $rawText, string $prompt): array
    {
        if (!$rawText) {
            return $this->handleFallback('Empty response from AI Provider', $prompt);
        }

        $start = strpos($rawText, '{');
        $end = strrpos($rawText, '}');
        
        $cleanJson = '';
        if ($start !== false && $end !== false) {
            $cleanJson = substr($rawText, $start, ($end - $start) + 1);
        } else {
            $cleanJson = str_replace(['```json', '```'], '', $rawText);
        }
        
        $data = json_decode($cleanJson, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            return [
                'status' => 'success',
                'source' => 'ai_generated',
                'data' => array_merge([
                    'title' => Str::limit(Str::title($prompt), 60),
                    'duration' => 30,
                    'pass_percentage' => 40,
                    'total_marks' => 100,
                    'description' => 'AI Generated Metadata'
                ], $data)
            ];
        }

        return $this->handleFallback('Failed to parse AI JSON response: ' . json_last_error_msg(), $prompt);
    }

    private function handleFallback(string $reason, string $prompt): array
    {
        Log::warning("AI Exam Generation Fallback: {$reason}");
        
        $result = $this->generateWithCustomLogic($prompt);
        $result['message'] = $reason; 
        
        return $result;
    }
}
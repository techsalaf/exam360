<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\SystemSetting;

class QuestionGeneratorService
{
    public function generate(string $topic, int $count, string $difficulty, string $type, ?string $requestedProvider = null): array
    {
        $provider = $requestedProvider ?: SystemSetting::where('key', 'ai_driver')->value('value');

        if ($provider === 'gemini') {
            return $this->generateWithGemini($topic, $count, $difficulty, $type);
        }

        if ($provider === 'openai') {
            return $this->generateWithOpenAI($topic, $count, $difficulty, $type);
        }

        return $this->generateWithCustomLogic($topic, $count, $difficulty, $type);
    }

    protected function buildPrompt(string $topic, int $count, string $difficulty, string $type): string
    {
        return "You are an expert academic examiner. Generate exactly {$count} unique questions.
        Topic: '{$topic}'. 
        Difficulty: '{$difficulty}'. 
        Type: '{$type}'.
        
        CRITICAL CONTENT RULES:
        1. DO NOT repeat the words 'Regarding {$topic}' or start every question with the same phrase.
        2. Create varied, challenging scenarios and diverse conceptual questions.
        3. Ensure question text is high-quality and directly addresses the subject matter.

        CRITICAL OUTPUT RULES:
        1. Return ONLY raw JSON. No markdown formatting.
        2. Follow strictly this schema:
        
        IF MCQ:
        {
            \"questions\": [
                {
                    \"question_text\": \"Unique question content...\",
                    \"type\": \"mcq\",
                    \"options\": {\"A\": \"Text\", \"B\": \"Text\", \"C\": \"Text\", \"D\": \"Text\"},
                    \"correct_answer\": \"A\", 
                    \"explanation\": \"Deep logic explanation.\"
                }
            ]
        }

        IF TRUE_FALSE:
        {
            \"questions\": [
                {
                    \"question_text\": \"Specific statement about the topic.\",
                    \"type\": \"true_false\",
                    \"options\": null,
                    \"correct_answer\": \"True\", 
                    \"explanation\": \"Fact-based explanation.\"
                }
            ]
        }

        IF SHORT_ANSWER:
        {
            \"questions\": [
                {
                    \"question_text\": \"Direct conceptual question...\",
                    \"type\": \"short_answer\",
                    \"options\": null,
                    \"correct_answer\": \"Keyword\", 
                    \"explanation\": \"Clarification.\"
                }
            ]
        }";
    }

    protected function generateWithGemini(string $topic, int $count, string $difficulty, string $type): array
    {
        $apiKey = SystemSetting::where('key', 'ai_gemini_api_key')->value('value');
        $model = SystemSetting::where('key', 'ai_gemini_model')->value('value') ?: 'gemini-1.5-flash';

        if (empty($apiKey)) {
            return $this->handleFallback($topic, $count, $difficulty, $type, 'Gemini API Key missing');
        }

        try {
            $prompt = $this->buildPrompt($topic, $count, $difficulty, $type);
            
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
            
            $payload = [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
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
                return $this->parseJsonResponse($rawText, $topic, $count, $difficulty, $type);
            }

            Log::error("Gemini Generation Error: " . $response->body());
            return $this->handleFallback($topic, $count, $difficulty, $type, 'Gemini API Error');

        } catch (\Exception $e) {
            Log::error("Gemini Exception: " . $e->getMessage());
            return $this->handleFallback($topic, $count, $difficulty, $type, 'Connection Error');
        }
    }

    protected function generateWithOpenAI(string $topic, int $count, string $difficulty, string $type): array
    {
        $apiKey = SystemSetting::where('key', 'ai_openai_api_key')->value('value');
        $model = SystemSetting::where('key', 'ai_openai_model')->value('value') ?: 'gpt-3.5-turbo';

        if (empty($apiKey)) {
            return $this->handleFallback($topic, $count, $difficulty, $type, 'OpenAI API Key missing');
        }

        try {
            $prompt = $this->buildPrompt($topic, $count, $difficulty, $type);

            $response = Http::withToken($apiKey)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You generate high-variety exam questions in valid JSON.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.8,
                ]);

            if ($response->successful()) {
                $rawText = $response->json('choices.0.message.content');
                return $this->parseJsonResponse($rawText, $topic, $count, $difficulty, $type);
            }

            Log::error("OpenAI Generation Error: " . $response->body());
            return $this->handleFallback($topic, $count, $difficulty, $type, 'OpenAI API Error');

        } catch (\Exception $e) {
            Log::error("OpenAI Exception: " . $e->getMessage());
            return $this->handleFallback($topic, $count, $difficulty, $type, 'Connection Error');
        }
    }

    protected function generateWithCustomLogic(string $topic, int $count, string $difficulty, string $type): array
    {
        $questions = [];

        for ($i = 1; $i <= $count; $i++) {
            if ($type === 'mcq') {
                $questions[] = [
                    'question_text' => "Draft MCQ Question #{$i}: Core concept of '{$topic}'...",
                    'type' => 'mcq',
                    'options' => [
                        'A' => "Option A",
                        'B' => "Option B",
                        'C' => "Option C",
                        'D' => "Option D",
                    ],
                    'correct_answer' => 'A',
                    'explanation' => "Explain the fundamental principles related to '{$topic}'."
                ];
            } elseif ($type === 'true_false') {
                $questions[] = [
                    'question_text' => "Statement #{$i}: '{$topic}' involves dynamic processes.",
                    'type' => 'true_false',
                    'options' => null,
                    'correct_answer' => 'True',
                    'explanation' => "Verified theoretical concept explanation."
                ];
            } else {
                $questions[] = [
                    'question_text' => "Short Answer #{$i}: Define the primary objective of '{$topic}'.",
                    'type' => 'short_answer',
                    'options' => null,
                    'correct_answer' => "Primary Objective",
                    'explanation' => "Concise theoretical definition."
                ];
            }
        }

        return ['status' => 'success', 'questions' => $questions, 'source' => 'local_template'];
    }

    private function parseJsonResponse(?string $rawText, string $topic, int $count, string $difficulty, string $type): array
    {
        if (!$rawText) {
            return $this->handleFallback($topic, $count, $difficulty, $type, 'Empty Response');
        }

        $start = strpos($rawText, '{');
        $end = strrpos($rawText, '}');
        
        if ($start !== false && $end !== false) {
            $cleanJson = substr($rawText, $start, ($end - $start) + 1);
            $data = json_decode($cleanJson, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($data['questions'])) {
                return [
                    'status' => 'success',
                    'questions' => $data['questions'],
                    'source' => 'ai_generated'
                ];
            }
        }

        return $this->handleFallback($topic, $count, $difficulty, $type, 'JSON Parsing Error');
    }

    private function handleFallback(string $topic, int $count, string $difficulty, string $type, string $reason): array
    {
        $result = $this->generateWithCustomLogic($topic, $count, $difficulty, $type);
        $result['message'] = $reason;
        return $result;
    }
}
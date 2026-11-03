<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiApiService
{
    protected ?string $apiKey;

    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        $this->model = config('services.gemini.model', env('GEMINI_MODEL', 'gemini-1.5-flash'));
    }

    /**
     * Check if Gemini API is configured with an active key.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->apiKey) && $this->apiKey !== 'YOUR_GEMINI_API_KEY';
    }

    /**
     * Call Gemini API with structured prompt and return raw text or decoded JSON.
     */
    public function generateStructuredJson(string $systemInstruction, string $userPrompt): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

            $response = Http::timeout(30)->post($endpoint, [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $systemInstruction],
                    ],
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $userPrompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                    'temperature' => 0.4,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($text) {
                    $decoded = json_decode($text, true);
                    if (is_array($decoded)) {
                        return $decoded;
                    }
                }
            } else {
                Log::warning('Gemini API Error: '.$response->body());
            }
        } catch (\Throwable $e) {
            Log::error('Gemini API Exception: '.$e->getMessage());
        }

        return null;
    }
}

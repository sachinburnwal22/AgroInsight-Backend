<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $model;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        // Using gemini-1.5-pro as it has a higher free tier limit (1500/day) compared to 2.5-flash (20/day)
        $this->model = 'gemini-1.5-pro';
    }

    public function generateContent(string $prompt): string
    {
        if (empty($this->apiKey)) {
            Log::error('Gemini API key is not set in .env');
            return 'AI Error: GEMINI_API_KEY is not configured on the backend.';
        }

        $url = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
                return 'AI Response could not be parsed.';
            }

            Log::error('Gemini API Error', ['response' => $response->json()]);
            return 'AI Error: Failed to fetch from Gemini API.';
        } catch (\Exception $e) {
            Log::error('Gemini API Exception', ['error' => $e->getMessage()]);
            return 'AI Error: An exception occurred.';
        }
    }
}

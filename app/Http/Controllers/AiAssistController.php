<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class AiAssistController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function suggest(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'region' => 'nullable|string'
        ]);

        $content = $request->content;
        $region = $request->region ?? 'Unknown region';

        $prompt = "A farmer from {$region} says: \"{$content}\". Give practical, concise advice in simple language to help this farmer. Do not use overly complex terminology.";

        $aiResponse = $this->geminiService->generateContent($prompt);

        return response()->json([
            'suggestion' => $aiResponse
        ]);
    }
}

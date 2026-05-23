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

    public function marketRecommendations(Request $request)
    {
        $request->validate([
            'crop' => 'nullable|string',
            'region' => 'nullable|string'
        ]);

        $crop = $request->crop ?? 'Wheat';
        $region = $request->region ?? 'Central India';

        $prompt = "As an agricultural AI expert, list the best 3 types of products (seeds, fertilizers, or tools) for growing {$crop} in {$region} in India. Provide the output as a clean bulleted list where each bullet starts with the product type name in bold followed by a brief description. Make it concise and actionable.";

        $aiResponse = $this->geminiService->generateContent($prompt);

        return response()->json([
            'recommendations' => $aiResponse
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class SoilController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'ph' => 'required|numeric'
        ]);

        $ph = $request->input('ph');

        $prompt = "Soil pH is {$ph}. Explain in simple terms and suggest improvements. Return JSON format with exact keys: 'soil_type', 'recommendation', 'suggested_crops' (array of strings). Do not use markdown backticks.";

        $aiResponse = $this->geminiService->generateContent($prompt);

        // Try to parse the JSON response from AI
        $cleanedResponse = str_replace(['```json', '```'], '', $aiResponse);
        $parsed = json_decode($cleanedResponse, true);

        if (!$parsed) {
            // Fallback if AI didn't return proper JSON
            return response()->json([
                'soil_type' => 'Unknown (pH: ' . $ph . ')',
                'recommendation' => $aiResponse,
                'suggested_crops' => []
            ]);
        }

        return response()->json($parsed);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class AlertController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'irrigation_threshold' => 'required|numeric',
            'temperature_threshold' => 'required|numeric',
        ]);

        $irr = $request->input('irrigation_threshold');
        $temp = $request->input('temperature_threshold');

        // Logic to store alert configuration in DB would go here (mocked for now)

        $prompt = "If irrigation drops below {$irr}% or temperature exceeds {$temp}°C, generate a short, urgent warning message to the farmer.";

        $aiResponse = $this->geminiService->generateContent($prompt);

        return response()->json([
            'status' => 'success',
            'message' => $aiResponse
        ]);
    }
}

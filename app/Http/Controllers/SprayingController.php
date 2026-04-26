<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class SprayingController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function schedule(Request $request)
    {
        $request->validate([
            'crop' => 'required|string',
            'date' => 'required|date',
        ]);

        $crop = $request->input('crop');
        $date = $request->input('date');

        // Logic to save schedule in DB would go here (mocked for now)

        $prompt = "Crop: {$crop}, Date: {$date}
Is this a good time for spraying? Give a suggestion and confirmation message.";

        $aiResponse = $this->geminiService->generateContent($prompt);

        return response()->json([
            'status' => 'success',
            'message' => 'Schedule saved successfully.',
            'ai_suggestion' => $aiResponse
        ]);
    }
}

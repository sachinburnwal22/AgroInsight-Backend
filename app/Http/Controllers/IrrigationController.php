<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class IrrigationController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function start(Request $request)
    {
        $region = $request->input('region', 'Unknown');
        $irrigationValue = $request->input('irrigation', 0);
        $crop = $request->input('crop', 'Unknown');

        $prompt = "Region: {$region}
Irrigation: {$irrigationValue}%
Crop: {$crop}
Should irrigation be started? Give short practical advice.";

        $aiResponse = $this->geminiService->generateContent($prompt);
        
        $action = (strpos(strtolower($aiResponse), 'wait') !== false || strpos(strtolower($aiResponse), 'delay') !== false) ? 'wait' : 'start';

        return response()->json([
            'action' => $action,
            'message' => $aiResponse
        ]);
    }
}

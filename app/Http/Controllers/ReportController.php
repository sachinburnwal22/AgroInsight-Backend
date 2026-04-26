<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class ReportController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function generate(Request $request)
    {
        $region = $request->input('region', 'Global');
        $reportType = $request->input('report_type', 'General Overview');
        $data = $request->input('data');

        $prompt = "Generate a '{$reportType}' report for the region '{$region}' based on this agricultural data: " . json_encode($data) . " Return JSON format with exact keys: 'summary', 'issues', 'recommendations'. Do not use markdown backticks. Make sure the response is highly specific to {$region} and the requested {$reportType}.";

        $aiResponse = $this->geminiService->generateContent($prompt);

        $cleanedResponse = str_replace(['```json', '```'], '', $aiResponse);
        $parsed = json_decode($cleanedResponse, true);

        if (!$parsed) {
            return response()->json([
                'summary' => "{$reportType} generated for {$region} based on current dashboard data.",
                'issues' => 'Data could not be deeply analyzed.',
                'recommendations' => $aiResponse
            ]);
        }

        return response()->json($parsed);
    }
}

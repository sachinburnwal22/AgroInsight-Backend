<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GovernmentScheme;
use App\Services\GeminiService;

class SchemeController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    // 1. Fetch all schemes with optional filters
    public function getAll(Request $request)
    {
        $query = GovernmentScheme::orderBy('name', 'asc');

        if ($request->has('state') && !empty($request->state)) {
            $state = $request->state;
            $query->where(function ($q) use ($state) {
                $q->where('state', 'like', "%{$state}%")
                  ->orWhere('state', 'All India');
            });
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $schemes = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $schemes
        ]);
    }

    // 2. Fetch schemes by state
    public function getByState(Request $request, $state)
    {
        $schemes = GovernmentScheme::where('state', 'like', "%{$state}%")
            ->orWhere('state', 'All India')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'state' => $state,
            'data' => $schemes
        ]);
    }

    // 3. Explain Scheme using Gemini AI
    public function explainScheme(Request $request, $id)
    {
        $scheme = GovernmentScheme::findOrFail($id);
        
        $language = $request->input('language', 'English');
        $validLanguages = ['English', 'Hindi', 'Punjabi', 'Bengali', 'Tamil'];
        if (!in_array($language, $validLanguages)) {
            $language = 'English';
        }

        $user = auth('sanctum')->user();
        $userContext = "";
        if ($user) {
            $userContext = "The user is from region '{$user->region}' and grows crop preferences like Wheat/Rice.";
        }

        $prompt = "As an agricultural counselor, explain this government scheme to a farmer in simple terms:\n";
        $prompt .= "Scheme Name: {$scheme->name}\n";
        $prompt .= "Description: {$scheme->description}\n";
        $prompt .= "Eligibility details: {$scheme->eligibility}\n";
        $prompt .= "Benefits details: {$scheme->benefits}\n";
        $prompt .= "Apply Link: {$scheme->apply_link}\n\n";
        $prompt .= "Context: {$userContext}\n";
        $prompt .= "Task:\n";
        $prompt .= "1. Explain in 3 simple steps how the farmer can apply.\n";
        $prompt .= "2. Summarize eligibility in 2 bullet points.\n";
        $prompt .= "3. Output the response in {$language} language in clean, easy-to-read markdown format.\n";

        $aiResponse = $this->geminiService->generateContent($prompt);

        if (empty($aiResponse) || str_contains($aiResponse, 'AI Error:')) {
            $aiResponse = "### Scheme Explanation ({$language} Fallback)\n\n" .
                          "**Benefits:**\n{$scheme->benefits}\n\n" .
                          "**How to apply:**\nVisit: [Apply Online]({$scheme->apply_link}) or contact your local agriculture office.";
        }

        return response()->json([
            'status' => 'success',
            'scheme_id' => $scheme->id,
            'language' => $language,
            'explanation' => $aiResponse
        ]);
    }
}

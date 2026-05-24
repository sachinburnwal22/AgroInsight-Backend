<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsArticle;
use App\Models\SavedArticle;
use App\Services\NewsSyncService;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    protected $newsSyncService;
    protected $geminiService;

    public function __construct(NewsSyncService $newsSyncService, GeminiService $geminiService)
    {
        $this->newsSyncService = $newsSyncService;
        $this->geminiService = $geminiService;
    }

    // 1. Fetch live agricultural news with filters
    public function getLive(Request $request)
    {
        // Dynamically parse new articles in background/cache
        $this->newsSyncService->syncNews();

        $query = NewsArticle::orderBy('published_at', 'desc');

        // Apply filters
        if ($request->has('state') && !empty($request->state)) {
            $query->where(function ($q) use ($request) {
                $q->where('state', 'like', "%{$request->state}%")
                  ->orWhereNull('state')
                  ->orWhere('state', 'All India');
            });
        }

        if ($request->has('crop') && !empty($request->crop)) {
            $query->where('crop', 'like', "%{$request->crop}%");
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('summary', 'like', "%{$searchTerm}%");
            });
        }

        $news = $query->paginate(12);

        // Append saved status if authenticated
        $user = auth('sanctum')->user();
        if ($user) {
            $savedIds = SavedArticle::where('user_id', $user->id)->pluck('article_id')->toArray();
            foreach ($news->items() as $item) {
                $item->is_saved = in_array($item->id, $savedIds);
            }
        } else {
            foreach ($news->items() as $item) {
                $item->is_saved = false;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $news
        ]);
    }

    // 2. Fetch trending articles
    public function getTrending(Request $request)
    {
        $trending = NewsArticle::where('is_trending', true)
            ->orWhere('category', 'MSP')
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $trending
        ]);
    }

    // 3. Fetch location-based personalized news
    public function getLocationBased(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $state = $user->region ?: 'Global';
        
        // Find news specific to user state or crop
        $query = NewsArticle::orderBy('published_at', 'desc');

        if ($state !== 'Global') {
            $query->where(function ($q) use ($state) {
                $q->where('state', 'like', "%{$state}%")
                  ->orWhereNull('state')
                  ->orWhere('state', 'All India');
            });
        }

        // Fetch user crops from crop_recommendations to personalize
        $recommendedCrops = \Illuminate\Support\Facades\DB::table('crop_recommendations')
            ->where('user_id', $user->id)
            ->pluck('crop_name')
            ->toArray();

        if (!empty($recommendedCrops)) {
            $query->orWhereIn('crop', $recommendedCrops);
        }

        $news = $query->take(8)->get();

        $savedIds = SavedArticle::where('user_id', $user->id)->pluck('article_id')->toArray();
        foreach ($news as $item) {
            $item->is_saved = in_array($item->id, $savedIds);
        }

        return response()->json([
            'status' => 'success',
            'user_region' => $state,
            'user_crops' => $recommendedCrops,
            'data' => $news
        ]);
    }

    // 4. Save/Bookmark news article
    public function saveArticle(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:news_articles,id'
        ]);

        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $articleId = $request->article_id;

        $exists = SavedArticle::where('user_id', $user->id)
            ->where('article_id', $articleId)
            ->first();

        if ($exists) {
            $exists->delete();
            return response()->json([
                'status' => 'success',
                'is_saved' => false,
                'message' => 'Article removed from bookmarks.'
            ]);
        }

        SavedArticle::create([
            'user_id' => $user->id,
            'article_id' => $articleId
        ]);

        return response()->json([
            'status' => 'success',
            'is_saved' => true,
            'message' => 'Article saved successfully!'
        ]);
    }

    // 5. Get saved/bookmarked articles for user
    public function getSavedArticles(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $articles = $user->savedArticles()->orderBy('saved_articles.created_at', 'desc')->get();
        foreach ($articles as $art) {
            $art->is_saved = true;
        }

        return response()->json([
            'status' => 'success',
            'data' => $articles
        ]);
    }

    // 6. Get AI Summary & Translation of news article
    public function getAiSummary(Request $request, $id)
    {
        $article = NewsArticle::findOrFail($id);
        
        $language = $request->input('language', 'English'); // English, Hindi, Punjabi, Bengali, Tamil
        $validLanguages = ['English', 'Hindi', 'Punjabi', 'Bengali', 'Tamil'];
        if (!in_array($language, $validLanguages)) {
            $language = 'English';
        }

        // If English is selected and we already have standard summary, let's use it as basis
        // But if translation is requested, we invoke Gemini API
        $prompt = "As an agricultural advisor, read this news article details:\nTitle: {$article->title}\nDetails: {$article->content}\n\nTask:\n1. Provide a short 3-bullet-point summary written in extremely simple, farmer-friendly terms.\n2. Add a 'Farmer Action Advice' section in 1-2 sentences explaining what the farmer should do.\n3. Translate the entire output (including labels) into {$language}.\n\nFormat your output cleanly in Markdown.";

        $aiResponse = $this->geminiService->generateContent($prompt);

        // Fallback translation if API fails
        if (empty($aiResponse) || str_contains($aiResponse, 'AI Error:')) {
            $aiResponse = "### AI Summary (English Fallback)\n• " . $article->summary . "\n\n### Farmer Action Advice\nStay updated with local agricultural departments regarding this development.";
        }

        // Cache the English AI summary in the database if it hasn't been saved yet
        if ($language === 'English' && empty($article->ai_summary)) {
            $article->ai_summary = $aiResponse;
            $article->save();
        }

        return response()->json([
            'status' => 'success',
            'article_id' => $article->id,
            'language' => $language,
            'ai_summary' => $aiResponse
        ]);
    }
}

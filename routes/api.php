<?php

use App\Http\Controllers\RegionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\AiAssistController;
use App\Http\Controllers\CommunityChatController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Backend connected 🚀',
    ]);
});

use App\Http\Controllers\IrrigationController;
use App\Http\Controllers\SprayingController;
use App\Http\Controllers\SoilController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;

Route::get('/regions', [RegionController::class, 'index']);
Route::get('/regions/{id}', [RegionController::class, 'show']);
Route::get('/analytics', [RegionController::class, 'analytics']);
Route::get('/crops', [RegionController::class, 'crops']);
Route::get('/dashboard', [RegionController::class, 'dashboard']);

Route::post('/irrigation/start', [IrrigationController::class, 'start']);
Route::post('/spraying/schedule', [SprayingController::class, 'schedule']);
Route::post('/soil/analyze', [SoilController::class, 'analyze']);
Route::post('/weather/view', [WeatherController::class, 'view']);
Route::post('/report/generate', [ReportController::class, 'generate']);
Route::post('/alerts', [AlertController::class, 'store']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Marketplace Public Routes
Route::get('/shops', [ShopController::class, 'index']);
Route::get('/shops/{id}/products', [ShopController::class, 'products']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [ProfileController::class, 'show']);
    Route::post('/user/update', [ProfileController::class, 'update']); // Using POST to handle multipart/form-data for file uploads

    // Community System Routes
    Route::get('/communities', [CommunityController::class, 'index']);
    Route::post('/communities', [CommunityController::class, 'store']);
    Route::get('/communities/{id}', [CommunityController::class, 'show']);
    Route::post('/community/join', [CommunityController::class, 'join']);
    Route::post('/community/leave', [CommunityController::class, 'leave']);

    Route::get('/community/{id}/chat', [CommunityChatController::class, 'index']);
    Route::post('/community/{id}/chat', [CommunityChatController::class, 'store']);

    Route::get('/posts/{community_id}', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    Route::post('/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    Route::post('/posts/{id}/like', [LikeController::class, 'toggle']);

    Route::post('/ai/suggest', [AiAssistController::class, 'suggest']);
    Route::post('/market/recommendations', [AiAssistController::class, 'marketRecommendations']);

    // Community Co-Explore Routes
    Route::post('/invite/send', [\App\Http\Controllers\CoExploreController::class, 'sendInvite']);
    Route::post('/invite/respond', [\App\Http\Controllers\CoExploreController::class, 'respondInvite']);
    Route::get('/notifications', [\App\Http\Controllers\CoExploreController::class, 'getNotifications']);
    Route::post('/session/end', [\App\Http\Controllers\CoExploreController::class, 'endSession']);

    // Advisor Authenticated Routes
    Route::post('/location/update', [\App\Http\Controllers\AdvisorController::class, 'updateLocation']);

    // Marketplace Cart Routes
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);

    // Marketplace Payment Routes
    Route::post('/payment/create-order', [PaymentController::class, 'createOrder']);
    Route::post('/payment/verify', [PaymentController::class, 'verify']);

    // AgriIntel Authenticated Routes
    Route::get('/news/location-based', [\App\Http\Controllers\NewsController::class, 'getLocationBased']);
    Route::post('/articles/save', [\App\Http\Controllers\NewsController::class, 'saveArticle']);
    Route::get('/articles/saved', [\App\Http\Controllers\NewsController::class, 'getSavedArticles']);
});

// Advisor Public Routes
Route::get('/weather/live', [\App\Http\Controllers\AdvisorController::class, 'getLiveWeather']);
Route::get('/weather/alerts', [\App\Http\Controllers\AdvisorController::class, 'getWeatherAlerts']);
Route::get('/crop/recommendations', [\App\Http\Controllers\AdvisorController::class, 'getCropRecommendations']);
Route::get('/product/recommendations', [\App\Http\Controllers\AdvisorController::class, 'getProductRecommendations']);

// AgriIntel Public Routes
Route::get('/news/live', [\App\Http\Controllers\NewsController::class, 'getLive']);
Route::get('/news/trending', [\App\Http\Controllers\NewsController::class, 'getTrending']);
Route::get('/schemes/all', [\App\Http\Controllers\SchemeController::class, 'getAll']);
Route::get('/schemes/state/{state}', [\App\Http\Controllers\SchemeController::class, 'getByState']);
Route::get('/alerts/government', [\App\Http\Controllers\GovAlertController::class, 'index']);
Route::post('/news/{id}/ai-summary', [\App\Http\Controllers\NewsController::class, 'getAiSummary']);
Route::post('/schemes/{id}/explain', [\App\Http\Controllers\SchemeController::class, 'explainScheme']);



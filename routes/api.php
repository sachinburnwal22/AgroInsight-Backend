<?php

use App\Http\Controllers\RegionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [ProfileController::class, 'show']);
    Route::post('/user/update', [ProfileController::class, 'update']); // Using POST to handle multipart/form-data for file uploads
});

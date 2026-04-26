<?php

use App\Http\Controllers\RegionController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Backend connected 🚀',
    ]);
});

Route::get('/regions', [RegionController::class, 'index']);
Route::get('/regions/{id}', [RegionController::class, 'show']);
Route::get('/analytics', [RegionController::class, 'analytics']);
Route::get('/crops', [RegionController::class, 'crops']);
Route::get('/dashboard', [RegionController::class, 'dashboard']);

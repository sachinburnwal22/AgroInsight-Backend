<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionController;

Route::get('/test', function () {
    return response()->json([
        "status" => "success",
        "message" => "Backend connected 🚀"
    ]);
});

Route::get('/regions', [RegionController::class, 'index']);
Route::get('/regions/{id}', [RegionController::class, 'show']);
Route::get('/analytics', [RegionController::class, 'analytics']);
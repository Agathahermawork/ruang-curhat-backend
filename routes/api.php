<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CounselorController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'Ruang Curhat API is online.',
        'app_url' => config('app.url'),
        'timestamp' => now()->toISOString(),
    ]);
});

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/auth/me', [AuthController::class, 'me']);
Route::match(['put', 'patch'], '/auth/me', [AuthController::class, 'updateMe']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::apiResource('counselors', CounselorController::class);

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint API tidak ditemukan.',
    ], 404);
});

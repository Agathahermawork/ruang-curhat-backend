<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CounselorController;
use App\Http\Controllers\Api\TestingApiController;
use Illuminate\Support\Facades\Route;

Route::get('/health', [TestingApiController::class, 'health']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/auth/me', [AuthController::class, 'me']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::apiResource('counselors', CounselorController::class);

Route::prefix('testing')->group(function (): void {
    Route::get('/health', [TestingApiController::class, 'health']);
    Route::get('/runtime', [TestingApiController::class, 'runtime']);
    Route::get('/database', [TestingApiController::class, 'database']);

    Route::get('/notes', [TestingApiController::class, 'index']);
    Route::post('/notes', [TestingApiController::class, 'store']);
    Route::get('/notes/{note}', [TestingApiController::class, 'show']);
    Route::match(['put', 'patch'], '/notes/{note}', [TestingApiController::class, 'update']);
    Route::delete('/notes/{note}', [TestingApiController::class, 'destroy']);
});
Route::get('/test-koneksi', function () {
    return response()->json([
        'success' => true,
        'message' => 'Mantap! API Laravel 12 sudah online dan siap dipakai Android.',
        'waktu_server' => now()->toDateTimeString(),
    ]);
});

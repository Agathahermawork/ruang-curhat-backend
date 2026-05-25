<?php

use App\Http\Controllers\Api\TestingApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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

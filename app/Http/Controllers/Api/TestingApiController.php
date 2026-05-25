<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TestingNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class TestingApiController extends Controller
{
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'API is reachable.',
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function runtime(): JsonResponse
    {
        return response()->json([
            'app' => config('app.name'),
            'environment' => app()->environment(),
            'debug' => (bool) config('app.debug'),
            'timezone' => config('app.timezone'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ]);
    }

    public function database(): JsonResponse
    {
        try {
            DB::select('select 1 as connection_test');

            return response()->json([
                'status' => 'ok',
                'message' => 'Database connection is working.',
                'connection' => config('database.default'),
                'timestamp' => now()->toISOString(),
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database connection failed.',
                'connection' => config('database.default'),
                'error' => $exception->getMessage(),
            ], 503);
        }
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => TestingNote::latest()->limit(20)->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $note = TestingNote::create($validated);

        return response()->json([
            'message' => 'Testing note created.',
            'data' => $note,
        ], 201);
    }

    public function show(TestingNote $note): JsonResponse
    {
        return response()->json([
            'data' => $note,
        ]);
    }

    public function update(Request $request, TestingNote $note): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'message' => ['sometimes', 'required', 'string'],
        ]);

        $note->update($validated);

        return response()->json([
            'message' => 'Testing note updated.',
            'data' => $note->fresh(),
        ]);
    }

    public function destroy(TestingNote $note): JsonResponse
    {
        $note->delete();

        return response()->json([
            'message' => 'Testing note deleted.',
        ]);
    }
}

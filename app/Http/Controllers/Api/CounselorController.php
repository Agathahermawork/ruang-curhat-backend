<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Counselor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CounselorController extends Controller
{
    private const RELIGIONS = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

    public function index(Request $request): JsonResponse
    {
        $query = Counselor::query()->where('is_active', true);

        if ($request->filled('religion')) {
            $query->where('religion', $request->string('religion'));
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('religion')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validatedPayload($request);
        $validated['pangkat'] = Str::upper($validated['pangkat']);
        $validated['emoji'] = $validated['emoji'] ?? $this->emojiForReligion($validated['religion']);

        $counselor = Counselor::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Konselor berhasil disimpan.',
            'data' => $counselor,
        ], 201);
    }

    public function show(Counselor $counselor): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $counselor,
        ]);
    }

    public function update(Request $request, Counselor $counselor): JsonResponse
    {
        $validated = $this->validatedPayload($request, $counselor);

        if (array_key_exists('pangkat', $validated)) {
            $validated['pangkat'] = Str::upper($validated['pangkat']);
        }

        if (isset($validated['religion']) && ! isset($validated['emoji'])) {
            $validated['emoji'] = $this->emojiForReligion($validated['religion']);
        }

        $counselor->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Konselor berhasil diperbarui.',
            'data' => $counselor->fresh(),
        ]);
    }

    public function destroy(Counselor $counselor): JsonResponse
    {
        $counselor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Konselor berhasil dihapus.',
        ]);
    }

    private function validatedPayload(Request $request, ?Counselor $counselor = null): array
    {
        $required = $counselor ? 'sometimes' : 'required';

        return $request->validate([
            'name' => [$required, 'string', 'max:255'],
            'pangkat' => [$required, 'string', 'max:100'],
            'nrp' => [$required, 'string', 'max:100', Rule::unique('counselors', 'nrp')->ignore($counselor)],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'kesatuan' => ['nullable', 'string', 'max:255'],
            'telegram' => [$required, 'string', 'max:100'],
            'religion' => [$required, Rule::in(self::RELIGIONS)],
            'emoji' => ['nullable', 'string', 'max:50'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }

    private function emojiForReligion(string $religion): string
    {
        return match ($religion) {
            'Islam' => '🕌',
            'Kristen' => '✝️',
            'Katolik' => '⛪',
            'Hindu' => '🕉️',
            'Buddha' => '☸️',
            default => '🙏',
        };
    }
}

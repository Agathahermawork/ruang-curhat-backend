<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password tidak sesuai.',
            ], 401);
        }

        $user->forceFill([
            'api_token' => hash('sha256', Str::random(80)),
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Autentikasi berhasil.',
            'token_type' => 'Bearer',
            'token' => $user->api_token,
            'data' => $this->userPayload($user),
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:6'],
            'name' => ['required', 'string', 'max:255'],
            'pangkat' => ['required', 'string', 'max:100'],
            'nrp' => ['required', 'string', 'max:100', 'unique:users,nrp'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'kesatuan' => ['nullable', 'string', 'max:255'],
            'telegram' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in(['admin', 'user'])],
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => $validated['password'] ?? 'password123',
            'name' => $validated['name'],
            'pangkat' => Str::upper($validated['pangkat']),
            'nrp' => $validated['nrp'],
            'jabatan' => $validated['jabatan'] ?? 'Prajurit Dinas',
            'kesatuan' => $validated['kesatuan'] ?? 'Lanud Abdulrachman Saleh',
            'telegram' => $validated['telegram'] ?? null,
            'role' => $validated['role'] ?? 'user',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Akun dinas berhasil dibuat.',
            'data' => $this->userPayload($user),
        ], 201);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $this->userFromBearerToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => $this->userPayload($user),
        ]);
    }

    public function updateMe(Request $request): JsonResponse
    {
        $user = $this->userFromBearerToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'pangkat' => ['required', 'string', 'max:100'],
            'nrp' => ['required', 'string', 'max:100', Rule::unique('users', 'nrp')->ignore($user)],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'kesatuan' => ['nullable', 'string', 'max:255'],
            'telegram' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $payload = [
            'name' => $validated['name'],
            'pangkat' => Str::upper($validated['pangkat']),
            'nrp' => $validated['nrp'],
            'jabatan' => $validated['jabatan'] ?? null,
            'kesatuan' => $validated['kesatuan'] ?? null,
            'telegram' => $validated['telegram'] ?? null,
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $this->userPayload($user->fresh()),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $this->userFromBearerToken($request);

        if ($user) {
            $user->forceFill(['api_token' => null])->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    private function userFromBearerToken(Request $request): ?User
    {
        $token = $request->bearerToken();

        if (! $token) {
            return null;
        }

        return User::where('api_token', $token)->first();
    }

    private function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'pangkat' => $user->pangkat,
            'nrp' => $user->nrp,
            'jabatan' => $user->jabatan,
            'kesatuan' => $user->kesatuan,
            'telegram' => $user->telegram,
            'role' => $user->role,
        ];
    }
}

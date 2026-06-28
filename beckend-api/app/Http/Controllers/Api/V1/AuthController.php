<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/v1/auth/login
     * Login dan dapatkan API token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        // Cek kredensial
        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Log login gagal
            $this->logLogin($user ?? null, $request, 'failed');

            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // Log login sukses
        $this->logLogin($user, $request, 'success');

        // Buat token Sanctum (hapus token lama agar tidak menumpuk)
        $user->tokens()->where('name', 'mobile-app')->delete();
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data'    => [
                'token' => $token,
                'user'  => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->getRoleNames()->first(), // Admin/Operator/Pimpinan
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                ],
            ],
        ]);
    }

    /**
     * GET /api/v1/auth/me
     * Ambil data user yang sedang login.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'role'        => $user->getRoleNames()->first(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'created_at'  => $user->created_at->toISOString(),
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/logout
     * Revoke token aktif dan logout.
     */
    public function logout(Request $request): JsonResponse
    {
        // Hapus token yang sedang dipakai
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    // ─── Private Helpers ───────────────────────────────────────────────────────

    private function logLogin(?User $user, Request $request, string $status): void
    {
        if (! $user) return;

        LoginLog::create([
            'user_id'    => $user->id,
            'ip_address' => $request->ip(),
            'device'     => $request->input('device', 'Unknown'),
            'user_agent' => $request->userAgent(),
            'status'     => $status,
            'login_time' => now(),
        ]);
    }
}

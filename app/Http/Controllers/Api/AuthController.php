<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class AuthController extends ApiController
{
    /**
     * POST /api/v1/auth/login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        // Accept username OR email
        $field = filter_var($validated['username'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $user = User::where($field, $validated['username'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return $this->fail('Invalid credentials.', 401);
        }

        // Revoke old tokens for the same device (optional)
        $device = $validated['device_name'] ?? 'mobile';
        $user->tokens()->where('name', $device)->delete();

        $token = $user->createToken($device)->plainTextToken;

        return $this->ok([
            'user'  => $this->userPayload($user),
            'token' => $token,
        ], 'Login successful.');
    }

    /**
     * POST /api/v1/auth/register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['nullable', 'string', 'max:100'],
            'username'     => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'        => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
            'mood'         => ['nullable', 'string', 'max:50'],
            'safety'       => ['nullable', 'in:Yes,No'],
            'country'      => ['nullable', 'string', 'max:50'],
            'location'     => ['nullable', 'string', 'max:100'],
            'device_name'  => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name'       => $validated['name'] ?? $validated['username'],
            'username'   => $validated['username'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => 'user',
            'mood'       => $validated['mood'] ?? null,
            'safety'     => $validated['safety'] ?? null,
            'country'    => $validated['country'] ?? null,
            'location'   => $validated['location'] ?? null,
            'risk_level' => 'low',
        ]);

        $token = $user->createToken($validated['device_name'] ?? 'mobile')->plainTextToken;

        return $this->ok([
            'user'  => $this->userPayload($user),
            'token' => $token,
        ], 'Account created.', 201);
    }

    /**
     * POST /api/v1/auth/guest
     */
    public function guest(Request $request)
    {
        $username = 'Guest_' . rand(1000, 9999);
        $password = Str::random(8);

        $guest = User::create([
            'name'       => $username,
            'username'   => $username,
            'email'      => strtolower($username) . '@guest.local',
            'password'   => Hash::make($password),
            'role'       => 'user',
            'country'    => 'Unknown',
            'location'   => 'Unknown',
            'risk_level' => 'low',
        ]);

        $token = $guest->createToken('guest')->plainTextToken;

        return $this->ok([
            'user'     => $this->userPayload($guest),
            'token'    => $token,
            'username' => $username,
            'password' => $password,
        ], 'Guest session created.', 201);
    }

    /**
     * GET /api/v1/auth/me
     */
    public function me(Request $request)
    {
        return $this->ok([
            'user' => $this->userPayload($request->user()),
        ]);
    }

    /**
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok(null, 'Logged out.');
    }

    /**
     * POST /api/v1/auth/refresh
     * Revokes current token, issues a new one.
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        $name = $request->user()->currentAccessToken()->name;

        $user->currentAccessToken()->delete();
        $newToken = $user->createToken($name)->plainTextToken;

        return $this->ok(['token' => $newToken], 'Token refreshed.');
    }

    /**
     * POST /api/v1/auth/forgot-password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Standard Laravel password reset flow
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return $this->ok(null, 'Password reset link sent to your email.');
        }

        return $this->fail(__($status), 422);
    }

    /**
     * POST /api/v1/auth/reset-password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete(); // revoke all tokens
            }
        );

        return $status === Password::PASSWORD_RESET
            ? $this->ok(null, 'Password reset successfully.')
            : $this->fail(__($status), 422);
    }

    /**
     * Shape user JSON consistently.
     */
    private function userPayload(User $user): array
    {
        return [
            'id'                => $user->id,
            'name'              => $user->name,
            'username'          => $user->username,
            'email'             => $user->email,
            'role'              => $user->role,
            'country'           => $user->country,
            'location'          => $user->location,
            'risk_level'        => $user->risk_level,
            'profile_image_url' => $user->profile_image_url,
            'created_at'        => $user->created_at?->toIso8601String(),
        ];
    }
}
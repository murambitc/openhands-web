<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProfileController extends ApiController
{
    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $request->file('image')->store('profiles', 'public');
        $user->update(['profile_image' => $path]);

        return $this->ok([
            'profile_image_url' => $user->profile_image_url,
        ], 'Profile image updated.');
    }

    public function updateName(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $request->user()->update($validated);

        return $this->ok(['name' => $validated['name']], 'Name updated.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $request->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return $this->fail('Current password is incorrect.', 422);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        // Revoke all other tokens for security
        $currentId = $user->currentAccessToken()->id;
        $user->tokens()->where('id', '!=', $currentId)->delete();

        return $this->ok(null, 'Password updated.');
    }
}
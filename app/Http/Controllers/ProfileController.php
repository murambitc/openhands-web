<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updateImage(Request $request)
    {
        $request->validate([
            'profile_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = auth()->user();

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $request->file('profile_image')->store('profiles', 'public');
        $user->update(['profile_image' => $path]);

        return response()->json([
            'status' => 'success',
            'path' => $user->profile_image_url,
        ]);
    }

    public function updateName(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        auth()->user()->update($validated);

        return response()->json(['status' => 'success']);
    }
}
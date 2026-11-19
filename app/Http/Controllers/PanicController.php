<?php

namespace App\Http\Controllers;

use App\Models\PanicLog;
use Illuminate\Http\Request;

class PanicController extends Controller
{
    public function trigger(Request $request)
    {
        $validated = $request->validate([
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
        ]);

        PanicLog::create([
            'user_id' => auth()->id(),
            'latitude' => $validated['lat'] ?? null,
            'longitude' => $validated['lng'] ?? null,
        ]);

        return response()->json(['status' => 'logged']);
    }
}
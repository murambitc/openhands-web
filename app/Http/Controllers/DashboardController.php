<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentCheckins = $user->checkins()->latest()->take(5)->get();

        return view('dashboard', compact('user', 'recentCheckins'));
    }

    public function storeCheckin(Request $request)
    {
        $validated = $request->validate([
            'mood' => ['required', 'string', 'max:100'],
            'comment' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        auth()->user()->checkins()->create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in saved',
        ]);
    }
}
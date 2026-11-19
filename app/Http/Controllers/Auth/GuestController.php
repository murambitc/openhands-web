<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    /**
     * Create a temporary guest user and log them in.
     */
    public function login()
    {
        $username = 'Guest_' . rand(1000, 9999);
        $password = Str::random(8);

        // Create a real user row so all features work
        $guest = User::create([
            'name'         => $username,
            'username'     => $username,
            'email'        => strtolower($username) . '@guest.local',
            'password'     => bcrypt($password),
            'role'         => 'user',
            'country'      => 'Unknown',
            'location'     => 'Unknown',
            'risk_level'   => 'low',
        ]);

        // Log them in
        Auth::login($guest);

        // Return credentials to display in the modal
        return response()->json([
            'status'   => 'success',
            'username' => $username,
            'password' => $password,
            'redirect' => route('dashboard'),
        ]);
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the fields your form actually sends
        $validated = $request->validate([
            // Wellness intake (Step 1)
            'mood'     => ['required', 'string', 'max:50'],
            'safety'   => ['required', 'string', 'in:Yes,No'],

            // Location (Step 2)
            'country'  => ['required', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:100'],

            // Account (Step 3)
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'mood.required'       => 'Please select your mood.',
            'safety.required'     => 'Please indicate if you feel safe.',
            'safety.in'           => 'Safety answer must be Yes or No.',
            'country.required'    => 'Please select your country.',
            'location.required'   => 'Please enter your city / town / village.',
            'username.required'   => 'Please choose a username.',
            'username.unique'     => 'That username is already taken.',
            'email.unique'        => 'That email is already registered.',
            'password.confirmed'  => 'Passwords do not match.',
        ]);

        // Create the user — `name` is auto-filled with `username`
        $user = User::create([
            'name'         => $validated['username'],
            'username'     => $validated['username'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'mood'         => $validated['mood'],
            'safety'       => $validated['safety'],
            'country'      => $validated['country'],
            'location'     => $validated['location'],
            'role'         => 'user',
            'risk_level'   => 'low',
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect with success flash → dashboard shows the confetti modal
        return redirect()->route('dashboard')
            ->with('registration_success', true)
            ->with('registration_username', $user->username);
    }
}
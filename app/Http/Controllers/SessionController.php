<?php

namespace App\Http\Controllers;

use App\Models\WellnessSession;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = WellnessSession::active()
            ->orderByDesc('session_date')
            ->get();

        return view('sessions', compact('sessions'));
    }
}
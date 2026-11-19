<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    // Mark stale panic alerts as resolved
    \App\Models\PanicLog::where('resolved', false)
        ->where('created_at', '<', now()->subHours(24))
        ->update(['resolved' => true]);
})->daily();

Schedule::call(function () {
    // Purge inactive sessions
    \App\Models\WellnessSession::where('session_date', '<', now()->subMonths(6))
        ->where('is_active', true)
        ->update(['is_active' => false]);
})->weekly();
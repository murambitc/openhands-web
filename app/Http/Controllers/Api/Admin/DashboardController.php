<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Checkin;
use App\Models\Organization;
use App\Models\User;
use App\Models\WellnessSession;

class DashboardController extends ApiController
{
    public function index()
    {
        $referrals = Organization::withCount('screenings')
            ->orderByDesc('screenings_count')
            ->take(10)
            ->get()
            ->map(fn ($o) => [
                'organization' => $o->name,
                'count'        => $o->screenings_count,
            ]);

        $recentCheckins = Checkin::with('user:id,username')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($c) => [
                'id'         => $c->id,
                'username'   => $c->user?->username ?? 'Unknown',
                'mood'       => $c->mood,
                'comment'    => $c->comment,
                'created_at' => $c->created_at->toIso8601String(),
            ]);

        return $this->ok([
            'stats' => [
                'users'         => User::count(),
                'checkins'      => Checkin::count(),
                'sessions'      => WellnessSession::count(),
                'organizations' => Organization::count(),
                'high_risk'     => User::where('risk_level', 'high')->count(),
            ],
            'mood_distribution' => Checkin::selectRaw('mood, COUNT(*) as total')
                ->groupBy('mood')
                ->pluck('total', 'mood'),
            'referrals'        => $referrals,
            'recent_checkins'  => $recentCheckins,
        ]);
    }
}
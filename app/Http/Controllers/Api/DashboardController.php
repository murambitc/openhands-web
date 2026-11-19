<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends ApiController
{
    public function index(Request $request)
    {
        $user = $request->user();

        $recentCheckins = $user->checkins()
            ->latest()
            ->take(5)
            ->get(['id', 'mood', 'comment', 'created_at']);

        $totalCheckins     = $user->checkins()->count();
        $thisMonthCheckins = $user->checkins()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $latestScreening = $user->screenings()->latest()->first();

        return $this->ok([
            'greeting' => $this->greetingForNow(),
            'stats'    => [
                'total_checkins'   => $totalCheckins,
                'month_checkins'   => $thisMonthCheckins,
                'risk_level'       => $latestScreening->risk_level ?? 'low',
                'streak'           => $this->calculateStreak($user),
            ],
            'recent_checkins' => $recentCheckins->map(fn ($c) => [
                'id'         => $c->id,
                'mood'       => $c->mood,
                'comment'    => $c->comment,
                'created_at' => $c->created_at->toIso8601String(),
                'ago'        => $c->created_at->diffForHumans(),
            ]),
            'latest_screening' => $latestScreening ? [
                'risk_level'  => $latestScreening->risk_level,
                'concern'     => $latestScreening->main_concern,
                'created_at'  => $latestScreening->created_at->toIso8601String(),
            ] : null,
        ]);
    }

    private function greetingForNow(): string
    {
        $hour = (int) now()->format('G');
        return $hour < 12 ? 'Good morning'
            : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    }

    private function calculateStreak($user): int
    {
        $dates = $user->checkins()
            ->selectRaw('DATE(created_at) as d')
            ->distinct()
            ->orderByDesc('d')
            ->pluck('d')
            ->toArray();

        if (empty($dates)) return 0;

        $streak = 0;
        $cursor = Carbon::today();

        if ($dates[0] !== $cursor->toDateString()) {
            $cursor->subDay();
        }

        foreach ($dates as $d) {
            if ($d === $cursor->toDateString()) {
                $streak++;
                $cursor->subDay();
            } else break;
        }

        return $streak;
    }
}
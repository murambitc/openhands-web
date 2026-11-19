<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Organization;
use App\Models\Screening;
use App\Models\User;
use App\Models\WellnessSession;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $checkinCount = Checkin::count();
        $sessionCount = WellnessSession::count();

        $moodData = Checkin::selectRaw('mood, COUNT(*) as total')
            ->groupBy('mood')
            ->get()
            ->pluck('total', 'mood');

        $referrals = Organization::withCount(['screenings'])
            ->orderByDesc('screenings_count')
            ->take(10)
            ->get()
            ->map(fn ($o) => [
                'organization' => $o->name,
                'count' => $o->screenings_count,
            ]);

        return view('admin.dashboard', compact(
            'userCount', 'checkinCount', 'sessionCount', 'moodData', 'referrals'
        ));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MapLink;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function updateFooter(Request $request)
    {
        $validated = $request->validate([
            'footer_text' => ['required', 'string', 'max:1000'],
        ]);

        Setting::set('footer_text', $validated['footer_text']);

        return back()->with('success', 'Footer updated');
    }

    public function updateMap(Request $request)
    {
        $validated = $request->validate([
            'map_url' => ['required', 'string', 'max:1000'],
        ]);

        MapLink::where('is_active', true)->update(['is_active' => false]);
        MapLink::create([
            'map_url' => $validated['map_url'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Map updated');
    }

    public function liveData()
    {
        $users = \App\Models\User::whereNotNull('last_lat')
            ->select(['username', 'last_lat', 'last_lon', 'risk_level', 'last_active'])
            ->get();

        $panics = \App\Models\PanicLog::latest()->take(20)->get();

        return response()->json([
            'users' => $users,
            'panics' => $panics,
        ]);
    }
}
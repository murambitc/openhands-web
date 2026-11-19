<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function index()
    {
        $checkins = auth()->user()
            ->checkins()
            ->latest()
            ->paginate(20);

        return view('mood-logs', compact('checkins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood'    => ['required', 'string', 'max:100'],
            'comment' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        $checkin = auth()->user()->checkins()->create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Check-in saved',
            'data'    => $checkin,
        ]);
    }

    public function tracker()
    {
        return view('tracker');
    }

    /**
     * GET /checkins/chart?days=7
     */
    public function chart(Request $request)
    {
        $days = (int) $request->input('days', 7);
        $days = max(1, min(90, $days));

        $from = now()->subDays($days - 1)->startOfDay();

        $checkins = auth()->user()
            ->checkins()
            ->where('created_at', '>=', $from)
            ->get()
            ->groupBy(fn ($c) => $c->created_at->format('Y-m-d'));

        $labels   = [];
        $positive = [];
        $sad      = [];
        $stressed = [];
        $angry    = [];
        $calm     = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');

            $dayMoods = $checkins->get($date, collect());

            $positive[] = $dayMoods->filter(fn ($c) => $this->moodCategory($c->mood) === 'positive')->count();
            $sad[]      = $dayMoods->filter(fn ($c) => $this->moodCategory($c->mood) === 'sad')->count();
            $stressed[] = $dayMoods->filter(fn ($c) => $this->moodCategory($c->mood) === 'stressed')->count();
            $angry[]    = $dayMoods->filter(fn ($c) => $this->moodCategory($c->mood) === 'angry')->count();
            $calm[]     = $dayMoods->filter(fn ($c) => $this->moodCategory($c->mood) === 'calm')->count();
        }

        return response()->json([
            'labels'   => $labels,
            'positive' => $positive,
            'sad'      => $sad,
            'stressed' => $stressed,
            'angry'    => $angry,
            'calm'     => $calm,
        ]);
    }

    public function destroy(Checkin $checkin)
    {
        if ($checkin->user_id !== auth()->id()) {
            abort(403);
        }

        $checkin->delete();

        return response()->json(['status' => 'success']);
    }

    private function moodCategory(string $mood): string
    {
        $m = strtolower($mood);

        if (str_contains($m, 'happy')
            || str_contains($m, 'joyful')
            || str_contains($m, 'excited')
            || str_contains($m, 'loved')
            || str_contains($m, 'confident')
            || str_contains($m, 'content')
            || str_contains($m, 'relaxed')) {
            return 'positive';
        }
        if (str_contains($m, 'sad')
            || str_contains($m, 'crying')
            || str_contains($m, 'disappointed')
            || str_contains($m, 'tired')) {
            return 'sad';
        }
        if (str_contains($m, 'stressed')
            || str_contains($m, 'anxious')
            || str_contains($m, 'overwhelmed')
            || str_contains($m, 'worried')) {
            return 'stressed';
        }
        if (str_contains($m, 'angry') || str_contains($m, 'frustrated')) {
            return 'angry';
        }
        if (str_contains($m, 'calm')
            || str_contains($m, 'peaceful')
            || str_contains($m, 'neutral')) {
            return 'calm';
        }
        return 'calm';
    }
}
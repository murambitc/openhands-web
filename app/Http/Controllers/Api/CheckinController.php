<?php

namespace App\Http\Controllers\Api;

use App\Models\Checkin;
use Illuminate\Http\Request;

class CheckinController extends ApiController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood'    => ['required', 'string', 'max:100'],
            'comment' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        $checkin = $request->user()->checkins()->create($validated);

        return $this->ok([
            'id'         => $checkin->id,
            'mood'       => $checkin->mood,
            'comment'    => $checkin->comment,
            'created_at' => $checkin->created_at->toIso8601String(),
        ], 'Check-in saved.', 201);
    }

    public function index(Request $request)
    {
        $checkins = $request->user()
            ->checkins()
            ->latest()
            ->paginate(20);

        return $this->paginated($checkins);
    }

    public function destroy(Request $request, Checkin $checkin)
    {
        if ($checkin->user_id !== $request->user()->id) {
            return $this->fail('Forbidden.', 403);
        }

        $checkin->delete();

        return $this->ok(null, 'Check-in deleted.');
    }

    public function chart(Request $request)
    {
        $days = max(1, min(90, (int) $request->input('days', 7)));

        $from = now()->subDays($days - 1)->startOfDay();

        $checkins = $request->user()
            ->checkins()
            ->where('created_at', '>=', $from)
            ->get()
            ->groupBy(fn ($c) => $c->created_at->format('Y-m-d'));

        $labels   = $positive = $sad = $stressed = $angry = $calm = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');

            $dayMoods = $checkins->get($date, collect());

            $positive[] = $dayMoods->filter(fn ($c) => $this->category($c->mood) === 'positive')->count();
            $sad[]      = $dayMoods->filter(fn ($c) => $this->category($c->mood) === 'sad')->count();
            $stressed[] = $dayMoods->filter(fn ($c) => $this->category($c->mood) === 'stressed')->count();
            $angry[]    = $dayMoods->filter(fn ($c) => $this->category($c->mood) === 'angry')->count();
            $calm[]     = $dayMoods->filter(fn ($c) => $this->category($c->mood) === 'calm')->count();
        }

        return $this->ok([
            'labels'   => $labels,
            'positive' => $positive,
            'sad'      => $sad,
            'stressed' => $stressed,
            'angry'    => $angry,
            'calm'     => $calm,
        ]);
    }

    private function category(string $mood): string
    {
        $m = strtolower($mood);
        if (str_contains($m, 'happy') || str_contains($m, 'joyful') || str_contains($m, 'excited') || str_contains($m, 'loved') || str_contains($m, 'confident')) return 'positive';
        if (str_contains($m, 'sad') || str_contains($m, 'crying') || str_contains($m, 'tired')) return 'sad';
        if (str_contains($m, 'stressed') || str_contains($m, 'anxious') || str_contains($m, 'worried')) return 'stressed';
        if (str_contains($m, 'angry') || str_contains($m, 'frustrated')) return 'angry';
        return 'calm';
    }
}
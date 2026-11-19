<?php

namespace App\Http\Controllers\Api;

use App\Models\WellnessSession;

class SessionController extends ApiController
{
    public function index()
    {
        $sessions = WellnessSession::orderByDesc('session_date')
            ->get()
            ->map(fn ($s) => $this->format($s));

        return $this->ok($sessions);
    }

    public function show($id)
    {
        $session = WellnessSession::find($id);
        if (! $session) return $this->fail('Not found.', 404);
        return $this->ok($this->format($session));
    }

    private function format($s): array
    {
        return [
            'id'           => $s->id,
            'title'        => $s->title,
            'description'  => $s->description,
            'session_date' => $s->session_date?->format('Y-m-d'),
            'session_time' => $s->session_time,
            'location'     => $s->location,
            'image_url'    => $s->image ? asset('storage/' . $s->image) : null,
            'is_active'    => (bool) $s->is_active,
            'created_at'   => $s->created_at->toIso8601String(),
        ];
    }
}
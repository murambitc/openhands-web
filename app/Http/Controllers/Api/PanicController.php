<?php

namespace App\Http\Controllers\Api;

use App\Models\PanicLog;
use Illuminate\Http\Request;

class PanicController extends ApiController
{
    public function trigger(Request $request)
    {
        $validated = $request->validate([
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
        ]);

        $log = PanicLog::create([
            'user_id'   => $request->user()->id,
            'latitude'  => $validated['lat'] ?? null,
            'longitude' => $validated['lng'] ?? null,
        ]);

        return $this->ok([
            'panic_id' => $log->id,
            'emergency_contacts' => [
                'police'    => '999',
                'ambulance' => '997',
                'whatsapp'  => '26777649963',
            ],
        ], 'Panic logged. Emergency contacts provided.');
    }

    public function history(Request $request)
    {
        $logs = $request->user()
            ->panicLogs()
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($p) => [
                'id'         => $p->id,
                'lat'        => $p->latitude,
                'lng'        => $p->longitude,
                'resolved'   => (bool) $p->resolved,
                'created_at' => $p->created_at->toIso8601String(),
            ]);

        return $this->ok($logs);
    }
}
<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\WellnessSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SessionController extends ApiController
{
    public function index()
    {
        $sessions = WellnessSession::latest()->paginate(20);
        return $this->paginated($sessions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:150'],
            'description'  => ['required', 'string'],
            'session_date' => ['required', 'date'],
            'session_time' => ['required'],
            'location'     => ['required', 'string', 'max:200'],
            'image'        => ['nullable', 'image', 'max:5120'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sessions', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $session = WellnessSession::create($validated);

        return $this->ok($session, 'Session created.', 201);
    }

    public function show($id)
    {
        $session = WellnessSession::find($id);
        if (! $session) return $this->fail('Not found.', 404);
        return $this->ok($session);
    }

    public function update(Request $request, $id)
    {
        $session = WellnessSession::find($id);
        if (! $session) return $this->fail('Not found.', 404);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:150'],
            'description'  => ['required', 'string'],
            'session_date' => ['required', 'date'],
            'session_time' => ['required'],
            'location'     => ['required', 'string', 'max:200'],
            'image'        => ['nullable', 'image', 'max:5120'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($session->image) Storage::disk('public')->delete($session->image);
            $validated['image'] = $request->file('image')->store('sessions', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $session->update($validated);

        return $this->ok(null, 'Session updated.');
    }

    public function destroy($id)
    {
        $session = WellnessSession::find($id);
        if (! $session) return $this->fail('Not found.', 404);

        if ($session->image) Storage::disk('public')->delete($session->image);
        $session->delete();

        return $this->ok(null, 'Session deleted.');
    }
}
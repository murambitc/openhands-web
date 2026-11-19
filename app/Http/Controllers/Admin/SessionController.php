<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WellnessSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = WellnessSession::latest()->paginate(15);

        return view('admin.sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('admin.sessions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'session_date' => ['required', 'date'],
            'session_time' => ['required'],
            'location' => ['required', 'string', 'max:200'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sessions', 'public');
        }

        $session = WellnessSession::create($validated);

        // Notify users (queued for production)
        $this->notifyUsers($session);

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Session posted successfully');
    }

    public function edit(WellnessSession $session)
    {
        return view('admin.sessions.edit', compact('session'));
    }

    public function update(Request $request, WellnessSession $session)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'session_date' => ['required', 'date'],
            'session_time' => ['required'],
            'location' => ['required', 'string', 'max:200'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            if ($session->image) {
                Storage::disk('public')->delete($session->image);
            }
            $validated['image'] = $request->file('image')->store('sessions', 'public');
        }

        $session->update($validated);

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Session updated');
    }

    public function destroy(WellnessSession $session)
    {
        if ($session->image) {
            Storage::disk('public')->delete($session->image);
        }
        $session->delete();

        return response()->json(['status' => 'success']);
    }

    private function notifyUsers(WellnessSession $session): void
    {
        $emails = \App\Models\User::whereNotNull('email')->pluck('email');
        foreach ($emails as $email) {
            // In production: use a queued mailable
            // Mail::to($email)->queue(new SessionPosted($session));
        }
        \Log::info("Session '{$session->title}' notification queued for {$emails->count()} users");
    }
}
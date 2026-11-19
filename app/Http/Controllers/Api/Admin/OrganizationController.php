<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends ApiController
{
    public function index()
    {
        $orgs = Organization::withCount('screenings')
            ->orderBy('name')
            ->get();

        return $this->ok($orgs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'phone'       => ['nullable', 'string', 'max:30'],
            'whatsapp'    => ['nullable', 'string', 'max:30'],
            'email'       => ['nullable', 'email'],
            'website'     => ['nullable', 'url'],
            'map_link'    => ['nullable', 'url'],
            'logo'        => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('orgs', 'public');
        }

        $org = Organization::create($validated);

        return $this->ok($org, 'Organization created.', 201);
    }

    public function show($id)
    {
        $org = Organization::withCount('screenings')->find($id);
        if (! $org) return $this->fail('Not found.', 404);
        return $this->ok($org);
    }

    public function update(Request $request, $id)
    {
        $org = Organization::find($id);
        if (! $org) return $this->fail('Not found.', 404);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'phone'       => ['nullable', 'string', 'max:30'],
            'whatsapp'    => ['nullable', 'string', 'max:30'],
            'email'       => ['nullable', 'email'],
            'website'     => ['nullable', 'url'],
            'map_link'    => ['nullable', 'url'],
            'logo'        => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($org->logo) Storage::disk('public')->delete($org->logo);
            $validated['logo'] = $request->file('logo')->store('orgs', 'public');
        }

        $org->update($validated);

        return $this->ok(null, 'Organization updated.');
    }

    public function destroy($id)
    {
        $org = Organization::find($id);
        if (! $org) return $this->fail('Not found.', 404);

        if ($org->logo) Storage::disk('public')->delete($org->logo);
        $org->delete();

        return $this->ok(null, 'Organization deleted.');
    }

    public function referrals($id)
    {
        $org = Organization::find($id);
        if (! $org) return $this->fail('Not found.', 404);

        $referrals = $org->screenings()
            ->with('user:id,username,email')
            ->latest()
            ->get()
            ->map(fn ($s) => [
                'id'         => $s->user_id,
                'username'   => $s->user?->username ?? 'Unknown',
                'email'      => $s->user?->email,
                'created_at' => $s->created_at->toIso8601String(),
            ]);

        return $this->ok($referrals);
    }
}
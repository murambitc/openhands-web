<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::orderBy('name')->paginate(15);

        return view('admin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('admin.organizations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'website' => ['nullable', 'url'],
            'map_link' => ['nullable', 'url'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('orgs', 'public');
        }

        Organization::create($validated);

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization added successfully');
    }

    public function edit(Organization $organization)
    {
        return view('admin.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'website' => ['nullable', 'url'],
            'map_link' => ['nullable', 'url'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($organization->logo) {
                Storage::disk('public')->delete($organization->logo);
            }
            $validated['logo'] = $request->file('logo')->store('orgs', 'public');
        }

        $organization->update($validated);

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization updated');
    }

    public function destroy(Organization $organization)
    {
        if ($organization->logo) {
            Storage::disk('public')->delete($organization->logo);
        }
        $organization->delete();

        return response()->json(['status' => 'success']);
    }

    public function referrals(Organization $organization)
    {
        $referrals = $organization->screenings()
            ->with('user:id,username,email')
            ->latest()
            ->get()
            ->map(fn ($s) => [
                'id' => $s->user_id,
                'username' => $s->user?->username ?? 'Unknown',
                'email' => $s->user?->email ?? 'N/A',
                'created_at' => $s->created_at->toIso8601String(),
            ]);

        return response()->json($referrals);
    }
}
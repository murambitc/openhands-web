<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends ApiController
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $users = User::query()
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(20);

        return $this->paginated($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'username'   => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', Rules\Password::defaults()],
            'role'       => ['required', 'in:user,admin'],
            'risk_level' => ['required', 'in:low,medium,high'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return $this->ok($user->only(['id', 'username', 'email', 'role', 'risk_level']), 'User created.', 201);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (! $user) return $this->fail('User not found.', 404);

        return $this->ok($user->only([
            'id', 'name', 'username', 'email', 'role', 'country', 'location', 'risk_level', 'created_at',
        ]));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (! $user) return $this->fail('User not found.', 404);

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'username'   => ['required', 'max:50', 'unique:users,username,' . $id],
            'email'      => ['required', 'email', 'unique:users,email,' . $id],
            'role'       => ['required', 'in:user,admin'],
            'risk_level' => ['required', 'in:low,medium,high'],
            'country'    => ['nullable', 'string', 'max:50'],
            'location'   => ['nullable', 'string', 'max:100'],
            'password'   => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return $this->ok(null, 'User updated.');
    }

    public function destroy(Request $request, $id)
    {
        if ((int) $id === $request->user()->id) {
            return $this->fail('You cannot delete your own account.', 422);
        }

        $request->validate(['password' => ['required', 'string']]);

        if (! Hash::check($request->password, $request->user()->password)) {
            return $this->fail('Incorrect admin password.', 422);
        }

        $user = User::find($id);
        if (! $user) return $this->fail('User not found.', 404);

        $user->delete();

        return $this->ok(null, 'User deleted.');
    }

    public function referrals(Request $request, $id)
    {
        $user = User::find($id);
        if (! $user) return $this->fail('User not found.', 404);

        $referrals = $user->screenings()
            ->whereNotNull('preferred_referral')
            ->with('organization:id,name')
            ->get()
            ->map(fn ($s) => [
                'screening_id' => $s->id,
                'organization' => $s->organization?->name,
                'risk_level'   => $s->risk_level,
                'created_at'   => $s->created_at->toIso8601String(),
            ]);

        return $this->ok($referrals);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Screening;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * GET /admin/users — list with search + pagination.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $users = User::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('country', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'  => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'users'  => User::where('role', 'user')->count(),
            'high'   => User::where('risk_level', 'high')->count(),
        ];

        return view('admin.users.index', compact('users', 'search', 'stats'));
    }

    /**
     * GET /admin/users/create — form.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * POST /admin/users — store new.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'username'   => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'      => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
            'role'       => ['required', 'in:user,admin'],
            'country'    => ['nullable', 'string', 'max:50'],
            'location'   => ['nullable', 'string', 'max:100'],
            'risk_level' => ['required', 'in:low,medium,high'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * GET /admin/users/{user}/edit — JSON for modal.
     */
    public function edit(User $user)
    {
        return response()->json([
            'id'         => $user->id,
            'name'       => $user->name,
            'username'   => $user->username,
            'email'      => $user->email,
            'role'       => $user->role,
            'country'    => $user->country,
            'location'   => $user->location,
            'risk_level' => $user->risk_level,
            'created_at' => $user->created_at->toIso8601String(),
        ]);
    }

    /**
     * GET /admin/users/{user}/details — JSON for view modal.
     */
    public function details(User $user)
    {
        $latestScreening = $user->screenings()->latest()->first();
        $checkinCount    = $user->checkins()->count();
        $recentCheckins  = $user->checkins()->latest()->take(5)->get(['mood', 'comment', 'created_at']);

        return response()->json([
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'username'   => $user->username,
                'email'      => $user->email,
                'role'       => $user->role,
                'country'    => $user->country,
                'location'   => $user->location,
                'risk_level' => $user->risk_level,
                'profile_image_url' => $user->profile_image_url,
                'created_at' => $user->created_at->format('d M Y, H:i'),
            ],
            'stats' => [
                'checkins'          => $checkinCount,
                'latest_screening'  => $latestScreening ? [
                    'risk'       => $latestScreening->risk_level,
                    'concern'    => $latestScreening->main_concern,
                    'created_at' => $latestScreening->created_at->format('d M Y'),
                ] : null,
            ],
            'recent_checkins' => $recentCheckins->map(fn ($c) => [
                'mood'       => $c->mood,
                'comment'    => \Illuminate\Support\Str::limit($c->comment, 80),
                'created_at' => $c->created_at->diffForHumans(),
            ]),
        ]);
    }

    /**
     * PUT /admin/users/{user} — update (AJAX).
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'username'   => ['required', 'string', 'max:50', 'unique:users,username,' . $user->id],
            'email'      => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'role'       => ['required', 'in:user,admin'],
            'country'    => ['nullable', 'string', 'max:50'],
            'location'   => ['nullable', 'string', 'max:100'],
            'risk_level' => ['required', 'in:low,medium,high'],
            'password'   => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'User updated successfully.',
        ]);
    }

    /**
     * DELETE /admin/users/{user} — requires admin password.
     */
    public function destroy(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You cannot delete your own account.',
            ], 422);
        }

        // Verify admin password
        if (! Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Incorrect admin password.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'User deleted.',
        ]);
    }

    /**
     * GET /admin/users/export — CSV download.
     */
    public function export()
    {
        $filename = 'users_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID', 'Name', 'Username', 'Email', 'Role',
                'Country', 'Location', 'Risk Level', 'Registered At',
            ]);

            User::query()->orderBy('id')->chunk(500, function ($users) use ($file) {
                foreach ($users as $u) {
                    fputcsv($file, [
                        $u->id,
                        $u->name,
                        $u->username,
                        $u->email,
                        $u->role,
                        $u->country,
                        $u->location,
                        $u->risk_level,
                        $u->created_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
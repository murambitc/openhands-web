<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\GuestController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PanicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (no auth required)
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'))->name('home');

// Guest login (creates a temporary user account)
Route::get('/guest-login', [GuestController::class, 'login'])->name('guest.login');


Route::get('/locate', [MapController::class, 'index'])->name('locate');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/checkin', [DashboardController::class, 'storeCheckin'])->name('checkin.store');

    // Mood logs
    Route::get('/checkins', [CheckinController::class, 'index'])->name('checkins.index');
    Route::get('/checkins/chart', [CheckinController::class, 'chart'])->name('checkins.chart');
    Route::delete('/checkins/{checkin}', [CheckinController::class, 'destroy'])->name('checkins.destroy');

    // Mood tracker
    Route::get('/tracker', [CheckinController::class, 'tracker'])->name('tracker');

    // Screening
    Route::get('/screening', [ScreeningController::class, 'index'])->name('screening');
    Route::post('/screening', [ScreeningController::class, 'store'])->name('screening.store');

    // Organizations (read-only)
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations');

    // Sessions (read-only)
    Route::get('/sessions', [SessionController::class, 'index'])->name('sessions');

    // Wellness tools
    Route::get('/wellness-tools', fn () => view('wellness-tools'))->name('wellness.tools');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'send'])->name('chat.send');

    // Profile
    Route::post('/profile/image', [ProfileController::class, 'updateImage'])->name('profile.image');
    Route::post('/profile/name', [ProfileController::class, 'updateName'])->name('profile.name');

    // Password change
    Route::post('/password', [App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');

    // Panic
    Route::post('/panic', [PanicController::class, 'trigger'])->name('panic');

    // Map
   

    // Logout
    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (auth + admin role)
|--------------------------------------------------------------------------
|
| Group is prefixed with /admin and named admin.*
| Every admin route uses this single group — no nesting.
|
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Users — CRUD
        // ⚠️ IMPORTANT: the `export` and `details` routes come BEFORE
        // Route::resource, otherwise {user} will match "export" / "details".
        Route::get('/users/export', [Admin\UserController::class, 'export'])->name('users.export');
        Route::get('/users/{user}/details', [Admin\UserController::class, 'details'])->name('users.details');

        Route::resource('users', Admin\UserController::class);

        // Organizations — CRUD
        Route::get('/organizations/{organization}/referrals', [Admin\OrganizationController::class, 'referrals'])
            ->name('organizations.referrals');
        Route::resource('organizations', Admin\OrganizationController::class);

        // Wellness sessions — CRUD
        Route::resource('sessions', Admin\SessionController::class);

        // Settings
        Route::post('/settings/footer', [Admin\SettingController::class, 'updateFooter'])->name('settings.footer');
        Route::post('/settings/map', [Admin\SettingController::class, 'updateMap'])->name('settings.map');
        Route::get('/settings/live-data', [Admin\SettingController::class, 'liveData'])->name('settings.live');
    });
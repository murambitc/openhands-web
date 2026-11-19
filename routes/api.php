<?php

use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\OrganizationController as AdminOrganizationController;
use App\Http\Controllers\Api\Admin\SessionController as AdminSessionController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CheckinController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\PanicController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ScreeningController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /*
    |----------------------------------------------------------------------
    | PUBLIC — Auth
    |----------------------------------------------------------------------
    */
    Route::middleware('throttle:auth')->group(function () {
        Route::post('/auth/login',            [AuthController::class, 'login']);
        Route::post('/auth/register',         [AuthController::class, 'register']);
        Route::post('/auth/guest',            [AuthController::class, 'guest']);
        Route::post('/auth/forgot-password',  [AuthController::class, 'forgotPassword']);
        Route::post('/auth/reset-password',   [AuthController::class, 'resetPassword']);
    });

    // Public read-only
    Route::get('/settings',           [SettingController::class, 'index']);
    Route::get('/organizations',      [OrganizationController::class, 'index']);
    Route::get('/organizations/{id}', [OrganizationController::class, 'show']);
    Route::get('/sessions',           [SessionController::class, 'index']);
    Route::get('/sessions/{id}',      [SessionController::class, 'show']);

    /*
    |----------------------------------------------------------------------
    | AUTHENTICATED — User
    |----------------------------------------------------------------------
    */
    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

        // Auth
        Route::get('/auth/me',      [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/refresh',[AuthController::class, 'refresh']);

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Check-ins
        Route::post('/checkins',           [CheckinController::class, 'store']);
        Route::get('/checkins',            [CheckinController::class, 'index']);
        Route::delete('/checkins/{checkin}',[CheckinController::class, 'destroy']);
        Route::get('/checkins/chart',      [CheckinController::class, 'chart']);

        // Screenings
        Route::post('/screenings',      [ScreeningController::class, 'store']);
        Route::get('/screenings',       [ScreeningController::class, 'index']);
        Route::get('/screenings/{id}',  [ScreeningController::class, 'show']);

        // Chat
        Route::get('/chat',     [ChatController::class, 'index']);
        Route::post('/chat',    [ChatController::class, 'send']);
        Route::delete('/chat',  [ChatController::class, 'clear']);

        // Panic
        Route::post('/panic',           [PanicController::class, 'trigger']);
        Route::get('/panic/history',    [PanicController::class, 'history']);

        // Profile
        Route::post('/profile/image',    [ProfileController::class, 'updateImage']);
        Route::put('/profile/name',      [ProfileController::class, 'updateName']);
        Route::post('/profile/password', [ProfileController::class, 'updatePassword']);

        /*
        |------------------------------------------------------------------
        | AUTHENTICATED — Admin
        |------------------------------------------------------------------
        */
        Route::middleware('admin')->prefix('admin')->group(function () {

            // Dashboard
            Route::get('/dashboard', [AdminDashboardController::class, 'index']);

            // Users
            Route::get('/users',              [AdminUserController::class, 'index']);
            Route::post('/users',             [AdminUserController::class, 'store']);
            Route::get('/users/{id}',         [AdminUserController::class, 'show']);
            Route::put('/users/{id}',         [AdminUserController::class, 'update']);
            Route::delete('/users/{id}',      [AdminUserController::class, 'destroy']);
            Route::get('/users/{id}/referrals',[AdminUserController::class, 'referrals']);

            // Organizations
            Route::get('/organizations',           [AdminOrganizationController::class, 'index']);
            Route::post('/organizations',          [AdminOrganizationController::class, 'store']);
            Route::get('/organizations/{id}',      [AdminOrganizationController::class, 'show']);
            Route::put('/organizations/{id}',      [AdminOrganizationController::class, 'update']);
            Route::delete('/organizations/{id}',   [AdminOrganizationController::class, 'destroy']);
            Route::get('/organizations/{id}/referrals', [AdminOrganizationController::class, 'referrals']);

            // Sessions
            Route::get('/sessions',           [AdminSessionController::class, 'index']);
            Route::post('/sessions',          [AdminSessionController::class, 'store']);
            Route::get('/sessions/{id}',      [AdminSessionController::class, 'show']);
            Route::put('/sessions/{id}',      [AdminSessionController::class, 'update']);
            Route::delete('/sessions/{id}',   [AdminSessionController::class, 'destroy']);
        });
    });
});

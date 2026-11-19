<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2F5DAA">

    <title>Sign In — Open Hands</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('img/logo/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2F5DAA;
            --primary-dark: #1e3a8a;
            --accent: #be185d;
            --accent-light: #ff4d8d;
            --accent-dark: #c2185b;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.1);
            --glass: rgba(255, 255, 255, 0.06);
        }

        * { box-sizing: border-box; }

        html, body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        body {
            background: linear-gradient(135deg, #2F5DAA, #1e3a8a);
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
        }

        /* Ambient floating orbs */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }
        body::before {
            width: 380px;
            height: 380px;
            background: rgba(255, 77, 141, 0.28);
            top: -140px;
            right: -120px;
        }
        body::after {
            width: 320px;
            height: 320px;
            background: rgba(47, 93, 170, 0.4);
            bottom: -120px;
            left: -100px;
        }

        .oh-auth-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 2;
        }

        /* ================================
           AUTH CARD
        ================================ */
        .oh-auth-card {
            background: linear-gradient(-165deg, #be185d, #D85C9A);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 22px;
            padding: 38px 32px 32px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
            animation: cardIn 0.6s ease;
        }

        .oh-auth-card::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ================================
           LOGO SECTION
        ================================ */
        .oh-logo-wrap {
            text-align: center;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .oh-logo-img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #2F5DAA;
            box-shadow: 0 0 25px rgba(190, 24, 93, 0.55),
                        0 8px 20px rgba(0, 0, 0, 0.3);
            background: #fff;
            transition: transform 0.4s ease;
            display: inline-block;
        }

        .oh-logo-img:hover {
            transform: scale(1.05) rotate(-2deg);
        }

        .oh-logo-text {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1.5px;
            margin-top: 12px;
        }

        .oh-logo-text span {
            color: #ffffff;
            opacity: 0.85;
            font-weight: 600;
        }

        .oh-subtitle {
            text-align: center;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 24px;
            line-height: 1.5;
        }

        /* ================================
           FORM CONTROLS
        ================================ */
        .oh-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 6px;
            text-align: left;
        }

        .oh-input-wrap {
            position: relative;
            margin-bottom: 16px;
        }

        .oh-input-wrap i.oh-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 15px;
            pointer-events: none;
        }

        .oh-input {
            width: 100%;
            background: #152036;
            border: 1px solid #253a5c;
            color: #fff !important;
            border-radius: 10px;
            padding: 12px 14px 12px 40px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .oh-input:focus {
            outline: none;
            border-color: var(--accent-light);
            background: #152036;
            box-shadow: 0 0 0 3px rgba(255, 77, 141, 0.2);
        }

        .oh-input::placeholder {
            color: #94a3b8 !important;
        }

        /* Password toggle */
        .oh-pw-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            font-size: 15px;
        }

        .oh-pw-toggle:hover { color: #fff; }

        /* ================================
           BUTTONS
        ================================ */
        .oh-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            border: none;
            text-decoration: none;
        }

        .oh-btn-primary {
            background: #2F5DAA;
            color: #fff;
            box-shadow: 0 4px 15px rgba(47, 93, 170, 0.3);
        }

        .oh-btn-primary:hover {
            background: #D85C9A;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(216, 92, 154, 0.4);
        }

        .oh-btn-primary:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        .oh-btn-outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: #fff;
        }

        .oh-btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-color: #fff;
        }

        .oh-btn-ghost {
            background: transparent;
            color: rgba(255, 255, 255, 0.85);
            font-size: 12px;
            font-weight: 400;
            padding: 8px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            border: none;
            cursor: pointer;
        }

        .oh-btn-ghost:hover {
            color: #fff;
            text-decoration: underline;
        }

        /* ================================
           DIVIDER
        ================================ */
        .oh-divider {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            margin: 22px 0;
        }

        .oh-divider::before,
        .oh-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.25);
        }

        .oh-divider span { padding: 0 14px; }

        /* ================================
           QUICK ACTION GRID
        ================================ */
        .oh-quick-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 8px;
        }

        .oh-quick-btn {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
            border-radius: 10px;
            padding: 10px 6px;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            text-align: center;
            line-height: 1.3;
        }

        .oh-quick-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            color: #fff;
        }

        .oh-quick-btn i {
            font-size: 18px;
        }

        /* ================================
           ERROR ALERT
        ================================ */
        .oh-alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fecaca;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .oh-alert-info {
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.4);
            color: #bfdbfe;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .oh-alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #bbf7d0;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        /* ================================
           FOOTER
        ================================ */
        .oh-footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            margin-top: 20px;
        }

        .oh-footer a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
        }

        .oh-footer a:hover { text-decoration: underline; }

        /* ================================
           MODALS
        ================================ */
        .modal-content {
            background: rgba(27, 42, 71, 0.98) !important;
            backdrop-filter: blur(16px);
            color: #fff;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .modal-header { border-color: rgba(255, 255, 255, 0.1); }
        .modal-title { color: #fff; font-weight: 600; }
        .btn-close { filter: invert(1); opacity: 0.8; }

        .modal .form-control {
            background: #0f172a;
            color: #fff;
            border: 1px solid #253a5c;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 14px;
        }

        .modal .form-control:focus {
            background: #0f172a;
            color: #fff;
            border-color: var(--accent-light);
            box-shadow: 0 0 0 3px rgba(255, 77, 141, 0.18);
        }

        .modal .form-control::placeholder {
            color: #94a3b8;
        }

        /* FAQ accordion */
        .modal .accordion-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px !important;
            margin-bottom: 8px;
            overflow: hidden;
        }

        .modal .accordion-button {
            background: transparent;
            color: #fff;
            font-weight: 500;
            font-size: 14px;
            padding: 14px 16px;
            border: none;
            box-shadow: none;
        }

        .modal .accordion-button:not(.collapsed) {
            background: rgba(190, 24, 93, 0.15);
            color: #fff;
        }

        .modal .accordion-button::after {
            filter: invert(1);
        }

        .modal .accordion-body {
            color: #cbd5f5;
            font-size: 13px;
            padding: 12px 16px;
        }

        /* Crisis modal glow */
        .crisis-modal .modal-content {
            border: 1px solid rgba(220, 38, 38, 0.4);
            box-shadow: 0 0 40px rgba(220, 38, 38, 0.3);
        }

        /* Guest modal info box */
        .guest-info-box {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 16px;
        }

        .guest-info-box p {
            margin: 0 0 6px;
            font-size: 14px;
            color: #e2e8f0;
        }

        .guest-info-box p:last-child { margin-bottom: 0; }

        .guest-info-box strong { color: #fff; }
        .guest-info-box span {
            color: var(--accent-light);
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }

        /* ================================
           FLOATING MENU
        ================================ */
        .oh-floating-menu {
            position: fixed;
            left: 20px;
            bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 900;
        }

        .oh-float-btn {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            color: #fff;
            font-size: 22px;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.35);
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .oh-float-btn:hover { transform: scale(1.08); color: #fff; }

        .oh-float-toggle { background: linear-gradient(135deg, #831843, #be185d); }
        .oh-float-faq    { background: linear-gradient(135deg, #7b2cbf, #9d4edd); }
        .oh-float-ios    { background: linear-gradient(135deg, #111827, #374151); }
        .oh-float-apk    { background: linear-gradient(135deg, #16a34a, #22c55e); }

        .oh-floating-menu.collapsed .oh-float-faq,
        .oh-floating-menu.collapsed .oh-float-ios,
        .oh-floating-menu.collapsed .oh-float-apk {
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px) scale(0.8);
            pointer-events: none;
        }

        /* Spinner inside buttons */
        .btn-spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Mobile tweaks */
        @media (max-width: 480px) {
            .oh-auth-card { padding: 28px 22px; }
            .oh-logo-img { width: 90px; height: 90px; }
            .oh-logo-text { font-size: 17px; }
            .oh-floating-menu { left: 12px; bottom: 12px; gap: 8px; }
            .oh-float-btn { width: 48px; height: 48px; font-size: 18px; }
        }
    </style>
</head>

<body>

<div class="oh-auth-wrapper">
    <div class="oh-auth-card">

        {{-- LOGO --}}
        <div class="oh-logo-wrap">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Open Hands Logo"
                 class="oh-logo-img"
                 onerror="this.style.display='none'">
            <div class="oh-logo-text">OPEN<span>&nbsp;HANDS</span></div>
        </div>

        <p class="oh-subtitle">
            Your safe space for support, connection, and healing.
        </p>

        {{-- SERVER-SIDE VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="oh-alert-error">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- SESSION STATUS (e.g., password reset success) --}}
        @if (session('status'))
            <div class="oh-alert-success">
                <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
            </div>
        @endif

        {{-- LOGIN FORM --}}
        <form method="POST" action="{{ route('login') }}" id="loginForm" autocomplete="on">
            @csrf

            <label class="oh-label" for="username">Username</label>
            <div class="oh-input-wrap">
                <i class="bi bi-person-fill oh-input-icon"></i>
                <input type="text"
                       id="username"
                       name="username"
                       class="oh-input"
                       placeholder="Enter your username"
                       value="{{ old('username') }}"
                       autocomplete="username"
                       required autofocus>
            </div>

            <label class="oh-label" for="password">Password</label>
            <div class="oh-input-wrap">
                <i class="bi bi-lock-fill oh-input-icon"></i>
                <input type="password"
                       id="password"
                       name="password"
                       class="oh-input"
                       placeholder="Enter your password"
                       autocomplete="current-password"
                       required>
                <button type="button" class="oh-pw-toggle" onclick="togglePw()" aria-label="Show password">
                    <i class="bi bi-eye" id="pwIcon"></i>
                </button>
            </div>

            <div style="text-align: right; margin-bottom: 16px;">
                <button type="button"
                        class="oh-btn-ghost"
                        onclick="openRecoveryModal()">
                    Forgot password?
                </button>
            </div>

            <button type="submit" class="oh-btn oh-btn-primary mb-2" id="loginBtn">
                <i class="bi bi-box-arrow-in-right"></i> Sign In
            </button>

            <button type="button" class="oh-btn oh-btn-outline" id="guestBtn" onclick="loginAsGuest()">
                <i class="bi bi-person-circle"></i> Continue as Guest
            </button>

            <div class="oh-divider"><span>or</span></div>

            <a href="{{ route('register') }}" class="oh-btn oh-btn-outline">
                <i class="bi bi-person-plus"></i> Create an Account
            </a>
        </form>

        {{-- QUICK ACTION GRID --}}
        <div class="oh-quick-grid mt-3">
            <button type="button" class="oh-quick-btn"
                    data-bs-toggle="modal" data-bs-target="#directoryModal">
                <i class="bi bi-folder2-open"></i>
                <span>Directory</span>
            </button>
            <button type="button" class="oh-quick-btn"
                    data-bs-toggle="modal" data-bs-target="#toolsModal">
                <i class="bi bi-heart-pulse"></i>
                <span>Tools</span>
            </button>
            <button type="button" class="oh-quick-btn"
                    data-bs-toggle="modal" data-bs-target="#crisisModal">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Crisis</span>
            </button>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="oh-footer mt-4" style="line-height: 1.5;">
    Open Hands App is a service provided by Friends of Diversity. <br>
    Copyright &copy; 2026 Friends of Diversity. All rights reserved.
</div>
</div>

{{-- ============================================================
     MODALS
============================================================ --}}

{{-- RECOVERY MODAL --}}
<div class="modal fade" id="recoveryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title"><i class="bi bi-key-fill"></i> Password Recovery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="color: #94a3b8; font-size: 13px; margin-bottom: 14px;">
                    Enter the email you registered with. We'll send you a password reset link.
                </p>
                <input type="email"
                       id="recoveryEmail"
                       class="form-control mb-3"
                       placeholder="your@email.com"
                       autocomplete="email">
                <button class="btn oh-btn-primary w-100" onclick="recoverPassword()" id="recoveryBtn">
                    <i class="bi bi-send"></i> Send Recovery Email
                </button>
                <div id="recoveryMsg" class="mt-3" style="font-size: 13px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- GUEST MODAL --}}
<div class="modal fade" id="guestModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 text-center">
            <div class="modal-header border-0 pb-2 justify-content-center">
                <h5 class="modal-title"><i class="bi bi-person-circle"></i> Temporary Guest Access</h5>
            </div>
            <div class="modal-body">
                <div class="guest-info-box text-start">
                    <p><strong>Username:</strong> <span id="guestUsername">—</span></p>
                    <p><strong>Password:</strong> <span id="guestPassword">—</span></p>
                </div>
                <p style="font-size: 12px; color: #94a3b8; margin-bottom: 16px;">
                    Save these credentials if you want to log in again later.
                </p>
                <button class="btn oh-btn-primary w-100" onclick="proceedGuest()">
                    Continue to Dashboard <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- DIRECTORY MODAL --}}
<div class="modal fade" id="directoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title"><i class="bi bi-folder2-open"></i> Support Directory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                <p style="color: #94a3b8; font-size: 12px; margin-bottom: 12px;">
                    Verified organizations ready to help.
                </p>

                @isset($organizations)
                    @if ($organizations->count() > 0)
                        @foreach ($organizations as $org)
                            <div class="d-flex gap-2 mb-2">
                                <button type="button"
                                        class="btn w-100 text-start"
                                        style="background: #0f172a; color: #fff; border-radius: 10px; padding: 12px;"
                                        onclick='openOrgModal(@json($org->name ?? ""), @json($org->description ?? ""), @json($org->phone ?? ""), @json($org->whatsapp ?? ""), @json($org->email ?? ""), @json($org->website ?? ""))'>
                                    <i class="bi bi-building me-2"></i>
                                    {{ $org->name ?? 'Unnamed Organization' }}
                                </button>
                                @if (!empty($org->map_link))
                                    <a href="{{ $org->map_link }}" target="_blank" rel="noopener"
                                       class="btn"
                                       style="background: #1e293b; color: #fff; min-width: 48px;">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-warning" style="font-size: 13px;">
                            No organizations available.
                        </p>
                    @endif
                @else
                    <p class="text-center text-muted" style="font-size: 13px;">
                        Loading directory... (sign in to view).
                    </p>
                @endisset
            </div>
        </div>
    </div>
</div>

{{-- ORGANIZATION DETAILS MODAL --}}
<div class="modal fade" id="orgModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 text-center">
            <h5 id="orgName" class="mt-2" style="color: #fff;"></h5>
            <p id="orgInfo" class="text-start" style="color: #94a3b8; font-size: 13px;"></p>

            <hr style="border-color: rgba(255,255,255,0.1);">

            <div class="d-grid gap-2 mt-3">
                <div class="p-2 rounded" style="background: rgba(255,255,255,0.05); color: #fff;">
                    <i class="bi bi-telephone-fill"></i> <span id="orgPhone">—</span>
                </div>
                <a id="orgWhatsapp" target="_blank" rel="noopener"
                   class="btn btn-success" style="display: none;">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </a>
                <div class="p-2 rounded" style="background: rgba(255,255,255,0.05); color: #fff;">
                    <i class="bi bi-envelope-fill"></i> <span id="orgEmail">—</span>
                </div>
                <a id="orgWebsite" target="_blank" rel="noopener"
                   class="btn btn-info" style="display: none;">
                    <i class="bi bi-globe"></i> Visit Website
                </a>
            </div>

            <button type="button" class="btn oh-btn-primary mt-3" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>

{{-- TOOLS MODAL --}}
<div class="modal fade" id="toolsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="bi bi-heart-pulse"></i> Wellness Tools</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">

                    {{-- Breathing --}}
                    <div class="col-md-6">
                        <div class="p-3 h-100 text-center rounded" style="background: rgba(255,255,255,0.05);">
                            <h6 style="color:#fff;">🧘 Breathing Exercise</h6>
                            <div id="breathCircle"
                                 style="width:100px;height:100px;border-radius:50%;
                                        margin:20px auto;background:#7b2cbf;
                                        transition: transform 4s ease;"></div>
                            <p id="breathText" style="color:#fff;">Ready?</p>
                            <button type="button" class="btn oh-btn-primary w-100" onclick="startBreathing()">
                                Start
                            </button>
                        </div>
                    </div>

                    {{-- Articles --}}
                    <div class="col-md-6">
                        <div class="p-3 h-100 rounded" style="background: rgba(255,255,255,0.05);">
                            <h6 style="color:#fff;">📖 Wellness Articles</h6>
                            <div class="mt-2" style="max-height:220px;overflow-y:auto;">
                                <div class="mb-2 p-2 rounded" style="background: rgba(255,255,255,0.05);">
                                    <strong style="color:#fff;">💙 Coping with Stress</strong>
                                    <p style="font-size:12px;color:#94a3b8;margin:4px 0;">
                                        Simple breathing and grounding techniques.
                                    </p>
                                </div>
                                <div class="mb-2 p-2 rounded" style="background: rgba(255,255,255,0.05);">
                                    <strong style="color:#fff;">🌱 Healthy Habits</strong>
                                    <p style="font-size:12px;color:#94a3b8;margin:4px 0;">
                                        Small daily habits, big mental health wins.
                                    </p>
                                </div>
                                <div class="mb-2 p-2 rounded" style="background: rgba(255,255,255,0.05);">
                                    <strong style="color:#fff;">🧘 Mindfulness Basics</strong>
                                    <p style="font-size:12px;color:#94a3b8;margin:4px 0;">
                                        Reduce anxiety and improve focus.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Journal --}}
                    <div class="col-md-6">
                        <div class="p-3 h-100 rounded" style="background: rgba(255,255,255,0.05);">
                            <h6 style="color:#fff;">📝 Journal</h6>
                            <select id="journalPrompt" class="form-control mb-2">
                                <option>What made you feel stressed today?</option>
                                <option>What are you grateful for today?</option>
                                <option>What made you happy today?</option>
                            </select>
                            <textarea id="journalText" class="form-control mb-2"
                                      placeholder="Write your thoughts..."
                                      style="min-height:70px;"></textarea>
                            <button type="button" class="btn oh-btn-primary w-100" onclick="saveJournal()">
                                Save Entry
                            </button>
                            <div id="journalList" class="mt-2"
                                 style="max-height:100px;overflow-y:auto;font-size:12px;color:#cbd5f5;"></div>
                        </div>
                    </div>

                    {{-- Habits --}}
                    <div class="col-md-6">
                        <div class="p-3 h-100 rounded" style="background: rgba(255,255,255,0.05);">
                            <h6 style="color:#fff;">🎯 Habit Tracker</h6>
                            <input type="text" id="habitInput" class="form-control mb-2"
                                   placeholder="Add habit (e.g. Drink water)">
                            <button type="button" class="btn oh-btn-primary w-100 mb-2" onclick="addHabit()">
                                Add Habit
                            </button>
                            <div id="habitList" style="max-height:100px;overflow-y:auto;"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- CRISIS MODAL --}}
<div class="modal fade crisis-modal" id="crisisModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 text-center">
            <h4 class="text-danger mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i> Emergency Help
            </h4>

            <p style="color: #94a3b8; font-size: 13px;">
                If you are in immediate danger, reach out now. You are not alone.
            </p>

            <a href="https://wa.me/26777649963?text={{ urlencode('Emergency, I need help now') }}"
               target="_blank" rel="noopener"
               class="btn btn-success w-100 mb-2">
                <i class="bi bi-whatsapp"></i> Emergency WhatsApp
            </a>
            <a href="tel:999" class="btn btn-danger w-100 mb-2">
                <i class="bi bi-telephone-fill"></i> Call Police (999)
            </a>
            <a href="tel:997" class="btn btn-info w-100 mb-2">
                <i class="bi bi-hospital"></i> Call Ambulance (997)
            </a>

            <button type="button" class="btn oh-btn-outline w-100 mt-2" data-bs-dismiss="modal">
                Close
            </button>
        </div>
    </div>
</div>

{{-- FAQ MODAL --}}
<div class="modal fade" id="faqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="bi bi-question-circle"></i> Frequently Asked Questions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How do I get help?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Use the Directory to find organizations, or the Crisis button for urgent help.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Is my information private?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes — your data is encrypted and never shared without your consent.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq3">
                                How does WhatsApp support work?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Clicking WhatsApp opens a direct chat with a trusted support provider.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FLOATING MENU --}}
<div class="oh-floating-menu" id="floatingMenu">
    <button type="button" class="oh-float-btn oh-float-toggle"
            onclick="toggleFloatingMenu()" aria-label="Menu">
        <i class="bi bi-list"></i>
    </button>
    <button type="button" class="oh-float-btn oh-float-faq"
            data-bs-toggle="modal" data-bs-target="#faqModal" aria-label="FAQ">
        <i class="bi bi-question-lg"></i>
    </button>
    <a href="#" class="oh-float-btn oh-float-ios" title="Download iOS App" aria-label="iOS">
        <i class="bi bi-apple"></i>
    </a>
    <a href="{{ asset('downloads/OpenHands.apk') }}"
       class="oh-float-btn oh-float-apk"
       download title="Download Android APK" aria-label="Android">
        <i class="bi bi-android2"></i>
    </a>
</div>

{{-- ============================================================
     SCRIPTS
============================================================ --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
    'use strict';

    /* =============================
       HELPERS
    ============================= */
    const csrfToken = () =>
        document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    const guestLoginUrl  = '{{ route("guest.login") }}';
    const recoveryUrl    = '{{ route("password.email") }}';
    const dashboardUrl   = '{{ url("/dashboard") }}';

    /* =============================
       PASSWORD VISIBILITY TOGGLE
    ============================= */
    function togglePw() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('pwIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    /* =============================
       LOGIN SUBMIT (loading state)
    ============================= */
    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn = document.getElementById('loginBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="btn-spinner"></span> Signing in...';
    });

    /* =============================
       GUEST LOGIN
    ============================= */
    async function loginAsGuest() {
        const btn = document.getElementById('guestBtn');
        const originalHtml = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<span class="btn-spinner"></span> Starting guest session...';

        try {
            const res = await fetch(guestLoginUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin', // ⚠️ IMPORTANT: sends session cookie
            });

            const rawText = await res.text();

            if (!res.ok) {
                console.error('Guest login HTTP error:', res.status, rawText);
                throw new Error(`Server returned HTTP ${res.status}. Check logs.`);
            }

            let data;
            try {
                data = JSON.parse(rawText);
            } catch (parseErr) {
                console.error('Invalid JSON:', rawText);
                throw new Error('Server returned invalid JSON. Check logs.');
            }

            if (data.status !== 'success') {
                throw new Error(data.message || 'Unexpected response');
            }

            // Populate modal
            document.getElementById('guestUsername').textContent = data.username || 'Guest';
            document.getElementById('guestPassword').textContent = data.password || '—';

            // Store the redirect URL for the Continue button
            window.__guestRedirectUrl = data.redirect || dashboardUrl;

            // Show modal
            new bootstrap.Modal(document.getElementById('guestModal')).show();

        } catch (err) {
            console.error('Guest login error:', err);
            alert(
                'Could not start guest session.\n\n' +
                err.message + '\n\n' +
                'Open the browser console (F12) for more details.'
            );
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }

    function proceedGuest() {
        // Session is already active (server set the cookie).
        // Just navigate to the dashboard.
        window.location.href = window.__guestRedirectUrl || dashboardUrl;
    }

    /* =============================
       RECOVERY MODAL
    ============================= */
    function openRecoveryModal() {
        document.getElementById('recoveryMsg').innerHTML = '';
        document.getElementById('recoveryEmail').value = '';
        new bootstrap.Modal(document.getElementById('recoveryModal')).show();
    }

    async function recoverPassword() {
        const email = document.getElementById('recoveryEmail').value.trim();
        const msg   = document.getElementById('recoveryMsg');
        const btn   = document.getElementById('recoveryBtn');

        if (!email) {
            msg.innerHTML = '<div class="oh-alert-error">Please enter your email.</div>';
            return;
        }

        msg.innerHTML = '<div class="oh-alert-info">Sending...</div>';
        btn.disabled = true;

        try {
            const res = await fetch(recoveryUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ email }),
            });

            const data = await res.json().catch(() => ({}));

            if (res.ok) {
                msg.innerHTML = `
                    <div class="oh-alert-success">
                        <i class="bi bi-check-circle-fill"></i>
                        ${data.message || data.status || 'If that email exists, a reset link has been sent.'}
                    </div>`;
            } else if (res.status === 422 && data.errors?.email) {
                msg.innerHTML = `<div class="oh-alert-error">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    ${data.errors.email[0]}
                </div>`;
            } else {
                msg.innerHTML = `<div class="oh-alert-error">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    ${data.message || 'Something went wrong. Try again.'}
                </div>`;
            }
        } catch (err) {
            console.error('Recovery error:', err);
            msg.innerHTML = '<div class="oh-alert-error">Connection error. Please try again.</div>';
        } finally {
            btn.disabled = false;
        }
    }

    /* =============================
       ORGANIZATION MODAL
    ============================= */
    function openOrgModal(name, info, phone, whatsapp, email, website) {
        document.getElementById('orgName').textContent = name || 'Organization';
        document.getElementById('orgInfo').textContent = info || 'No description available';
        document.getElementById('orgPhone').textContent = phone || 'Not available';
        document.getElementById('orgEmail').textContent = email || 'Not available';

        const wa = document.getElementById('orgWhatsapp');
        if (whatsapp) {
            const clean = whatsapp.toString().replace(/\D/g, '');
            const msg = encodeURIComponent('Hello, I need help from ' + name);
            wa.href = `https://wa.me/${clean}?text=${msg}`;
            wa.style.display = 'flex';
        } else {
            wa.style.display = 'none';
        }

        const web = document.getElementById('orgWebsite');
        if (website) {
            web.href = website;
            web.style.display = 'flex';
        } else {
            web.style.display = 'none';
        }

        const dirModal = bootstrap.Modal.getInstance(document.getElementById('directoryModal'));
        if (dirModal) dirModal.hide();

        setTimeout(() => {
            new bootstrap.Modal(document.getElementById('orgModal')).show();
        }, 250);
    }

    /* =============================
       WELLNESS TOOLS (inline)
    ============================= */
    function startBreathing() {
        const circle = document.getElementById('breathCircle');
        const text = document.getElementById('breathText');
        if (!circle) return;

        text.textContent = 'Inhale...';
        circle.style.transform = 'scale(1.5)';

        setTimeout(() => { text.textContent = 'Hold...'; }, 4000);
        setTimeout(() => {
            text.textContent = 'Exhale...';
            circle.style.transform = 'scale(1)';
        }, 8000);
    }

    function saveJournal() {
        const text = document.getElementById('journalText').value.trim();
        const prompt = document.getElementById('journalPrompt').value;
        if (!text) return;

        const list = document.getElementById('journalList');
        const entry = document.createElement('div');
        entry.className = 'p-2 mb-2 rounded';
        entry.style.background = 'rgba(255,255,255,0.05)';
        entry.innerHTML =
            `<strong style="color:#fff;">${prompt}</strong><br>` +
            `<span style="color:#cbd5f5;">${text}</span>`;
        list.prepend(entry);

        document.getElementById('journalText').value = '';
    }

    function addHabit() {
        const input = document.getElementById('habitInput');
        const list = document.getElementById('habitList');
        if (!input.value.trim()) return;

        const item = document.createElement('div');
        item.className = 'p-2 mb-2 rounded d-flex align-items-center';
        item.style.background = 'rgba(255,255,255,0.05)';
        item.innerHTML =
            `<input type="checkbox" class="me-2">` +
            `<span style="color:#fff;">${input.value}</span>`;
        list.prepend(item);
        input.value = '';
    }

    /* =============================
       FLOATING MENU
    ============================= */
    function toggleFloatingMenu() {
        document.getElementById('floatingMenu').classList.toggle('collapsed');
    }
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b1220">

    <title>Open Hands — @yield('title', 'Home')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg: #0b1220;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-strong: rgba(255, 255, 255, 0.09);
            --border: rgba(255, 255, 255, 0.1);
            --primary: #2F5DAA;
            --accent: #be185d;
            --accent-light: #ff4d8d;
            --accent-dark: #c2185b;
            --text: #e2e8f0;
            --muted: #94a3b8;

            /* ── Layout dimensions ── */
            --sidebar-width: 260px;
            --sidebar-collapsed: 72px;
            --header-height: 60px;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body.app-body {
            background: linear-gradient(135deg, #2F5DAA, #1e3a8a);
            color: var(--text);
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* =====================================================
           SIDEBAR
        ===================================================== */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #ff4d8d, #c2185b);
            border-right: 1px solid rgba(255, 255, 255, 0.15);
            z-index: 1000;
            transition: width 0.28s ease, transform 0.28s ease;
            overflow-x: hidden;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        #sidebar::-webkit-scrollbar { width: 6px; }
        #sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }

        body.sidebar-collapsed #sidebar { width: var(--sidebar-collapsed); }

        body.sidebar-collapsed #sidebar .menu-label,
        body.sidebar-collapsed #sidebar .profile-text,
        body.sidebar-collapsed #sidebar .profile-name,
        body.sidebar-collapsed #sidebar .sidebar-heading { display: none; }

        body.sidebar-collapsed #sidebar .menu-item { justify-content: center; padding: 12px; }
        body.sidebar-collapsed #sidebar .profile-img-wrapper { width: 44px; height: 44px; }
        body.sidebar-collapsed #sidebar .profile-img { width: 44px; height: 44px; }

        @media (max-width: 991px) {
            #sidebar { transform: translateX(-100%); width: var(--sidebar-width) !important; }
            #sidebar.active { transform: translateX(0); }
            body.sidebar-collapsed #sidebar .menu-label,
            body.sidebar-collapsed #sidebar .profile-text,
            body.sidebar-collapsed #sidebar .profile-name,
            body.sidebar-collapsed #sidebar .sidebar-heading { display: block; }
            body.sidebar-collapsed #sidebar .menu-item { justify-content: flex-start; padding: 10px 12px; }
        }

        /* =====================================================
           MAIN CONTENT WRAPPER
        ===================================================== */
        .all-content-wrapper {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.28s ease;
            width: calc(100% - var(--sidebar-width));
            display: flex;
            flex-direction: column; /* Ensures footer pushes to bottom */
        }

        body.sidebar-collapsed .all-content-wrapper {
            margin-left: var(--sidebar-collapsed);
            width: calc(100% - var(--sidebar-collapsed));
        }

        @media (max-width: 991px) {
            .all-content-wrapper,
            body.sidebar-collapsed .all-content-wrapper { margin-left: 0; width: 100%; }
        }

        /* =====================================================
           HEADER
        ===================================================== */
        .nk-header-bar {
            position: sticky;
            top: 0;
            z-index: 500;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            background: rgba(11, 18, 32, 0.78);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            min-height: var(--header-height);
        }

        .sidebar-toggle-btn,
        .nk-header-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--glass);
            border-radius: 10px;
            cursor: pointer;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
            color: #fff;
            text-decoration: none;
            flex-shrink: 0;
            font-size: 18px;
            padding: 0;
        }

        .sidebar-toggle-btn:hover,
        .nk-header-icon:hover {
            background: var(--glass-strong);
            transform: scale(1.05);
            color: #fff;
        }

        .icon-gradient {
            background: linear-gradient(180deg, var(--accent-light), var(--accent-dark));
            border: none;
        }
        .icon-gradient:hover { opacity: 0.9; }

        /* =====================================================
           MAIN CONTENT AREA
        ===================================================== */
        .app-main {
            flex: 1; /* Pushes footer down */
            padding: 22px;
            padding-bottom: 110px;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
        }

        @media (max-width: 576px) {
            .app-main { padding: 14px; padding-bottom: 100px; }
        }

        /* =====================================================
           MOBILE BACKDROP
        ===================================================== */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        @media (max-width: 991px) {
            .sidebar-backdrop.active { display: block; opacity: 1; }
        }

        /* =====================================================
           GLASS CARDS
        ===================================================== */
        .card {
            background: var(--glass) !important;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-radius: 18px !important;
            border: 1px solid var(--border) !important;
            color: var(--text);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.28);
        }
        .card h4, .card h5, .card h6 { color: #fff; }

        /* =====================================================
           FORM CONTROLS
        ===================================================== */
        .form-label {
            color: var(--muted);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .form-control, .form-select {
            background: #fff !important;
            color: #000 !important;
            border: 1px solid var(--border) !important;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-light) !important;
            box-shadow: 0 0 0 3px rgba(255, 77, 141, 0.18);
        }

        .form-control::placeholder { color: #64748b !important; opacity: 1; }

        /* =====================================================
           BUTTONS
        ===================================================== */
        .btn { border-radius: 10px; font-weight: 500; transition: all 0.2s ease; }
        .btn:hover { transform: translateY(-1px); }

        .btn-nk-primary {
            background: linear-gradient(180deg, var(--accent-light), var(--accent-dark));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 20px;
        }
        .btn-nk-primary:hover {
            color: #fff;
            opacity: 0.92;
            box-shadow: 0 8px 20px rgba(190, 24, 93, 0.35);
        }
        .btn-nk-primary:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

        /* =====================================================
           ALERTS
        ===================================================== */
        .alert { border-radius: 12px; border: none; padding: 12px 16px; font-size: 14px; }
        .alert-success { background: rgba(34, 197, 94, 0.15); color: #bbf7d0; border: 1px solid rgba(34, 197, 94, 0.35); }
        .alert-danger { background: rgba(239, 68, 68, 0.15); color: #fecaca; border: 1px solid rgba(239, 68, 68, 0.35); }
        .alert .btn-close { filter: invert(1); opacity: 0.7; }

        /* =====================================================
           MODALS
        ===================================================== */
        .modal-content {
            background: rgba(27, 42, 71, 0.98) !important;
            backdrop-filter: blur(18px);
            color: #fff;
            border-radius: 16px;
            border: 1px solid var(--border);
        }
        .modal-header, .modal-footer { border-color: var(--border); }
        .modal-title { color: #fff; font-weight: 600; }
        .btn-close { filter: invert(1); opacity: 0.8; }

        /* =====================================================
           TABLES
        ===================================================== */
        .table-dark {
            --bs-table-bg: transparent;
            --bs-table-color: #e2e8f0;
            --bs-table-border-color: rgba(255, 255, 255, 0.06);
        }
        .table-dark th { color: #fff; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }

        /* =====================================================
           APP GLOBAL FOOTER
        ===================================================== */
        .app-global-footer {
            margin-top: auto; 
            padding: 24px 20px;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(11, 18, 32, 0.3);
            width: 100%;
        }

        .app-global-footer p {
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.6;
            margin: 0;
        }

        /* =====================================================
           UTILITIES
        ===================================================== */
        .text-muted { color: var(--muted) !important; }
        a { color: var(--accent-light); }
        a:hover { color: var(--accent-light); }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.03); }
        ::-webkit-scrollbar-thumb { background: rgba(255, 77, 141, 0.35); border-radius: 10px; }
    </style>

    @stack('styles')
</head>
<body class="app-body">

    {{-- =====================
         SIDEBAR
    ===================== --}}
    @include('layouts.sidebar')

    {{-- Backdrop (mobile only) --}}
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>

    {{-- =====================
         MAIN CONTENT
    ===================== --}}
    <div class="all-content-wrapper">
        @include('layouts.header')

        <main class="app-main">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        {{-- APP GLOBAL FOOTER --}}
        <footer class="app-global-footer">
            <p>
                Open Hands App is a service provided by Friends of Diversity.<br class="d-sm-none">
                Copyright &copy; {{ date('Y') }} Friends of Diversity. All rights reserved.
            </p>
        </footer>
    </div>

    @include('layouts.chat-fab')
    @include('layouts.panic-button')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const DESKTOP_BREAKPOINT = 992;
        const STORAGE_KEY = 'oh-sidebar-collapsed';

        function isDesktop() { return window.innerWidth >= DESKTOP_BREAKPOINT; }

        function openSidebar() {
            document.getElementById('sidebar')?.classList.add('active');
            document.getElementById('sidebarBackdrop')?.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            document.getElementById('sidebar')?.classList.remove('active');
            document.getElementById('sidebarBackdrop')?.classList.remove('active');
            document.body.style.overflow = '';
        }

        function toggleSidebar() {
            if (isDesktop()) {
                document.body.classList.toggle('sidebar-collapsed');
                const collapsed = document.body.classList.contains('sidebar-collapsed');
                try { localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0'); } catch (e) {}
            } else {
                const sidebar = document.getElementById('sidebar');
                if (sidebar?.classList.contains('active')) { closeSidebar(); } 
                else { openSidebar(); }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (isDesktop()) {
                try {
                    if (localStorage.getItem(STORAGE_KEY) === '1') {
                        document.body.classList.add('sidebar-collapsed');
                    }
                } catch (e) {}
            }

            document.querySelectorAll('#sidebar .menu-item').forEach(el => {
                el.addEventListener('click', () => {
                    if (!isDesktop()) closeSidebar();
                });
            });
        });

        window.addEventListener('resize', () => { if (isDesktop()) closeSidebar(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeSidebar(); });

        window.showToast = function (message, type = 'success') {
            let toast = document.getElementById('__globalToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = '__globalToast';
                toast.style.cssText = `
                    position: fixed; bottom: 24px; left: 50%;
                    transform: translateX(-50%) translateY(140%);
                    padding: 14px 24px; border-radius: 12px;
                    font-size: 14px; font-weight: 600; color: #fff;
                    box-shadow: 0 12px 36px rgba(0,0,0,0.4);
                    z-index: 99999; transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
                    display: flex; align-items: center; gap: 10px;
                    max-width: calc(100vw - 40px);
                `;
                document.body.appendChild(toast);
            }
            toast.style.background = type === 'error'
                ? 'linear-gradient(180deg, #ef4444, #b91c1c)'
                : 'linear-gradient(180deg, #22c55e, #16a34a)';
            toast.innerHTML = `<i class="bi bi-${type === 'error' ? 'exclamation-triangle-fill' : 'check-circle-fill'}"></i><span></span>`;
            toast.querySelector('span').textContent = message;

            requestAnimationFrame(() => { toast.style.transform = 'translateX(-50%) translateY(0)'; });
            clearTimeout(toast._t);
            toast._t = setTimeout(() => { toast.style.transform = 'translateX(-50%) translateY(140%)'; }, 3000);
        };
    </script>

    @stack('scripts')
</body>
</html>
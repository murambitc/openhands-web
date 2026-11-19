@php
    use App\Models\Organization;
    $organizations = Organization::orderBy('name')->get();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Open Hands — Your safe space for support, connection, and healing. Mental wellness for everyone.">
    <meta name="theme-color" content="#2F5DAA">

    <title>{{ config('app.name', 'Open Hands') }} — Your Safe Space for Healing</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --bg: #0b1220;
            --primary: #2F5DAA;
            --primary-dark: #1e3a8a;
            --accent: #be185d;
            --accent-light: #ff4d8d;
            --accent-dark: #c2185b;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --glass: rgba(255, 255, 255, 0.06);
            --border: rgba(255, 255, 255, 0.1);
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; scroll-behavior: smooth; }
        body {
            background: linear-gradient(135deg, #2F5DAA, #1e3a8a);
            color: var(--text);
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* NAVIGATION */
        .oh-navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 14px 0; transition: all 0.3s ease; background: rgba(11, 18, 32, 0.6); backdrop-filter: blur(16px); border-bottom: 1px solid transparent; }
        .oh-navbar.scrolled { background: rgba(11, 18, 32, 0.92); border-bottom-color: var(--border); padding: 10px 0; }
        .oh-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        .oh-logo { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 22px; color: #fff; text-decoration: none; }
        .oh-logo:hover { color: var(--accent-light); }
        .oh-logo img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255, 255, 255, 0.2); }
        .oh-logo span { color: var(--accent-light); }
        .oh-nav-links { display: flex; align-items: center; gap: 28px; list-style: none; margin: 0; padding: 0; }
        .oh-nav-links a { color: var(--text); text-decoration: none; font-size: 14px; font-weight: 500; transition: color 0.2s; position: relative; }
        .oh-nav-links a:hover { color: #fff; }
        .oh-nav-links a::after { content: ''; position: absolute; bottom: -6px; left: 0; width: 0; height: 2px; background: var(--accent-light); transition: width 0.25s; }
        .oh-nav-links a:hover::after { width: 100%; }
        .oh-nav-cta { display: flex; gap: 10px; }
        .oh-btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.25s ease; border: none; cursor: pointer; white-space: nowrap; }
        .oh-btn:hover { transform: translateY(-2px); }
        .oh-btn-primary { background: linear-gradient(180deg, var(--accent-light), var(--accent-dark)); color: #fff; box-shadow: 0 4px 15px rgba(190, 24, 93, 0.35); }
        .oh-btn-primary:hover { color: #fff; opacity: 0.92; }
        .oh-btn-outline { background: transparent; color: #fff; border: 1px solid rgba(255, 255, 255, 0.3); }
        .oh-btn-outline:hover { background: rgba(255, 255, 255, 0.08); color: #fff; border-color: rgba(255, 255, 255, 0.5); }
        .oh-hamburger { display: none; background: transparent; border: none; color: #fff; font-size: 26px; cursor: pointer; padding: 4px 8px; }
        .oh-mobile-menu { display: none; position: fixed; top: 70px; left: 0; right: 0; background: rgba(11, 18, 32, 0.98); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); padding: 20px; z-index: 999; }
        .oh-mobile-menu.active { display: block; }
        .oh-mobile-menu a { display: block; color: #fff; text-decoration: none; padding: 12px 0; font-size: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        .oh-mobile-menu .oh-btn { display: flex; justify-content: center; margin-top: 12px; width: 100%; }
        @media (max-width: 991px) { .oh-nav-links, .oh-nav-cta { display: none; } .oh-hamburger { display: block; } }

        /* HERO */
        .oh-hero { position: relative; min-height: 100vh; display: flex; align-items: center; overflow: hidden; padding-top: 90px; }
        .oh-slider { position: absolute; inset: 0; z-index: 0; }
        .oh-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 1s ease, transform 1s ease; transform: scale(1.05); }
        .oh-slide.active { opacity: 1; transform: scale(1); }
        .oh-slide-overlay { position: absolute; inset: 0; background: linear-gradient(135deg, rgba(47, 93, 170, 0.88), rgba(30, 58, 138, 0.92)); }
        .oh-floating-shape { position: absolute; border-radius: 50%; filter: blur(40px); opacity: 0.4; animation: floatShape 12s ease-in-out infinite; }
        .oh-shape-1 { width: 300px; height: 300px; background: rgba(255, 77, 141, 0.4); top: 10%; left: -100px; }
        .oh-shape-2 { width: 400px; height: 400px; background: rgba(47, 93, 170, 0.5); bottom: 5%; right: -150px; animation-delay: 3s; }
        .oh-shape-3 { width: 200px; height: 200px; background: rgba(255, 77, 141, 0.3); top: 50%; right: 20%; animation-delay: 6s; }
        @keyframes floatShape { 0%, 100% { transform: translate(0, 0) scale(1); } 50% { transform: translate(30px, -30px) scale(1.1); } }
        .oh-hero-content { position: relative; z-index: 2; width: 100%; padding: 60px 24px; }
        .oh-hero-inner { max-width: 900px; margin: 0 auto; text-align: center; }
        .oh-hero-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); padding: 8px 16px; border-radius: 50px; font-size: 13px; color: #fff; margin-bottom: 24px; backdrop-filter: blur(10px); }
        .oh-hero-badge .pulse-dot { width: 8px; height: 8px; background: #22c55e; border-radius: 50%; animation: pulseDot 1.8s infinite; }
        @keyframes pulseDot { 0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); } 50% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); } }
        .oh-hero h1 { font-size: clamp(32px, 6vw, 64px); font-weight: 800; color: #fff; line-height: 1.1; margin-bottom: 20px; letter-spacing: -1px; }
        .oh-hero h1 .highlight { background: linear-gradient(180deg, var(--accent-light), var(--accent-dark)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .oh-hero p.lead { font-size: clamp(16px, 2vw, 20px); color: var(--text); line-height: 1.6; max-width: 680px; margin: 0 auto 32px; opacity: 0.95; }
        .oh-hero-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; margin-bottom: 48px; }
        .oh-hero-actions .oh-btn { padding: 14px 28px; font-size: 15px; }
        .oh-hero-stats { display: flex; justify-content: center; gap: 40px; flex-wrap: wrap; margin-top: 32px; }
        .oh-stat { text-align: center; color: #fff; }
        .oh-stat .num { font-size: 28px; font-weight: 800; background: linear-gradient(180deg, #fff, rgba(255, 255, 255, 0.7)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .oh-stat .label { font-size: 12px; color: var(--muted); text-transform: uppercase; letter-spacing: 1.2px; margin-top: 4px; }
        .oh-slider-dots { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); display: flex; gap: 10px; z-index: 3; }
        .oh-slider-dots button { width: 10px; height: 10px; border-radius: 50%; background: rgba(255, 255, 255, 0.3); border: none; cursor: pointer; transition: all 0.3s; padding: 0; }
        .oh-slider-dots button.active { background: var(--accent-light); width: 30px; border-radius: 6px; }
        .oh-scroll-indicator { position: absolute; bottom: 90px; left: 50%; transform: translateX(-50%); color: rgba(255, 255, 255, 0.6); font-size: 12px; text-align: center; z-index: 3; animation: bounce 2s infinite; }
        @keyframes bounce { 0%, 100% { transform: translateX(-50%) translateY(0); } 50% { transform: translateX(-50%) translateY(8px); } }

        /* SECTIONS */
        .oh-section { padding: 100px 24px; position: relative; }
        .oh-section-title { text-align: center; margin-bottom: 60px; }
        .oh-section-title h2 { font-size: clamp(28px, 4vw, 42px); font-weight: 700; color: #fff; margin-bottom: 12px; letter-spacing: -0.5px; }
        .oh-section-title p { color: var(--muted); font-size: 16px; max-width: 600px; margin: 0 auto; }

        /* FEATURES */
        .oh-features { background: rgba(11, 18, 32, 0.5); backdrop-filter: blur(10px); }
        .oh-features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; max-width: 1200px; margin: 0 auto; }
        .oh-feature-card { background: var(--glass); border: 1px solid var(--border); border-radius: 18px; padding: 32px 24px; text-align: center; transition: all 0.35s ease; backdrop-filter: blur(14px); position: relative; overflow: hidden; }
        .oh-feature-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--accent-light), var(--accent-dark)); transform: scaleX(0); transition: transform 0.4s; transform-origin: left; }
        .oh-feature-card:hover { transform: translateY(-8px); border-color: rgba(255, 77, 141, 0.4); box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4); }
        .oh-feature-card:hover::before { transform: scaleX(1); }
        .oh-feature-icon { width: 72px; height: 72px; border-radius: 20px; background: linear-gradient(180deg, var(--accent-light), var(--accent-dark)); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px; color: #fff; box-shadow: 0 8px 25px rgba(190, 24, 93, 0.4); }
        .oh-feature-card h3 { color: #fff; font-size: 18px; font-weight: 600; margin-bottom: 10px; }
        .oh-feature-card p { color: var(--muted); font-size: 14px; line-height: 1.6; }

        /* ORGANIZATIONS */
        .oh-orgs { background: rgba(30, 58, 138, 0.3); backdrop-filter: blur(10px); }
        .oh-orgs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; max-width: 1200px; margin: 0 auto; }
        .oh-org-card { background: var(--glass); border: 1px solid var(--border); border-radius: 14px; padding: 20px; display: flex; align-items: center; gap: 14px; transition: all 0.3s ease; backdrop-filter: blur(10px); text-decoration: none; }
        .oh-org-card:hover { background: rgba(255, 255, 255, 0.1); border-color: var(--accent); transform: translateX(4px); }
        .oh-org-logo { width: 50px; height: 50px; border-radius: 10px; background: var(--primary); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; flex-shrink: 0; overflow: hidden; }
        .oh-org-logo img { width: 100%; height: 100%; object-fit: cover; }
        .oh-org-info { flex: 1; min-width: 0; }
        .oh-org-info h4 { color: #fff; font-size: 14px; font-weight: 600; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .oh-org-info p { color: var(--muted); font-size: 12px; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* TESTIMONIALS */
        .oh-testimonials { background: rgba(11, 18, 32, 0.6); }
        .oh-testimonials-carousel { max-width: 800px; margin: 0 auto; position: relative; min-height: 260px; }
        .oh-testimonial { position: absolute; inset: 0; opacity: 0; visibility: hidden; transition: opacity 0.5s ease; text-align: center; padding: 40px 32px; background: var(--glass); border: 1px solid var(--border); border-radius: 20px; backdrop-filter: blur(14px); }
        .oh-testimonial.active { opacity: 1; visibility: visible; }
        .oh-testimonial .quote-icon { font-size: 42px; color: var(--accent-light); opacity: 0.6; margin-bottom: 16px; }
        .oh-testimonial p { font-size: 17px; color: var(--text); line-height: 1.7; font-style: italic; margin-bottom: 24px; }
        .oh-testimonial .author { display: flex; flex-direction: column; align-items: center; gap: 6px; }
        .oh-testimonial .avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(180deg, var(--accent-light), var(--accent-dark)); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 18px; }
        .oh-testimonial .author-name { color: #fff; font-weight: 600; font-size: 14px; }
        .oh-testimonial .author-role { color: var(--muted); font-size: 12px; }
        .oh-testimonial-dots { display: flex; justify-content: center; gap: 8px; margin-top: 24px; }
        .oh-testimonial-dots button { width: 8px; height: 8px; border-radius: 50%; background: rgba(255, 255, 255, 0.25); border: none; cursor: pointer; padding: 0; transition: all 0.3s; }
        .oh-testimonial-dots button.active { background: var(--accent-light); width: 24px; border-radius: 4px; }

        /* CTA & FOOTER */
        .oh-cta { padding: 80px 24px; background: linear-gradient(135deg, var(--accent-dark), var(--accent)); text-align: center; position: relative; overflow: hidden; }
        .oh-cta-inner { position: relative; z-index: 2; max-width: 700px; margin: 0 auto; }
        .oh-cta h2 { color: #fff; font-size: clamp(28px, 4vw, 42px); font-weight: 800; margin-bottom: 16px; }
        .oh-cta p { color: rgba(255, 255, 255, 0.9); font-size: 16px; margin-bottom: 32px; line-height: 1.6; }
        .oh-cta .oh-btn { padding: 14px 32px; font-size: 15px; background: #fff; color: var(--accent-dark); font-weight: 700; }
        .oh-cta .oh-btn:hover { background: rgba(255, 255, 255, 0.92); color: var(--accent-dark); }
        .oh-footer { background: var(--bg); padding: 60px 24px 30px; border-top: 1px solid var(--border); }
        .oh-footer-inner { max-width: 1200px; margin: 0 auto; }
        .oh-footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .oh-footer-brand p { color: var(--muted); font-size: 14px; margin-top: 12px; line-height: 1.6; max-width: 300px; }
        .oh-footer h5 { color: #fff; font-size: 14px; font-weight: 700; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px; }
        .oh-footer ul { list-style: none; padding: 0; margin: 0; }
        .oh-footer ul li { margin-bottom: 10px; }
        .oh-footer ul a { color: var(--muted); text-decoration: none; font-size: 13px; transition: color 0.2s; }
        .oh-footer ul a:hover { color: var(--accent-light); }
        .oh-footer-bottom { border-top: 1px solid var(--border); padding-top: 24px; text-align: center; color: var(--muted); font-size: 12px; }
        @media (max-width: 768px) { .oh-footer-grid { grid-template-columns: 1fr 1fr; gap: 30px; } .oh-footer-brand { grid-column: span 2; } }

        /* FLOATING */
        .oh-floating-menu { position: fixed; left: 20px; bottom: 20px; display: flex; flex-direction: column; gap: 12px; z-index: 900; }
        .oh-float-btn { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; border: none; color: #fff; font-size: 22px; cursor: pointer; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.35); transition: all 0.25s ease; text-decoration: none; }
        .oh-float-btn:hover { transform: scale(1.08); color: #fff; }
        .oh-float-toggle { background: linear-gradient(135deg, #831843, #be185d); }
        .oh-float-faq { background: linear-gradient(135deg, #7b2cbf, #9d4edd); }
        .oh-float-ios { background: linear-gradient(135deg, #111827, #374151); }
        .oh-float-apk { background: linear-gradient(135deg, #16a34a, #22c55e); }
        .oh-floating-menu.collapsed .oh-float-faq, .oh-floating-menu.collapsed .oh-float-ios, .oh-floating-menu.collapsed .oh-float-apk { opacity: 0; visibility: hidden; transform: translateY(20px) scale(0.8); pointer-events: none; }
        .oh-crisis-btn { position: fixed; right: 20px; bottom: 20px; width: 60px; height: 60px; border-radius: 50%; background: #dc2626; border: none; color: white; font-size: 24px; cursor: pointer; z-index: 900; box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); animation: crisisPulse 1.8s infinite; display: flex; align-items: center; justify-content: center; }
        @keyframes crisisPulse { 0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); } 70% { box-shadow: 0 0 0 18px rgba(220, 38, 38, 0); } 100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); } }

        /* MODALS */
        .oh-modal .modal-content { background: #1b2a47; color: #fff; border-radius: 16px; border: 1px solid var(--border); }
        .oh-modal .accordion-button { background: transparent; color: #fff; font-weight: 500; font-size: 14px; padding: 14px 16px; border: none; box-shadow: none; }
        .oh-modal .accordion-button:not(.collapsed) { background: rgba(190, 24, 93, 0.15); color: #fff; }
        .oh-modal .accordion-button::after { filter: invert(1); }
        .oh-modal .accordion-body { color: var(--muted); font-size: 13px; padding: 12px 16px; }
        .oh-modal .accordion-item { background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border); border-radius: 10px !important; margin-bottom: 8px; overflow: hidden; }
        .oh-crisis-modal .modal-content { background: #1b2a47; color: #fff; border-radius: 16px; border: 1px solid rgba(220, 38, 38, 0.5); box-shadow: 0 0 40px rgba(220, 38, 38, 0.3); }
        .oh-crisis-modal .btn { margin-bottom: 8px; padding: 12px; font-weight: 600; border-radius: 10px; }
        .oh-reveal { opacity: 0; transform: translateY(30px); transition: all 0.7s ease; }
        .oh-reveal.visible { opacity: 1; transform: translateY(0); }
        @media (max-width: 576px) { .oh-section { padding: 60px 20px; } .oh-hero-stats { gap: 24px; } .oh-stat .num { font-size: 22px; } .oh-testimonial { padding: 28px 20px; } .oh-floating-menu { left: 15px; bottom: 15px; } .oh-float-btn { width: 48px; height: 48px; font-size: 18px; } .oh-crisis-btn { width: 52px; height: 52px; font-size: 20px; } }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="oh-navbar" id="ohNavbar">
    <div class="oh-container d-flex justify-content-between align-items-center">
        <a href="{{ url('/') }}" class="oh-logo">
            <img src="{{ asset('img/logo/logo.png') }}" alt="Open Hands" onerror="this.style.display='none'">
            Open<span>&nbsp;Hands</span>
        </a>
        <ul class="oh-nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="{{ route('locate') }}">Support Map</a></li>
            <li><a href="#organizations">Directory</a></li>
            <li><a href="#testimonials">Stories</a></li>
            <li><a href="#about">About</a></li>
        </ul>
        <div class="oh-nav-cta">
            @auth
                <a href="{{ url('/dashboard') }}" class="oh-btn oh-btn-primary"><i class="bi bi-speedometer2"></i> Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="oh-btn oh-btn-outline">Sign In</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="oh-btn oh-btn-primary"><i class="bi bi-person-plus"></i> Get Started</a>
                @endif
            @endauth
        </div>
        <button class="oh-hamburger" id="ohHamburger" aria-label="Menu"><i class="bi bi-list"></i></button>
    </div>
</nav>

<div class="oh-mobile-menu" id="ohMobileMenu">
    <a href="#features" onclick="closeMobileMenu()">Features</a>
    <a href="{{ route('locate') }}">Support Map</a>
    <a href="#organizations" onclick="closeMobileMenu()">Directory</a>
    <a href="#testimonials" onclick="closeMobileMenu()">Stories</a>
    <a href="#about" onclick="closeMobileMenu()">About</a>
    @auth
        <a href="{{ url('/dashboard') }}" class="oh-btn oh-btn-primary">Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="oh-btn oh-btn-outline">Sign In</a>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="oh-btn oh-btn-primary">Get Started</a>
        @endif
    @endauth
</div>

{{-- HERO --}}
<section class="oh-hero">
    <div class="oh-slider" id="ohSlider">
        <div class="oh-slide active" data-slide="0"><div class="oh-slide-overlay"></div><div class="oh-floating-shape oh-shape-1"></div><div class="oh-floating-shape oh-shape-2"></div><div class="oh-floating-shape oh-shape-3"></div></div>
        <div class="oh-slide" data-slide="1"><div class="oh-slide-overlay"></div><div class="oh-floating-shape oh-shape-2" style="top: 20%; left: 10%;"></div><div class="oh-floating-shape oh-shape-1" style="bottom: 10%; right: 10%;"></div></div>
        <div class="oh-slide" data-slide="2"><div class="oh-slide-overlay"></div><div class="oh-floating-shape oh-shape-3" style="top: 30%; left: 40%;"></div><div class="oh-floating-shape oh-shape-1" style="top: 10%; right: 20%;"></div></div>
    </div>
    <div class="oh-hero-content">
        <div class="oh-hero-inner">
            <div class="oh-hero-badge"><span class="pulse-dot"></span><span id="heroBadgeText">Free &amp; confidential · Botswana</span></div>
            <h1 id="heroTitle">Your Safe Space for <span class="highlight">Support</span>,<br>Connection &amp; <span class="highlight">Healing</span></h1>
            <p class="lead" id="heroSubtitle">Open Hands connects you with mental wellness tools, certified counselors, and a caring community — whenever you need it, wherever you are.</p>
            <div class="oh-hero-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="oh-btn oh-btn-primary"><i class="bi bi-speedometer2"></i> Go to Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="oh-btn oh-btn-primary"><i class="bi bi-heart-pulse"></i> Start Your Journey</a>
                    <a href="{{ route('login') }}" class="oh-btn oh-btn-outline"><i class="bi bi-box-arrow-in-right"></i> Sign In</a>
                @endauth
                <a href="{{ route('locate') }}" class="oh-btn oh-btn-outline"><i class="bi bi-geo-alt-fill"></i> Support Map</a>
            </div>
            <div class="oh-hero-stats">
                <div class="oh-stat"><div class="num">24/7</div><div class="label">Support</div></div>
                <div class="oh-stat"><div class="num">100%</div><div class="label">Confidential</div></div>
                <div class="oh-stat"><div class="num">{{ $organizations->count() ?? 12 }}+</div><div class="label">Organizations</div></div>
                <div class="oh-stat"><div class="num">Free</div><div class="label">Forever</div></div>
            </div>
        </div>
    </div>
    <div class="oh-slider-dots" id="ohSliderDots">
        <button class="active" data-target="0"></button><button data-target="1"></button><button data-target="2"></button>
    </div>
    <div class="oh-scroll-indicator"><i class="bi bi-chevron-double-down" style="font-size: 20px;"></i><div style="font-size: 11px; margin-top: 4px;">Scroll to explore</div></div>
</section>

{{-- FEATURES --}}
<section class="oh-section oh-features" id="features">
    <div class="oh-section-title oh-reveal"><h2>Everything You Need to Feel Better</h2><p>A complete toolkit built for your mental wellness journey.</p></div>
    <div class="oh-features-grid">
        <div class="oh-feature-card oh-reveal"><div class="oh-feature-icon"><i class="bi bi-chat-heart"></i></div><h3>AI Wellness Chat</h3><p>Talk to a supportive AI companion anytime. Non-judgmental, always available.</p></div>
        <div class="oh-feature-card oh-reveal"><div class="oh-feature-icon"><i class="bi bi-clipboard-heart"></i></div><h3>Smart Screening</h3><p>Understand your mental health through a guided, private assessment.</p></div>
        <div class="oh-feature-card oh-reveal"><div class="oh-feature-icon"><i class="bi bi-journal-text"></i></div><h3>Mood Tracking</h3><p>Log daily moods and watch your emotional patterns over time.</p></div>
        <div class="oh-feature-card oh-reveal"><div class="oh-feature-icon"><i class="bi bi-people-fill"></i></div><h3>Trusted Directory</h3><p>Connect with verified local organizations ready to help you today.</p></div>
        <div class="oh-feature-card oh-reveal"><div class="oh-feature-icon"><i class="bi bi-heart-pulse-fill"></i></div><h3>Wellness Tools</h3><p>Breathing exercises, meditation timers, journaling prompts, and habit trackers.</p></div>
        <div class="oh-feature-card oh-reveal"><div class="oh-feature-icon"><i class="bi bi-shield-check"></i></div><h3>Private &amp; Secure</h3><p>End-to-end privacy. Your data is never shared without consent.</p></div>
    </div>
</section>

{{-- ORGANIZATIONS --}}
<section class="oh-section oh-orgs" id="organizations">
    <div class="oh-section-title oh-reveal"><h2>Trusted Support Partners</h2><p>Real organizations across Botswana ready to help.</p></div>
    <div class="oh-orgs-grid">
        @forelse($organizations as $org)
            <div class="oh-org-card oh-reveal">
                <div class="oh-org-logo">@if($org->logo)<img src="{{ asset($org->logo) }}" alt="{{ $org->name }}">@else<i class="bi bi-building"></i>@endif</div>
                <div class="oh-org-info"><h4>{{ $org->name }}</h4><p>{{ \Illuminate\Support\Str::limit($org->description, 40) }}</p></div>
            </div>
        @empty
            <div class="oh-org-card oh-reveal"><div class="oh-org-logo"><i class="bi bi-building"></i></div><div class="oh-org-info"><h4>No Organizations Yet</h4><p>Check back soon.</p></div></div>
        @endforelse
    </div>
    <div class="text-center mt-5 oh-reveal">
        @if (Route::has('login'))
            <a href="{{ route('login') }}" class="oh-btn oh-btn-primary"><i class="bi bi-people"></i> Explore Full Directory</a>
        @endif
    </div>
</section>

{{-- TESTIMONIALS --}}
<section class="oh-section oh-testimonials" id="testimonials">
    <div class="oh-section-title oh-reveal"><h2>Stories of Healing</h2><p>Real voices from our community.</p></div>
    <div class="oh-testimonials-carousel" id="ohTestimonials">
        <div class="oh-testimonial active"><div class="quote-icon"><i class="bi bi-quote"></i></div><p>"Open Hands gave me the courage to speak up. The AI companion is always there at 3am when I need it most."</p><div class="author"><div class="avatar">T</div><div class="author-name">Tshepo M.</div><div class="author-role">Anonymous User · Gaborone</div></div></div>
        <div class="oh-testimonial"><div class="quote-icon"><i class="bi bi-quote"></i></div><p>"I finally found a counselor that understands me. The directory made it so easy to connect."</p><div class="author"><div class="avatar">L</div><div class="author-name">Lesego K.</div><div class="author-role">Young Professional · Francistown</div></div></div>
        <div class="oh-testimonial"><div class="quote-icon"><i class="bi bi-quote"></i></div><p>"Tracking my mood every day showed me patterns I never noticed. It's changed how I take care of myself."</p><div class="author"><div class="avatar">N</div><div class="author-name">Neo B.</div><div class="author-role">Student · Maun</div></div></div>
    </div>
    <div class="oh-testimonial-dots" id="ohTestimonialDots">
        <button class="active" data-target="0"></button><button data-target="1"></button><button data-target="2"></button>
    </div>
</section>

{{-- CTA --}}
<section class="oh-cta" id="about">
    <div class="oh-cta-inner oh-reveal">
        <h2>You Are Not Alone.</h2>
        <p>Take the first step towards feeling better today. Free, confidential, and always available.</p>
        @auth
            <a href="{{ url('/dashboard') }}" class="oh-btn"><i class="bi bi-speedometer2"></i> Go to Dashboard</a>
        @else
            <a href="{{ route('register') }}" class="oh-btn"><i class="bi bi-heart-pulse"></i> Get Started Free</a>
        @endauth
    </div>
</section>

{{-- FOOTER --}}
<footer class="oh-footer">
    <div class="oh-footer-inner">
        <div class="oh-footer-grid">
            <div class="oh-footer-brand">
                <a href="{{ url('/') }}" class="oh-logo"><img src="{{ asset('img/logo/logo.png') }}" alt="Open Hands" onerror="this.style.display='none'">Open<span>&nbsp;Hands</span></a>
                <p>Your safe space for support, connection, and healing. Building a mentally healthier Botswana, one conversation at a time.</p>
            </div>
            <div>
                <h5>Explore</h5>
                <ul><li><a href="#features">Features</a></li><li><a href="{{ route('locate') }}">Support Map</a></li><li><a href="#organizations">Directory</a></li><li><a href="#testimonials">Stories</a></li><li><a href="#about">About</a></li></ul>
            </div>
            <div>
                <h5>Support</h5>
                <ul>
                    <li><a href="{{ route('locate') }}">Map / Directions</a></li>
                    <li><a href="tel:999">Emergency (999)</a></li><li><a href="tel:997">Ambulance (997)</a></li><li><a href="https://wa.me/26777649963" target="_blank">WhatsApp</a></li><li><a href="{{ route('login') }}">Sign In</a></li>
                </ul>
            </div>
            <div>
                <h5>Legal</h5>
                <ul><li><a href="#">Privacy Policy</a></li><li><a href="#">Terms of Service</a></li><li><a href="#">Cookie Policy</a></li><li><a href="#">Contact</a></li></ul>
            </div>
        </div>
        <div class="oh-footer-bottom">Open Hands App is a service provided by Friends of Diversity. <br class="d-md-none"> Copyright &copy; 2026 Friends of Diversity. All rights reserved.</div>
    </div>
</footer>

{{-- FLOATING MENU --}}
<div class="oh-floating-menu" id="ohFloatingMenu">
    <button class="oh-float-btn oh-float-toggle" onclick="toggleFloatingMenu()" aria-label="Toggle menu"><i class="bi bi-list"></i></button>
    <button class="oh-float-btn oh-float-faq" data-bs-toggle="modal" data-bs-target="#ohFaqModal" aria-label="FAQ"><i class="bi bi-question-lg"></i></button>
    <a href="#" class="oh-float-btn oh-float-ios" title="Download iOS App" aria-label="iOS"><i class="bi bi-apple"></i></a>
    <a href="{{ asset('downloads/OpenHands.apk') }}" class="oh-float-btn oh-float-apk" download title="Download Android APK" aria-label="Android"><i class="bi bi-android2"></i></a>
</div>
<button class="oh-crisis-btn" data-bs-toggle="modal" data-bs-target="#ohCrisisModal" aria-label="Emergency Help"><i class="bi bi-exclamation-triangle-fill"></i></button>

{{-- FAQ MODAL --}}
<div class="modal fade oh-modal" id="ohFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0"><h5 class="modal-title"><i class="bi bi-question-circle"></i> Frequently Asked Questions</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="accordion" id="ohFaqAccordion">
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">Is Open Hands free to use?</button></h2><div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#ohFaqAccordion"><div class="accordion-body">Yes — 100% free forever. No hidden fees, no premium tiers.</div></div></div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">Is my information private?</button></h2><div id="faq2" class="accordion-collapse collapse" data-bs-parent="#ohFaqAccordion"><div class="accordion-body">Absolutely. Your data is encrypted and never shared without your explicit consent. We do not sell user information.</div></div></div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">How does the AI companion work?</button></h2><div id="faq3" class="accordion-collapse collapse" data-bs-parent="#ohFaqAccordion"><div class="accordion-body">The AI chat is a supportive, non-judgmental companion available 24/7. It is not a replacement for professional care — for emergencies please use the crisis button.</div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CRISIS MODAL --}}
<div class="modal fade oh-crisis-modal" id="ohCrisisModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0"><h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Emergency Help</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body text-center">
                <p class="mb-4" style="color: var(--muted);">If you are in immediate danger, please reach out now. You are not alone.</p>
                <a href="https://wa.me/26777649963?text={{ urlencode('Emergency, I need help now') }}" target="_blank" class="btn btn-success w-100"><i class="bi bi-whatsapp"></i> Emergency WhatsApp</a>
                <a href="tel:999" class="btn btn-danger w-100"><i class="bi bi-telephone-fill"></i> Call Police (999)</a>
                <a href="tel:997" class="btn btn-info w-100"><i class="bi bi-hospital"></i> Call Ambulance (997)</a>
                <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const navbar = document.getElementById('ohNavbar');
        window.addEventListener('scroll', () => { navbar.classList.toggle('scrolled', window.scrollY > 40); });
        document.getElementById('ohHamburger').addEventListener('click', () => { document.getElementById('ohMobileMenu').classList.toggle('active'); });
        function closeMobileMenu() { document.getElementById('ohMobileMenu').classList.remove('active'); }

        const slides = document.querySelectorAll('.oh-slide');
        const sliderDots = document.querySelectorAll('#ohSliderDots button');
        let currentSlide = 0;
        let slideInterval;
        const heroContent = {
            0: { badge: 'Free &amp; confidential · Botswana', title: 'Your Safe Space for <span class="highlight">Support</span>,<br>Connection &amp; <span class="highlight">Healing</span>', subtitle: 'Open Hands connects you with mental wellness tools, certified counselors, and a caring community — whenever you need it, wherever you are.' },
            1: { badge: '24/7 AI Companion', title: 'Someone to Talk to, <span class="highlight">Anytime</span>', subtitle: 'Our supportive AI companion listens without judgment — at 3am, on a bad day, or whenever you need a moment of calm.' },
            2: { badge: 'Trusted Local Partners', title: 'Real People. <span class="highlight">Real Help.</span>', subtitle: 'Connect with verified counselors and organizations across Botswana who are ready to walk alongside you on your journey.' }
        };
        function showSlide(index) {
            slides.forEach((s, i) => s.classList.toggle('active', i === index));
            sliderDots.forEach((d, i) => d.classList.toggle('active', i === index));
            const title = document.getElementById('heroTitle');
            const subtitle = document.getElementById('heroSubtitle');
            const badge = document.getElementById('heroBadgeText');
            title.style.opacity = 0; subtitle.style.opacity = 0; badge.style.opacity = 0;
            setTimeout(() => {
                title.innerHTML = heroContent[index].title;
                subtitle.textContent = heroContent[index].subtitle;
                badge.innerHTML = heroContent[index].badge;
                title.style.opacity = 1; subtitle.style.opacity = 1; badge.style.opacity = 1;
            }, 250);
            currentSlide = index;
        }
        function nextSlide() { showSlide((currentSlide + 1) % slides.length); }
        function startSlider() { slideInterval = setInterval(nextSlide, 6000); }
        sliderDots.forEach(dot => { dot.addEventListener('click', () => { clearInterval(slideInterval); showSlide(parseInt(dot.dataset.target)); startSlider(); }); });
        ['heroTitle', 'heroSubtitle', 'heroBadgeText'].forEach(id => { document.getElementById(id).style.transition = 'opacity 0.4s ease'; });
        startSlider();

        const testimonials = document.querySelectorAll('.oh-testimonial');
        const testimonialDots = document.querySelectorAll('#ohTestimonialDots button');
        let currentTestimonial = 0;
        let testimonialInterval;
        function showTestimonial(index) {
            testimonials.forEach((t, i) => t.classList.toggle('active', i === index));
            testimonialDots.forEach((d, i) => d.classList.toggle('active', i === index));
            currentTestimonial = index;
        }
        function nextTestimonial() { showTestimonial((currentTestimonial + 1) % testimonials.length); }
        testimonialDots.forEach(dot => { dot.addEventListener('click', () => { clearInterval(testimonialInterval); showTestimonial(parseInt(dot.dataset.target)); testimonialInterval = setInterval(nextTestimonial, 5000); }); });
        testimonialInterval = setInterval(nextTestimonial, 5000);

        function toggleFloatingMenu() { document.getElementById('ohFloatingMenu').classList.toggle('collapsed'); }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); } });
        }, { threshold: 0.12 });
        document.querySelectorAll('.oh-reveal').forEach(el => observer.observe(el));
    });
</script>
</body>
</html>
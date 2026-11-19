<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register — Open Hands</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --bg: #0b1220;
            --card: #1b2a47;
            --primary: #2F5DAA;
            --accent: #be185d;
            --accent-light: #ff4d8d;
            --accent-dark: #c2185b;
            --border: rgba(255, 255, 255, 0.08);
            --text: #e2e8f0;
            --muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            min-height: 100vh;
        }

        body {
            background: linear-gradient(135deg, #2F5DAA, #1e3a8a);
            font-family: 'Roboto', sans-serif;
            color: var(--text);
            margin: 0;
            padding: 40px 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .nk-auth-wrapper {
            width: 100%;
            max-width: 560px;
            padding: 0 16px;
        }

        .nk-auth-card {
            background: rgba(27, 42, 71, 0.95);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 40px 32px;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Close button */
        .nk-close-btn {
            position: absolute;
            top: 14px;
            right: 18px;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            transition: 0.2s;
            line-height: 1;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .nk-close-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--accent-light);
        }

        /* Logo */
        .nk-auth-logo {
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1px;
            text-align: center;
            margin-bottom: 4px;
        }

        .nk-auth-logo span {
            color: var(--accent-light);
        }

        .nk-auth-subtitle {
            text-align: center;
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 24px;
        }

        /* Step progress */
        .step-progress {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .step-line {
            position: absolute;
            top: 18px;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
            border-radius: 3px;
        }

        .step-item {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 33.333%;
        }

        .step-item .circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #253a5c;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            border: 2px solid transparent;
        }

        .step-item small {
            display: block;
            margin-top: 6px;
            font-size: 11px;
            color: #8a9bb5;
            letter-spacing: 0.5px;
        }

        .step-item.active .circle {
            background: linear-gradient(180deg, var(--accent-light), var(--accent-dark));
            transform: scale(1.1);
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 15px rgba(255, 77, 141, 0.5);
        }

        .step-item.completed .circle {
            background: #22c55e;
        }

        /* Steps */
        .step {
            display: none;
            animation: stepFade 0.35s ease;
        }

        .step.active {
            display: block;
        }

        @keyframes stepFade {
            from { opacity: 0; transform: translateX(15px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* Form controls */
        .form-label {
            color: var(--muted);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }

        .form-control,
        .form-select {
            background: #ffffff;
            color: #000000;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 14px;
            transition: all 0.25s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background: #ffffff;
            color: #000000;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(47, 93, 170, 0.25);
            outline: none;
        }

        .form-control::placeholder {
            color: #64748b;
            opacity: 1;
        }

        /* Buttons */
        .btn-nk-primary {
            background: linear-gradient(180deg, var(--accent-light), var(--accent-dark));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 500;
            padding: 11px 20px;
            transition: all 0.25s ease;
        }

        .btn-nk-primary:hover {
            transform: translateY(-2px);
            opacity: 0.92;
            color: #fff;
        }

        .btn-nk-outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: var(--muted);
            border-radius: 10px;
            padding: 11px 20px;
            transition: 0.25s;
        }

        .btn-nk-outline:hover {
            border-color: var(--primary);
            color: #fff;
            background: rgba(47, 93, 170, 0.15);
        }

        /* Password strength */
        .strength-wrapper {
            height: 6px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            overflow: hidden;
            margin-top: 8px;
        }

        #strengthBar {
            height: 100%;
            width: 0%;
            transition: 0.3s;
            border-radius: 10px;
            background: linear-gradient(90deg, #ff4d8d, #c2185b);
        }

        #strengthText {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
            display: block;
        }

        /* Alerts */
        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #bbf7d0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        /* Footer */
        .nk-auth-footer {
            color: var(--muted);
            font-size: 12px;
            text-align: center;
            margin-top: 24px;
        }

        .nk-auth-footer a {
            color: var(--accent-light);
            text-decoration: none;
        }

        .nk-auth-footer a:hover {
            text-decoration: underline;
        }

        /* Success modal */
        .modal-content {
            background: #1b2a47;
            color: #fff;
            border-radius: 16px;
            border: 1px solid var(--border);
        }

        /* Buttons row */
        .btn-row {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-row .btn {
            flex: 1;
        }

        /* Mobile tweak */
        @media (max-width: 576px) {
            .nk-auth-card {
                padding: 32px 20px;
            }
            .nk-auth-logo {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
<div class="nk-auth-wrapper">
    <div class="nk-auth-card">
        <button type="button" class="nk-close-btn" onclick="goToLogin()" aria-label="Close">✖</button>

        {{-- Logo --}}
        <div class="nk-auth-logo">Open<span> Hands App</span></div>
        <p class="nk-auth-subtitle">Create Your Account</p>

        {{-- Error alert (server-side) --}}
        @if ($errors->any())
            <div class="alert-danger">
                @foreach ($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Step progress --}}
        <div class="step-progress">
            <div class="step-line"></div>

            <div class="step-item active" id="indicator1">
                <div class="circle">1</div>
                <small>Wellness</small>
            </div>

            <div class="step-item" id="indicator2">
                <div class="circle">2</div>
                <small>Location</small>
            </div>

            <div class="step-item" id="indicator3">
                <div class="circle">3</div>
                <small>Account</small>
            </div>
        </div>

        {{-- Form --}}
        <form id="registerForm" method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            {{-- ============================= --}}
            {{-- STEP 1 — WELLNESS INTAKE --}}
            {{-- ============================= --}}
            <div class="step active" id="step1">
                <div class="mb-3">
                    <label class="form-label">1. How are you feeling today?</label>
                    <select class="form-control" name="mood" id="mood" required>
                        <option value="">Select Your Mood</option>
                        <option value="Happy">😊 Happy</option>
                        <option value="Excited">😄 Excited</option>
                        <option value="Content">🙂 Content</option>
                        <option value="Relaxed">😌 Relaxed</option>
                        <option value="Loved">😍 Loved</option>
                        <option value="Confident">😎 Confident</option>
                        <option value="Thoughtful">🤔 Thoughtful</option>
                        <option value="Neutral">😐 Neutral</option>
                        <option value="Confused">😕 Confused</option>
                        <option value="Worried">😟 Worried</option>
                        <option value="Sad">😢 Sad</option>
                        <option value="Crying">😭 Crying</option>
                        <option value="Angry">😡 Angry</option>
                        <option value="Frustrated">😤 Frustrated</option>
                        <option value="Disappointed">😞 Disappointed</option>
                        <option value="Tired">😴 Tired</option>
                        <option value="Anxious">😰 Anxious</option>
                        <option value="Scared">😱 Scared</option>
                        <option value="Overwhelmed">🥺 Overwhelmed</option>
                        <option value="Sick">🤒 Sick</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">2. Do you feel safe at home/work?</label>
                    <select class="form-control" name="safety" id="safety" required>
                        <option value="">Select Option</option>
                        <option value="Yes">👍 Yes</option>
                        <option value="No">👎 No</option>
                    </select>
                </div>

                <button type="button" class="btn btn-nk-primary w-100" onclick="nextStep(1)">
                    Next
                </button>
            </div>

            {{-- ============================= --}}
            {{-- STEP 2 — LOCATION --}}
            {{-- ============================= --}}
            <div class="step" id="step2">
                <div class="mb-3">
                    <label class="form-label">Country</label>
                    <select class="form-control" name="country" id="country" required>
                        <option value="">Select country</option>
                        <option value="Botswana">🇧🇼 Botswana</option>
                        <option value="South Africa">🇿🇦 South Africa</option>
                        <option value="Zimbabwe">🇿🇼 Zimbabwe</option>
                        <option value="Namibia">🇳🇦 Namibia</option>
                        <option value="United States">🇺🇸 United States</option>
                        <option value="United Kingdom">🇬🇧 United Kingdom</option>
                        <option value="Canada">🇨🇦 Canada</option>
                        <option value="Australia">🇦🇺 Australia</option>
                        <option value="India">🇮🇳 India</option>
                        <option value="Nigeria">🇳🇬 Nigeria</option>
                        <option value="Kenya">🇰🇪 Kenya</option>
                        <option value="Ghana">🇬🇭 Ghana</option>
                        <option value="France">🇫🇷 France</option>
                        <option value="Germany">🇩🇪 Germany</option>
                        <option value="Brazil">🇧🇷 Brazil</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">City / Town / Village</label>
                    <input type="text" class="form-control" name="location" id="location"
                           placeholder="e.g. Gaborone" required>
                </div>

                <div class="btn-row">
                    <button type="button" class="btn btn-nk-outline" onclick="prevStep(1)">
                        Previous
                    </button>
                    <button type="button" class="btn btn-nk-primary" onclick="nextStep(2)">
                        Next
                    </button>
                </div>
            </div>

            {{-- ============================= --}}
            {{-- STEP 3 — ACCOUNT --}}
            {{-- ============================= --}}
            <div class="step" id="step3">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username"
                           placeholder="Choose a username" minlength="3" maxlength="50"
                           value="{{ old('username') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email"
                           placeholder="your@email.com" value="{{ old('email') }}" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password"
                           placeholder="Create a strong password" minlength="8" required>
                    <div class="strength-wrapper">
                        <div id="strengthBar"></div>
                    </div>
                    <small id="strengthText"></small>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation"
                           id="password_confirmation" placeholder="Confirm your password" required>
                </div>

                <div class="btn-row">
                    <button type="button" class="btn btn-nk-outline" onclick="prevStep(2)">
                        Previous
                    </button>
                    <button type="submit" class="btn btn-nk-primary">
                        Create Account
                    </button>
                </div>
            </div>
        </form>
    </div>

   <div class="nk-auth-footer mt-4" style="line-height: 1.5;">
    Open Hands App is a service provided by Friends of Diversity. <br>
    Copyright &copy; 2026 Friends of Diversity. All rights reserved.
</div>
</div>

{{-- ============================= --}}
{{-- SUCCESS MODAL --}}
{{-- ============================= --}}
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div style="font-size: 48px; line-height: 1;">🎉</div>
                <h5 class="mt-3 mb-3">Account Created Successfully!</h5>

                <p style="font-size: 14px; color: var(--muted);">
                    Welcome to Open Hands 💜<br>
                    You're taking a strong step toward support and healing.
                </p>

                <p style="font-size: 14px;">
                    <strong>Username:</strong> <span id="modalUsername" class="text-warning"></span>
                </p>

                <button type="button" class="btn btn-nk-primary w-100 mt-3" onclick="goToHome()">
                    Thank You 🙏
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================= --}}
{{-- SCRIPTS --}}
{{-- ============================= --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
/* ==========================================================
   STEP NAVIGATION
   ========================================================== */
let currentStep = 1;

function showStep(step) {
    document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
    const target = document.getElementById('step' + step);
    if (target) target.classList.add('active');

    document.querySelectorAll('.step-item').forEach((el, index) => {
        el.classList.remove('active', 'completed');
        if (index + 1 < step) {
            el.classList.add('completed');
        } else if (index + 1 === step) {
            el.classList.add('active');
        }
    });

    currentStep = step;
}

function validateStep(step) {
    const container = document.getElementById('step' + step);
    if (!container) return true;

    const inputs = container.querySelectorAll('input, select');
    for (const input of inputs) {
        if (!input.checkValidity()) {
            input.reportValidity();
            input.style.borderColor = '#ef4444';
            setTimeout(() => { input.style.borderColor = ''; }, 2000);
            return false;
        }
    }
    return true;
}

function nextStep(step) {
    if (!validateStep(step)) return;
    showStep(step + 1);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    showStep(step);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ==========================================================
   PASSWORD STRENGTH
   ========================================================== */
const passwordInput = document.getElementById('password');
const strengthBar = document.getElementById('strengthBar');
const strengthText = document.getElementById('strengthText');

if (passwordInput) {
    passwordInput.addEventListener('input', function () {
        const val = this.value;
        let strength = 0;

        if (val.length >= 8) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;

        const widths = ['0%', '25%', '50%', '75%', '100%'];
        const labels = ['', 'Very Weak', 'Weak', 'Fair', 'Strong'];
        const colors = ['#c2185b', '#ef4444', '#f59e0b', '#3b82f6', '#22c55e'];

        strengthBar.style.width = widths[strength];
        strengthBar.style.background = colors[strength];
        strengthText.innerText = val ? labels[strength] + ' password' : '';
        strengthText.style.color = colors[strength];
    });
}

/* ==========================================================
   FORM SUBMIT
   ========================================================== */
document.getElementById('registerForm').addEventListener('submit', function (e) {
    // Validate all three steps before submitting
    if (!validateStep(1) || !validateStep(2) || !validateStep(3)) {
        e.preventDefault();
        return;
    }

    // Verify password match
    const pw = document.getElementById('password').value;
    const pwConfirm = document.getElementById('password_confirmation').value;
    if (pw !== pwConfirm) {
        e.preventDefault();
        alert('⚠️ Passwords do not match!');
        return;
    }

    // Verify minimum length
    if (pw.length < 8) {
        e.preventDefault();
        alert('⚠️ Password must be at least 8 characters.');
        return;
    }
});

/* ==========================================================
   SUCCESS MODAL (triggered from server-side via flash or JS)
   ========================================================== */
document.addEventListener('DOMContentLoaded', function () {
    showStep(1);

    @if (session('registration_success'))
        const username = "{{ session('registration_username') ?? '' }}";
        if (username) {
            document.getElementById('modalUsername').innerText = username;
        }

        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();

        // Confetti celebration
        if (typeof confetti === 'function') {
            confetti({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#ff4d8d', '#c2185b', '#2F5DAA', '#22c55e', '#f59e0b']
            });
        }
    @endif
});

/* ==========================================================
   REDIRECT HELPERS
   ========================================================== */
function goToHome() {
    window.location.href = "{{ url('/') }}";
}

function goToLogin() {
    window.location.href = "{{ route('login') }}";
}
</script>
</body>
</html>
@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    :root {
        --bg-card: rgba(255, 255, 255, 0.05);
        --border-glass: rgba(255, 255, 255, 0.1);
        --accent-light: #ff4d8d;
        --accent-dark: #c2185b;
        --text-muted: #94a3b8;
    }

    /* ==========================
       GREETING HERO
    ========================== */
    .dash-greeting {
        background: linear-gradient(135deg, rgba(47, 93, 170, 0.5), rgba(190, 24, 93, 0.35));
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 28px 24px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .dash-greeting::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -80px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 77, 141, 0.25);
        filter: blur(40px);
    }

    .dash-greeting::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: -60px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(47, 93, 170, 0.3);
        filter: blur(40px);
    }

    .dash-greeting-inner {
        position: relative;
        z-index: 2;
    }

    .dash-greeting h4 {
        color: #fff;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 6px;
        letter-spacing: -0.3px;
    }

    .dash-greeting p {
        color: #cbd5f5;
        font-size: 14px;
        margin: 0;
    }

    .dash-greeting .emoji-hi {
        font-size: 30px;
        margin-right: 8px;
    }

    /* ==========================
       STATS CARDS
    ========================== */
    .dash-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .dash-stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 14px;
        padding: 16px 18px;
        backdrop-filter: blur(14px);
        transition: all 0.25s ease;
    }

    .dash-stat-card:hover {
        transform: translateY(-3px);
        border-color: rgba(255, 77, 141, 0.35);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .dash-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(180deg, var(--accent-light), var(--accent-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #fff;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(190, 24, 93, 0.3);
    }

    .dash-stat-icon.blue {
        background: linear-gradient(180deg, #3b82f6, #1e40af);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .dash-stat-icon.green {
        background: linear-gradient(180deg, #22c55e, #15803d);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .dash-stat-icon.purple {
        background: linear-gradient(180deg, #a855f7, #7c3aed);
        box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3);
    }

    .dash-stat-value {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        line-height: 1.1;
    }

    .dash-stat-label {
        color: var(--text-muted);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-top: 4px;
    }

    /* ==========================
       CHECK-IN CARD
    ========================== */
    .checkin-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 24px;
        backdrop-filter: blur(14px);
        margin-bottom: 24px;
    }

    .checkin-card h5 {
        color: #fff;
        font-size: 17px;
        font-weight: 600;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checkin-card .helper {
        color: var(--text-muted);
        font-size: 13px;
        margin-bottom: 18px;
    }

    /* Mood grid */
    .mood-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 8px;
        margin-bottom: 18px;
    }

    .mood-tile {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 12px 6px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .mood-tile:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .mood-tile.selected {
        background: linear-gradient(180deg, rgba(255, 77, 141, 0.25), rgba(194, 24, 91, 0.35));
        border-color: var(--accent-light);
        box-shadow: 0 0 0 2px rgba(255, 77, 141, 0.3), 0 6px 20px rgba(190, 24, 93, 0.35);
    }

    .mood-tile .emoji {
        font-size: 22px;
        display: block;
        margin-bottom: 4px;
    }

    .mood-tile .label {
        font-size: 10px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .mood-tile.selected .label {
        color: #fff;
    }

    .mood-tile .mood-name {
        font-size: 9px;
        color: var(--text-muted);
        opacity: 0.7;
        margin-top: 2px;
    }

    /* Comment textarea */
    .checkin-textarea {
        background: rgba(15, 23, 42, 0.6);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 14px;
        width: 100%;
        min-height: 90px;
        resize: vertical;
        transition: all 0.2s ease;
    }

    .checkin-textarea:focus {
        background: rgba(15, 23, 42, 0.85);
        border-color: var(--accent-light);
        box-shadow: 0 0 0 3px rgba(255, 77, 141, 0.15);
        outline: none;
        color: #fff;
    }

    .checkin-textarea::placeholder {
        color: #64748b;
    }

    .char-counter {
        font-size: 11px;
        color: var(--text-muted);
        text-align: right;
        margin-top: 4px;
    }

    /* Submit button */
    .btn-submit-checkin {
        background: linear-gradient(180deg, var(--accent-light), var(--accent-dark));
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 14px;
        width: 100%;
        margin-top: 16px;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-submit-checkin:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(190, 24, 93, 0.4);
        color: #fff;
    }

    .btn-submit-checkin:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* ==========================
       RECENT CHECK-INS
    ========================== */
    .recent-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 24px;
        backdrop-filter: blur(14px);
    }

    .recent-card h5 {
        color: #fff;
        font-size: 17px;
        font-weight: 600;
        margin-bottom: 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .recent-card .see-all {
        font-size: 12px;
        color: var(--accent-light);
        text-decoration: none;
        font-weight: 500;
    }

    .recent-card .see-all:hover {
        color: var(--accent-light);
        text-decoration: underline;
    }

    .recent-card .helper {
        color: var(--text-muted);
        font-size: 13px;
        margin-bottom: 16px;
    }

    .checkin-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .checkin-item:last-child {
        border-bottom: none;
    }

    .checkin-item .mood-badge {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .checkin-item .content {
        flex: 1;
        min-width: 0;
    }

    .checkin-item .mood-name-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 6px;
        margin-bottom: 4px;
    }

    .checkin-item .comment-text {
        color: #e2e8f0;
        font-size: 13px;
        line-height: 1.5;
        margin: 0;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .checkin-item .time {
        color: var(--text-muted);
        font-size: 11px;
        margin-top: 4px;
    }

    .empty-state {
        text-align: center;
        padding: 30px 20px;
        color: var(--text-muted);
    }

    .empty-state .icon {
        font-size: 42px;
        opacity: 0.5;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 13px;
        margin: 0;
    }

    /* ==========================
       QUICK ACTIONS
    ========================== */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-top: 24px;
    }

    .quick-action {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 14px;
        padding: 16px;
        text-align: center;
        text-decoration: none;
        color: var(--text-muted);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .quick-action:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--accent-light);
        color: #fff;
        transform: translateY(-3px);
    }

    .quick-action .qa-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(180deg, var(--accent-light), var(--accent-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #fff;
    }

    .quick-action span {
        font-size: 12px;
        font-weight: 500;
    }

    /* ==========================
       SUCCESS TOAST
    ========================== */
    .dash-toast {
        display: none; /* Hidden by default */
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(120%);
        background: linear-gradient(180deg, #22c55e, #16a34a);
        color: #fff;
        padding: 14px 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        z-index: 9999;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        align-items: center;
        gap: 10px;
    }

    .dash-toast.show {
        display: flex; /* Show when active */
        transform: translateX(-50%) translateY(0);
    }

    @media (max-width: 576px) {
        .dash-greeting h4 { font-size: 20px; }
        .mood-grid { grid-template-columns: repeat(4, 1fr); }
        .checkin-card, .recent-card { padding: 18px; }
    }
</style>
@endpush

@section('content')
@php
    use Illuminate\Support\Facades\DB;
    use Carbon\Carbon;

    $user = auth()->user();
    $hour = (int) now()->format('G');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $greetingEmoji = $hour < 12 ? '🌅' : ($hour < 18 ? '☀️' : '🌙');

    // Recent check-ins
    $recentCheckins = $user->checkins()
        ->latest()
        ->take(5)
        ->get();

    // Stats
    $totalCheckins = $user->checkins()->count();
    $thisMonthCheckins = $user->checkins()
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    // Streak
    $streak = 0;
    $checkinDates = $user->checkins()
        ->selectRaw('DATE(created_at) as d')
        ->distinct()
        ->orderByDesc('d')
        ->pluck('d')
        ->toArray();

    if (!empty($checkinDates)) {
        $cursor = Carbon::today();
        if ($checkinDates[0] !== $cursor->toDateString()) {
            $cursor->subDay();
        }
        foreach ($checkinDates as $date) {
            if ($date === $cursor->toDateString()) {
                $streak++;
                $cursor->subDay();
            } else {
                break;
            }
        }
    }

    // Latest risk
    $latestScreening = $user->screenings()->latest()->first();
    $riskLevel = $latestScreening->risk_level ?? 'low';

    // Mood palette
    $moods = [
        ['emoji' => '😊', 'label' => 'Happy',    'value' => '😊 Happy',    'color' => '#22c55e'],
        ['emoji' => '😄', 'label' => 'Excited',  'value' => '😄 Excited',  'color' => '#10b981'],
        ['emoji' => '🙂', 'label' => 'Content',  'value' => '🙂 Content',  'color' => '#14b8a6'],
        ['emoji' => '😌', 'label' => 'Relaxed',  'value' => '😌 Relaxed',  'color' => '#06b6d4'],
        ['emoji' => '😍', 'label' => 'Loved',    'value' => '😍 Loved',    'color' => '#ec4899'],
        ['emoji' => '😎', 'label' => 'Confident','value' => '😎 Confident','color' => '#8b5cf6'],
        ['emoji' => '🤔', 'label' => 'Thoughtful','value'=> '🤔 Thoughtful','color'=>'#a78bfa'],
        ['emoji' => '😐', 'label' => 'Neutral',  'value' => '😐 Neutral',  'color' => '#94a3b8'],
        ['emoji' => '😕', 'label' => 'Confused', 'value' => '😕 Confused', 'color' => '#fbbf24'],
        ['emoji' => '😟', 'label' => 'Worried',  'value' => '😟 Worried',  'color' => '#f59e0b'],
        ['emoji' => '😢', 'label' => 'Sad',      'value' => '😢 Sad',      'color' => '#3b82f6'],
        ['emoji' => '😡', 'label' => 'Angry',    'value' => '😡 Angry',    'color' => '#ef4444'],
        ['emoji' => '😩', 'label' => 'Stressed', 'value' => '😩 Stressed', 'color' => '#dc2626'],
        ['emoji' => '😴', 'label' => 'Tired',    'value' => '😴 Tired',    'color' => '#6366f1'],
        ['emoji' => '😰', 'label' => 'Anxious',  'value' => '😰 Anxious',  'color' => '#f97316'],
        ['emoji' => '🥺', 'label' => 'Overwhelm','value' => '🥺 Overwhelmed','color'=>'#c026d3'],
    ];

    function moodColorFor($label) {
        return match (true) {
            str_contains($label, 'Happy'),
            str_contains($label, 'Excited'),
            str_contains($label, 'Content') => '#22c55e',
            str_contains($label, 'Loved'),
            str_contains($label, 'Confident') => '#ec4899',
            str_contains($label, 'Sad'),
            str_contains($label, 'Tired') => '#3b82f6',
            str_contains($label, 'Angry'),
            str_contains($label, 'Stressed'),
            str_contains($label, 'Anxious') => '#ef4444',
            default => '#94a3b8',
        };
    }

    function moodEmojiFor($label) {
        $parts = preg_split('/\s+/u', trim($label));
        return $parts[0] ?? '😐';
    }
@endphp

{{-- =====================
     GREETING HERO
===================== --}}
<div class="dash-greeting">
    <div class="dash-greeting-inner">
        <h4>
            <span class="emoji-hi">{{ $greetingEmoji }}</span>
            {{ $greeting }}, <span style="color: #ff4d8d;">{{ $user->username }}</span>
        </h4>
        <p>
            @if($totalCheckins === 0)
                Let's start your wellness journey — how are you feeling right now?
            @elseif($streak >= 3)
                You're on a <strong style="color:#22c55e;">{{ $streak }}-day streak</strong> 🔥 — keep it up!
            @elseif($riskLevel === 'high')
                You matter. Please reach out to one of our partners if you need support.
            @else
                Take a moment to check in with yourself today.
            @endif
        </p>
    </div>
</div>

{{-- =====================
     STATS CARDS
===================== --}}
<div class="dash-stats">
    <div class="dash-stat-card">
        <div class="dash-stat-icon">
            <i class="bi bi-journal-check"></i>
        </div>
        <div class="dash-stat-value">{{ $totalCheckins }}</div>
        <div class="dash-stat-label">Total Check-ins</div>
    </div>

    <div class="dash-stat-card">
        <div class="dash-stat-icon blue">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div class="dash-stat-value">{{ $thisMonthCheckins }}</div>
        <div class="dash-stat-label">This Month</div>
    </div>

    <div class="dash-stat-card">
        <div class="dash-stat-icon green">
            <i class="bi bi-fire"></i>
        </div>
        <div class="dash-stat-value">{{ $streak }}</div>
        <div class="dash-stat-label">Day Streak</div>
    </div>

    <div class="dash-stat-card">
        <div class="dash-stat-icon purple">
            <i class="bi bi-shield-check"></i>
        </div>
        <div class="dash-stat-value" style="text-transform: capitalize; font-size: 18px;">
            {{ $riskLevel }}
        </div>
        <div class="dash-stat-label">Risk Level</div>
    </div>
</div>

{{-- =====================
     CHECK-IN FORM
===================== --}}
<div class="checkin-card">
    <h5>
        <i class="bi bi-heart-pulse-fill" style="color: #ff4d8d;"></i>
        How are you feeling today?
    </h5>
    <p class="helper">Pick the mood that best describes you right now.</p>

    {{-- Mood grid --}}
    <div class="mood-grid" id="moodGrid">
        @foreach($moods as $mood)
            <div class="mood-tile"
                 data-value="{{ $mood['value'] }}"
                 onclick="selectMood(this)">
                <span class="emoji">{{ $mood['emoji'] }}</span>
                <span class="label">{{ $mood['label'] }}</span>
            </div>
        @endforeach
    </div>

    {{-- Comment --}}
    <textarea
        id="comment"
        class="checkin-textarea"
        placeholder="Write your thoughts... (What's on your mind?)"
        maxlength="1000"
        oninput="updateCounter(this)"></textarea>
    <div class="char-counter"><span id="charCount">0</span> / 1000</div>

    {{-- Submit --}}
    <button
        type="button"
        id="submitBtn"
        class="btn-submit-checkin"
        onclick="submitCheckin()">
        <i class="bi bi-check-circle-fill"></i>
        <span>Submit Check-in</span>
    </button>
</div>

{{-- =====================
     RECENT CHECK-INS
===================== --}}
<div class="recent-card">
    <h5>
        <span><i class="bi bi-clock-history" style="color: #ff4d8d;"></i> Recent Check-ins</span>
        @if($recentCheckins->count() > 0)
            <a href="{{ route('checkins.index') }}" class="see-all">See all →</a>
        @endif
    </h5>
    <p class="helper">Your last few mood entries.</p>

    @forelse($recentCheckins as $checkin)
        @php
            $color = moodColorFor($checkin->mood);
            $emoji = moodEmojiFor($checkin->mood);
            $label = trim(preg_replace('/^[^\p{L}]+/u', '', $checkin->mood));
        @endphp
        <div class="checkin-item">
            <div class="mood-badge" style="background: {{ $color }}22; border: 1px solid {{ $color }}55;">
                <span>{{ $emoji }}</span>
            </div>
            <div class="content">
                <span class="mood-name-badge" style="background: {{ $color }}33; color: {{ $color }};">
                    {{ $label }}
                </span>
                <p class="comment-text">{{ $checkin->comment }}</p>
                <div class="time">
                    <i class="bi bi-clock" style="font-size: 10px;"></i>
                    {{ $checkin->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="icon">🌱</div>
            <p>No check-ins yet. Your first entry starts your journey.</p>
        </div>
    @endforelse
</div>

{{-- =====================
     QUICK ACTIONS
===================== --}}
<div class="quick-actions">
    <a href="{{ route('screening') }}" class="quick-action">
        <div class="qa-icon"><i class="bi bi-clipboard-heart"></i></div>
        <span>Take Screening</span>
    </a>
    <a href="{{ route('tracker') }}" class="quick-action">
        <div class="qa-icon"><i class="bi bi-graph-up-arrow"></i></div>
        <span>Mood Tracker</span>
    </a>
    <a href="{{ route('organizations') }}" class="quick-action">
        <div class="qa-icon"><i class="bi bi-people-fill"></i></div>
        <span>Find Support</span>
    </a>
    <a href="{{ route('wellness.tools') }}" class="quick-action">
        <div class="qa-icon"><i class="bi bi-heart-pulse-fill"></i></div>
        <span>Wellness Tools</span>
    </a>
</div>

{{-- =====================
     SUCCESS TOAST
===================== --}}
<div id="dashToast" class="dash-toast">
    <i class="bi bi-check-circle-fill"></i>
    <span id="dashToastText">Check-in saved!</span>
</div>
@endsection

@push('scripts')
<script>
    let selectedMood = '';

    function selectMood(el) {
        document.querySelectorAll('.mood-tile').forEach(t => t.classList.remove('selected'));
        el.classList.add('selected');
        selectedMood = el.dataset.value;
    }

    function updateCounter(el) {
        document.getElementById('charCount').textContent = el.value.length;
    }

    function showToast(message) {
        const toast = document.getElementById('dashToast');
        document.getElementById('dashToastText').textContent = message;
        
        toast.style.display = 'flex'; // Show the toast
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.style.display = 'none', 400); // Hide after animation
        }, 3000);
    }

    async function submitCheckin() {
        const comment = document.getElementById('comment').value.trim();
        const btn = document.getElementById('submitBtn');
        const btnLabel = btn.querySelector('span');

        if (!selectedMood) {
            showToast('Please select a mood first');
            return;
        }
        if (!comment) {
            showToast('Please write a short comment');
            return;
        }

        btn.disabled = true;
        btnLabel.textContent = 'Saving...';

        try {
            const res = await fetch('{{ route("checkin.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ mood: selectedMood, comment }),
            });

            const data = await res.json();

            if (res.ok && data.status === 'success') {
                showToast('Check-in saved!');

                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 80,
                        spread: 60,
                        origin: { y: 0.7 },
                        colors: ['#ff4d8d', '#c2185b', '#2F5DAA', '#22c55e'],
                    });
                }

                document.getElementById('comment').value = '';
                document.getElementById('charCount').textContent = '0';
                document.querySelectorAll('.mood-tile').forEach(t => t.classList.remove('selected'));
                selectedMood = '';

                setTimeout(() => window.location.reload(), 1400);
            } else {
                showToast(data.message || 'Could not save check-in');
            }
        } catch (err) {
            showToast('Connection error. Try again.');
        } finally {
            btn.disabled = false;
            btnLabel.textContent = 'Submit Check-in';
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
@endpush
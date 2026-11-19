@extends('layouts.app')

@section('title', 'Wellness Tools')

@push('styles')
<style>
    .wt-wrap { max-width: 1100px; margin: 0 auto; }

    .wt-title {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .wt-subtitle {
        color: #94a3b8;
        font-size: 13px;
        margin-bottom: 22px;
    }

    .wt-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 18px;
    }

    .wt-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 18px;
        padding: 22px;
        display: flex;
        flex-direction: column;
        min-height: 340px;
    }

    .wt-card h5 {
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ========== BUTTONS ========== */
    .wt-btn {
        padding: 11px 20px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
    }

    .wt-btn-primary {
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        color: #fff;
    }

    .wt-btn-primary:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(190, 24, 93, 0.35);
    }

    .wt-btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .wt-btn-danger {
        background: linear-gradient(180deg, #ef4444, #b91c1c);
        color: #fff;
    }

    .wt-btn-danger:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.35);
    }

    .wt-btn-secondary {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #fff;
    }

    .wt-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
    }

    /* ========== FORM CONTROLS ========== */
    .wt-input,
    .wt-select,
    .wt-textarea {
        width: 100%;
        background: #0f172a !important;
        border: 1px solid #253a5c;
        color: #fff !important;
        border-radius: 10px;
        padding: 11px 14px;
        font-size: 13.5px;
        transition: all 0.2s ease;
    }

    .wt-input:focus,
    .wt-select:focus,
    .wt-textarea:focus {
        outline: none;
        border-color: #ff4d8d;
        box-shadow: 0 0 0 3px rgba(255, 77, 141, 0.15);
        background: #0f172a !important;
        color: #fff !important;
    }

    .wt-input::placeholder,
    .wt-textarea::placeholder {
        color: #64748b;
    }

    .wt-textarea {
        min-height: 80px;
        resize: vertical;
    }

    /* ========== BREATHING ========== */
    .breath-stage {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 180px;
        position: relative;
    }

    .breath-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, #a855f7, #7b2cbf);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        box-shadow: 0 0 40px rgba(123, 44, 191, 0.5);
        transition: transform 4s ease-in-out, box-shadow 4s ease-in-out;
        padding: 8px;
        line-height: 1.3;
    }

    .breath-circle.inhale {
        transform: scale(1.6);
        box-shadow: 0 0 60px rgba(123, 44, 191, 0.8);
    }

    .breath-circle.hold {
        transform: scale(1.6);
        box-shadow: 0 0 80px rgba(168, 85, 247, 0.9);
    }

    .breath-circle.exhale {
        transform: scale(1);
        box-shadow: 0 0 30px rgba(123, 44, 191, 0.3);
    }

    /* ========== ARTICLES ========== */
    .article-list {
        flex: 1;
        max-height: 300px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .article-item {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 14px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .article-item:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 77, 141, 0.3);
        transform: translateX(3px);
    }

    .article-item h6 {
        color: #fff;
        font-size: 13.5px;
        font-weight: 600;
        margin: 0 0 4px;
    }

    .article-item p {
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.5;
        margin: 0;
    }

    /* ========== JOURNAL ========== */
    .journal-entries {
        flex: 1;
        max-height: 160px;
        overflow-y: auto;
        margin-top: 12px;
        padding-right: 4px;
    }

    .journal-entry {
        background: rgba(255, 255, 255, 0.05);
        border-left: 3px solid #ff4d8d;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 8px;
        font-size: 12.5px;
    }

    .journal-entry .entry-prompt {
        color: #ff4d8d;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .journal-entry .entry-text {
        color: #e2e8f0;
        line-height: 1.5;
    }

    .journal-entry .entry-time {
        color: #64748b;
        font-size: 10.5px;
        margin-top: 4px;
    }

    /* ========== MEDITATION ========== */
    .med-display {
        background: rgba(123, 44, 191, 0.15);
        border: 1px solid rgba(123, 44, 191, 0.3);
        border-radius: 14px;
        padding: 24px;
        text-align: center;
        margin-bottom: 14px;
    }

    .med-time {
        color: #fff;
        font-size: 42px;
        font-weight: 700;
        font-family: 'Courier New', monospace;
        letter-spacing: 2px;
        line-height: 1;
    }

    .med-label {
        color: #a78bfa;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-top: 6px;
    }

    /* ========== HABITS ========== */
    .habit-list {
        flex: 1;
        max-height: 200px;
        overflow-y: auto;
        margin-top: 10px;
    }

    .habit-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }

    .habit-row:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .habit-check {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.35);
        color: #22c55e;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .habit-check:hover {
        background: rgba(34, 197, 94, 0.25);
        transform: scale(1.05);
    }

    .habit-check.done {
        background: #22c55e;
        color: #fff;
    }

    .habit-info { flex: 1; min-width: 0; }

    .habit-name {
        color: #fff;
        font-size: 13px;
        font-weight: 500;
    }

    .habit-streak {
        color: #94a3b8;
        font-size: 11px;
        margin-top: 2px;
    }

    .habit-delete {
        background: transparent;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 4px;
        font-size: 14px;
        transition: color 0.2s;
    }

    .habit-delete:hover { color: #ef4444; }

    .empty-state {
        text-align: center;
        color: #94a3b8;
        font-size: 12.5px;
        padding: 20px 10px;
    }

    .empty-state .emoji { font-size: 28px; opacity: 0.5; display: block; margin-bottom: 6px; }

    /* ========== MODAL (for reading articles) ========== */
    .modal-content {
        background: rgba(27, 42, 71, 0.98) !important;
        backdrop-filter: blur(16px);
        color: #fff;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .modal-title { color: #fff; }
    .modal-header { border-color: rgba(255, 255, 255, 0.1); }
    .btn-close { filter: invert(1); opacity: 0.8; }

    .article-body {
        color: #cbd5f5;
        font-size: 14px;
        line-height: 1.7;
    }

    .article-body h4 {
        color: #fff;
        font-size: 16px;
        margin: 18px 0 10px;
    }

    .article-body h4:first-child { margin-top: 0; }

    .article-body p { margin-bottom: 12px; }

    .article-body ul { padding-left: 20px; margin-bottom: 12px; }
    .article-body li { margin-bottom: 6px; }

    @media (max-width: 576px) {
        .wt-card { padding: 16px; min-height: 300px; }
        .med-time { font-size: 32px; }
        .breath-circle { width: 100px; height: 100px; }
    }
</style>
@endpush

@section('content')
<div class="wt-wrap">
    <h3 class="wt-title">🧠 Wellness Tools</h3>
    <p class="wt-subtitle">Small daily practices that make a big difference.</p>

    <div class="wt-grid">

        {{-- ============ BREATHING ============ --}}
        <div class="wt-card">
            <h5><i class="bi bi-lungs" style="color:#a855f7;"></i> Breathing Exercise</h5>

            <div class="breath-stage">
                <div id="breathCircle" class="breath-circle">Ready?</div>
            </div>

            <button type="button"
                    id="breathBtn"
                    class="wt-btn wt-btn-primary mt-3"
                    onclick="toggleBreathing()">
                <i class="bi bi-play-fill"></i> Start Breathing
            </button>
        </div>

        {{-- ============ ARTICLES ============ --}}
        <div class="wt-card">
            <h5><i class="bi bi-book" style="color:#22c55e;"></i> Wellness Articles</h5>

            <div class="article-list">
                <div class="article-item" onclick="openArticle('stress')">
                    <h6>💙 Coping with Stress</h6>
                    <p>Simple breathing and grounding techniques to manage daily stress.</p>
                </div>
                <div class="article-item" onclick="openArticle('habits')">
                    <h6>🌱 Building Healthy Habits</h6>
                    <p>Small daily habits that transform your mental health over time.</p>
                </div>
                <div class="article-item" onclick="openArticle('mindfulness')">
                    <h6>🧘 Mindfulness Basics</h6>
                    <p>Learn how mindfulness reduces anxiety and improves focus.</p>
                </div>
            </div>
        </div>

        {{-- ============ JOURNAL ============ --}}
        <div class="wt-card">
            <h5><i class="bi bi-journal-text" style="color:#f59e0b;"></i> Journal</h5>

            <select id="journalPrompt" class="wt-select mb-2">
                <option>What made you feel stressed today?</option>
                <option>What are you grateful for today?</option>
                <option>What made you happy today?</option>
                <option>What do you want to let go of?</option>
            </select>

            <textarea id="journalText"
                      class="wt-textarea mb-2"
                      placeholder="Write your thoughts..."></textarea>

            <button type="button" class="wt-btn wt-btn-primary" onclick="saveJournal()">
                <i class="bi bi-save"></i> Save Entry
            </button>

            <div id="journalEntries" class="journal-entries"></div>
        </div>

        {{-- ============ MEDITATION ============ --}}
        <div class="wt-card">
            <h5><i class="bi bi-headphones" style="color:#06b6d4;"></i> Meditation Timer</h5>

            <div class="med-display">
                <div id="medTime" class="med-time">00:00</div>
                <div class="med-label">Meditation</div>
            </div>

            <input type="number"
                   id="medMinutes"
                   class="wt-input mb-2"
                   placeholder="Minutes (e.g. 5)"
                   min="1"
                   max="60"
                   value="5">

            <div style="display: flex; gap: 8px;">
                <button type="button" id="medStartBtn" class="wt-btn wt-btn-primary" onclick="startMeditation()">
                    <i class="bi bi-play-fill"></i> Start
                </button>
                <button type="button" id="medStopBtn" class="wt-btn wt-btn-danger" onclick="stopMeditation()" style="display:none;">
                    <i class="bi bi-stop-fill"></i> Stop
                </button>
            </div>

            {{-- Optional audio — place file in public/audio/flying.mp3 --}}
            <audio id="meditationAudio" loop preload="none">
                <source src="{{ asset('audio/flying.mp3') }}" type="audio/mpeg">
            </audio>
        </div>

        {{-- ============ HABITS ============ --}}
        <div class="wt-card">
            <h5><i class="bi bi-check2-circle" style="color:#ec4899;"></i> Habit Tracker</h5>

            <input type="text"
                   id="habitInput"
                   class="wt-input mb-2"
                   placeholder="Add habit (e.g. Drink water)"
                   onkeydown="if(event.key==='Enter'){event.preventDefault();addHabit();}">

            <button type="button" class="wt-btn wt-btn-primary mb-2" onclick="addHabit()">
                <i class="bi bi-plus-lg"></i> Add Habit
            </button>

            <div id="habitList" class="habit-list"></div>
        </div>

    </div>
</div>

{{-- ============ ARTICLE MODAL ============ --}}
<div class="modal fade" id="articleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="articleTitle">Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body article-body" id="articleBody"></div>
            <div class="modal-footer" style="border-color: rgba(255,255,255,0.1);">
                <button type="button" class="wt-btn wt-btn-primary" data-bs-dismiss="modal" style="width:auto; padding: 10px 24px;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ============================================================
       BREATHING EXERCISE
    ============================================================ */
    let breathingActive = false;
    let breathingTimeout = null;

    window.toggleBreathing = function () {
        const circle = document.getElementById('breathCircle');
        const btn    = document.getElementById('breathBtn');

        if (breathingActive) {
            // Stop
            breathingActive = false;
            clearTimeout(breathingTimeout);
            circle.classList.remove('inhale', 'hold', 'exhale');
            circle.textContent = 'Ready?';
            btn.innerHTML = '<i class="bi bi-play-fill"></i> Start Breathing';
            return;
        }

        // Start
        breathingActive = true;
        btn.innerHTML = '<i class="bi bi-stop-fill"></i> Stop';

        function cycle() {
            if (!breathingActive) return;

            // Inhale
            circle.textContent = 'Inhale...';
            circle.classList.remove('hold', 'exhale');
            circle.classList.add('inhale');

            breathingTimeout = setTimeout(() => {
                if (!breathingActive) return;

                // Hold
                circle.textContent = 'Hold...';
                circle.classList.remove('inhale', 'exhale');
                circle.classList.add('hold');

                breathingTimeout = setTimeout(() => {
                    if (!breathingActive) return;

                    // Exhale
                    circle.textContent = 'Exhale...';
                    circle.classList.remove('inhale', 'hold');
                    circle.classList.add('exhale');

                    breathingTimeout = setTimeout(cycle, 4000);
                }, 4000);
            }, 4000);
        }

        cycle();
    };

    /* ============================================================
       ARTICLES
    ============================================================ */
    const ARTICLES = {
        stress: {
            title: '💙 Coping with Stress',
            body: `
                <p>Stress is your body's natural response to pressure. When it becomes chronic, it can affect your sleep, mood, and physical health. Here are proven techniques to bring it back under control.</p>

                <h4>1. The 4-7-8 Breathing</h4>
                <p>Inhale for 4 seconds, hold for 7, exhale for 8. Repeat 4 times. This activates your parasympathetic nervous system — the "rest and digest" mode — and calms your body within minutes.</p>

                <h4>2. The 5-4-3-2-1 Grounding</h4>
                <p>When anxiety spikes, notice:</p>
                <ul>
                    <li><strong>5 things</strong> you can see</li>
                    <li><strong>4 things</strong> you can touch</li>
                    <li><strong>3 things</strong> you can hear</li>
                    <li><strong>2 things</strong> you can smell</li>
                    <li><strong>1 thing</strong> you can taste</li>
                </ul>
                <p>This anchors you in the present moment and interrupts the spiral.</p>

                <h4>3. Move Your Body</h4>
                <p>Even a 10-minute walk releases endorphins and reduces cortisol. Movement doesn't have to be intense to work.</p>

                <h4>4. Set Boundaries</h4>
                <p>Stress often comes from saying yes to too much. Practice saying: <em>"Let me get back to you"</em> instead of an automatic yes.</p>

                <h4>5. Reach Out</h4>
                <p>You are not meant to carry everything alone. Talk to a trusted friend, family member, or use the Directory to reach a counselor.</p>
            `,
        },
        habits: {
            title: '🌱 Building Healthy Habits',
            body: `
                <p>Habits are the compound interest of self-improvement. Small actions done consistently create remarkable change over time.</p>

                <h4>1. Start Absurdly Small</h4>
                <p>Want to meditate? Start with 1 minute. Want to exercise? Do 1 push-up. The goal is to make the action so easy you can't say no.</p>

                <h4>2. Habit Stacking</h4>
                <p>Attach the new habit to an existing one: <em>"After I pour my morning coffee, I will write one sentence in my journal."</em></p>

                <h4>3. Design Your Environment</h4>
                <p>Make good habits obvious. Want to read more? Put a book on your pillow. Want to drink water? Fill a bottle and set it on your desk.</p>

                <h4>4. Track Your Streaks</h4>
                <p>Use the Habit Tracker on this page. Seeing a streak grow is one of the most powerful motivators there is.</p>

                <h4>5. Forgive the Miss</h4>
                <p>Missing one day isn't the problem — missing two is. The rule: <strong>never miss twice</strong>. One slip is a bump; two is a new pattern.</p>
            `,
        },
        mindfulness: {
            title: '🧘 Mindfulness Basics',
            body: `
                <p>Mindfulness is the practice of paying attention to the present moment, without judgment. It sounds simple — and it is — but it takes practice.</p>

                <h4>1. Watch Your Breath</h4>
                <p>Sit comfortably. Close your eyes. Notice the sensation of breathing — air entering your nose, your chest rising, your abdomen expanding. When your mind wanders (it will), gently bring it back. That moment of noticing <em>is</em> mindfulness.</p>

                <h4>2. Body Scan</h4>
                <p>Starting at your toes and moving up to the crown of your head, notice sensations in each area of your body. No need to change anything — just notice.</p>

                <h4>3. Mindful Eating</h4>
                <p>Take one bite of food and eat it slowly. Notice the texture, temperature, taste, and smell. This is mindfulness in the middle of ordinary life.</p>

                <h4>4. The Mind Wanders — That's Okay</h4>
                <p>You will not "clear your mind." Trying to force silence usually backfires. Instead, notice the thought, label it ("thinking"), and return to your breath.</p>

                <h4>5. Start with 3 Minutes</h4>
                <p>Consistency beats duration. Three minutes daily for a month is more powerful than one hour-long session.</p>
            `,
        },
    };

    window.openArticle = function (key) {
        const article = ARTICLES[key];
        if (!article) return;

        document.getElementById('articleTitle').textContent = article.title;
        document.getElementById('articleBody').innerHTML = article.body;

        new bootstrap.Modal(document.getElementById('articleModal')).show();
    };

    /* ============================================================
       JOURNAL
    ============================================================ */
    const JOURNAL_KEY = 'oh_journal_entries';

    function loadJournal() {
        const entries = JSON.parse(localStorage.getItem(JOURNAL_KEY) || '[]');
        const list = document.getElementById('journalEntries');

        if (entries.length === 0) {
            list.innerHTML = `<div class="empty-state">
                <span class="emoji">📝</span>
                No entries yet. Write your first thought above.
            </div>`;
            return;
        }

        list.innerHTML = entries.slice(0, 10).map(e => `
            <div class="journal-entry">
                <div class="entry-prompt">${escapeHtml(e.prompt)}</div>
                <div class="entry-text">${escapeHtml(e.text)}</div>
                <div class="entry-time">${escapeHtml(e.time)}</div>
            </div>
        `).join('');
    }

    window.saveJournal = function () {
        const text = document.getElementById('journalText').value.trim();
        const prompt = document.getElementById('journalPrompt').value;

        if (!text) {
            document.getElementById('journalText').focus();
            return;
        }

        const entries = JSON.parse(localStorage.getItem(JOURNAL_KEY) || '[]');
        entries.unshift({
            prompt,
            text,
            time: new Date().toLocaleString(),
        });

        // Keep only the last 50 entries
        localStorage.setItem(JOURNAL_KEY, JSON.stringify(entries.slice(0, 50)));

        document.getElementById('journalText').value = '';
        loadJournal();
    };

    /* ============================================================
       MEDITATION TIMER
    ============================================================ */
    let medInterval = null;
    let medSecondsLeft = 0;

    function formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }

    window.startMeditation = function () {
        const minutes = parseInt(document.getElementById('medMinutes').value, 10);
        if (!minutes || minutes < 1 || minutes > 60) {
            alert('Enter a valid number of minutes (1–60)');
            return;
        }

        const audio = document.getElementById('meditationAudio');

        // Reset
        clearInterval(medInterval);
        medSecondsLeft = minutes * 60;

        document.getElementById('medTime').textContent = formatTime(medSecondsLeft);
        document.getElementById('medStartBtn').style.display = 'none';
        document.getElementById('medStopBtn').style.display = 'flex';

        // Play audio (silently ignore if file missing)
        try {
            audio.currentTime = 0;
            audio.play().catch(() => {});
        } catch (e) {}

        medInterval = setInterval(() => {
            medSecondsLeft--;
            document.getElementById('medTime').textContent = formatTime(Math.max(0, medSecondsLeft));

            if (medSecondsLeft <= 0) {
                clearInterval(medInterval);
                medInterval = null;
                audio.pause();
                document.getElementById('medTime').textContent = 'Done 🧘';
                document.getElementById('medStartBtn').style.display = 'flex';
                document.getElementById('medStopBtn').style.display = 'none';

                // Gentle alert after a beat
                setTimeout(() => alert('Meditation complete 🧘'), 200);
            }
        }, 1000);
    };

    window.stopMeditation = function () {
        clearInterval(medInterval);
        medInterval = null;

        const audio = document.getElementById('meditationAudio');
        try {
            audio.pause();
            audio.currentTime = 0;
        } catch (e) {}

        document.getElementById('medTime').textContent = '00:00';
        document.getElementById('medStartBtn').style.display = 'flex';
        document.getElementById('medStopBtn').style.display = 'none';
    };

    /* ============================================================
       HABITS
    ============================================================ */
    const HABITS_KEY = 'oh_habits';

    function todayString() {
        return new Date().toDateString();
    }

    function loadHabits() {
        const habits = JSON.parse(localStorage.getItem(HABITS_KEY) || '[]');
        const list = document.getElementById('habitList');

        if (habits.length === 0) {
            list.innerHTML = `<div class="empty-state">
                <span class="emoji">🎯</span>
                No habits yet. Add your first one above.
            </div>`;
            return;
        }

        const today = todayString();

        list.innerHTML = habits.map((h, i) => {
            const doneToday = h.lastDone === today;
            return `
                <div class="habit-row">
                    <button type="button"
                            class="habit-check ${doneToday ? 'done' : ''}"
                            onclick="markHabitDone(${i})"
                            title="${doneToday ? 'Done today' : 'Mark as done'}">
                        ${doneToday ? '<i class="bi bi-check-lg"></i>' : ''}
                    </button>
                    <div class="habit-info">
                        <div class="habit-name">${escapeHtml(h.name)}</div>
                        <div class="habit-streak">🔥 ${h.streak || 0}-day streak</div>
                    </div>
                    <button type="button"
                            class="habit-delete"
                            onclick="deleteHabit(${i})"
                            title="Delete">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            `;
        }).join('');
    }

    window.addHabit = function () {
        const input = document.getElementById('habitInput');
        const name = input.value.trim();
        if (!name) return;

        const habits = JSON.parse(localStorage.getItem(HABITS_KEY) || '[]');
        habits.push({ name, streak: 0, lastDone: null });
        localStorage.setItem(HABITS_KEY, JSON.stringify(habits));

        input.value = '';
        loadHabits();
    };

    window.markHabitDone = function (index) {
        const habits = JSON.parse(localStorage.getItem(HABITS_KEY) || '[]');
        const today = todayString();

        if (habits[index].lastDone !== today) {
            habits[index].streak = (habits[index].streak || 0) + 1;
            habits[index].lastDone = today;
            localStorage.setItem(HABITS_KEY, JSON.stringify(habits));
        }

        loadHabits();
    };

    window.deleteHabit = function (index) {
        if (!confirm('Delete this habit?')) return;

        const habits = JSON.parse(localStorage.getItem(HABITS_KEY) || '[]');
        habits.splice(index, 1);
        localStorage.setItem(HABITS_KEY, JSON.stringify(habits));
        loadHabits();
    };

    /* ============================================================
       UTIL
    ============================================================ */
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    /* ============================================================
       INIT
    ============================================================ */
    document.addEventListener('DOMContentLoaded', () => {
        loadJournal();
        loadHabits();
    });
})();
</script>
@endpush
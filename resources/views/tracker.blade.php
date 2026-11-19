@extends('layouts.app')

@section('title', 'Mood Tracker')

@push('styles')
<style>
    .trk-wrap { max-width: 900px; margin: 0 auto; }

    .trk-title {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .trk-subtitle {
        color: #94a3b8;
        font-size: 13px;
        margin-bottom: 20px;
    }

    /* Filter tabs */
    .trk-filters {
        display: flex;
        gap: 8px;
        margin-bottom: 18px;
    }

    .trk-filter {
        flex: 1;
        padding: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: #94a3b8;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .trk-filter:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .trk-filter.active {
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 4px 15px rgba(190, 24, 93, 0.35);
    }

    /* Chart card */
    .trk-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 18px;
        padding: 20px;
        margin-bottom: 18px;
    }

    .trk-card h5 {
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .trk-chart-wrap {
        position: relative;
        min-height: 320px;
    }

    .trk-chart-wrap canvas {
        max-width: 100%;
    }

    .trk-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 300px;
        color: #94a3b8;
        font-size: 14px;
        gap: 10px;
    }

    .trk-spinner {
        width: 22px;
        height: 22px;
        border: 3px solid rgba(255, 255, 255, 0.15);
        border-top-color: #ff4d8d;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Legend */
    .trk-legend {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 10px;
        margin-top: 8px;
    }

    .trk-legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #cbd5f5;
        font-size: 12px;
        font-weight: 500;
    }

    .trk-legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* Insights block */
    .trk-insight {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .trk-insight .icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        flex-shrink: 0;
    }

    .trk-insight .content h6 {
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 4px;
    }

    .trk-insight .content p {
        color: #94a3b8;
        font-size: 13px;
        line-height: 1.5;
        margin: 0;
    }

    /* Empty state */
    .trk-empty {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .trk-empty .emoji { font-size: 48px; opacity: 0.5; margin-bottom: 12px; }
    .trk-empty h5 { color: #fff; font-size: 16px; margin-bottom: 6px; }
    .trk-empty p { font-size: 13px; margin-bottom: 18px; }

    @media (max-width: 576px) {
        .trk-card { padding: 14px; }
        .trk-filter { padding: 8px; font-size: 12px; }
    }
</style>
@endpush

@section('content')
<div class="trk-wrap">
    <h3 class="trk-title">📊 Mood Tracker</h3>
    <p class="trk-subtitle">See your emotional patterns at a glance.</p>

    {{-- Filter --}}
    <div class="trk-filters">
        <button type="button" class="trk-filter active" data-days="7">7 Days</button>
        <button type="button" class="trk-filter" data-days="30">30 Days</button>
        <button type="button" class="trk-filter" data-days="90">90 Days</button>
    </div>

    {{-- Chart --}}
    <div class="trk-card">
        <h5><i class="bi bi-graph-up-arrow" style="color:#ff4d8d;"></i> Your Mood Trends</h5>

        <div class="trk-chart-wrap">
            <div id="trkLoading" class="trk-loading">
                <div class="trk-spinner"></div>
                <span>Loading chart...</span>
            </div>
            <canvas id="moodChart" style="display:none; max-height: 340px;"></canvas>
        </div>

        {{-- Legend --}}
        <div class="trk-legend">
            <div class="trk-legend-item">
                <span class="trk-legend-dot" style="background:#22c55e;"></span> Positive 😊
            </div>
            <div class="trk-legend-item">
                <span class="trk-legend-dot" style="background:#3b82f6;"></span> Sad 😢
            </div>
            <div class="trk-legend-item">
                <span class="trk-legend-dot" style="background:#ef4444;"></span> Stressed 😩
            </div>
            <div class="trk-legend-item">
                <span class="trk-legend-dot" style="background:#f97316;"></span> Angry 😡
            </div>
            <div class="trk-legend-item">
                <span class="trk-legend-dot" style="background:#06b6d4;"></span> Calm 😌
            </div>
        </div>
    </div>

    {{-- Insight --}}
    <div class="trk-insight">
        <div class="icon"><i class="bi bi-lightbulb-fill"></i></div>
        <div class="content">
            <h6>AI Insight</h6>
            <p id="trkInsight">Tracking your mood over time reveals patterns. Submit a few check-ins and see them here.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    const chartUrl  = '{{ route("checkins.chart") }}';
    const canvas    = document.getElementById('moodChart');
    const loadingEl = document.getElementById('trkLoading');
    const insightEl = document.getElementById('trkInsight');
    const filters   = document.querySelectorAll('.trk-filter');

    let currentDays = 7;
    let chartInstance = null;

    /* ================================
       BUILD THE CHART
    ================================= */
    async function loadChart(days) {
        // Show loading, hide canvas
        loadingEl.style.display = 'flex';
        canvas.style.display = 'none';

        try {
            const res = await fetch(`${chartUrl}?days=${days}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });

            if (!res.ok) throw new Error('Failed to fetch chart data');
            const data = await res.json();

            const total = data.positive.reduce((a, b) => a + b, 0)
                        + data.sad.reduce((a, b) => a + b, 0)
                        + data.stressed.reduce((a, b) => a + b, 0)
                        + data.angry.reduce((a, b) => a + b, 0)
                        + data.calm.reduce((a, b) => a + b, 0);

            if (total === 0) {
                showEmptyState();
                return;
            }

            renderChart(data);
            updateInsight(data);

        } catch (err) {
            console.error(err);
            showErrorState();
        }
    }

    /* ================================
       RENDER CHART
    ================================= */
    function renderChart(data) {
        // Destroy previous chart
        if (chartInstance) chartInstance.destroy();

        // Hide loading, show canvas
        loadingEl.style.display = 'none';
        canvas.style.display = 'block';

        const ctx = canvas.getContext('2d');

        // Gradient fills
        const makeGradient = (hex) => {
            const grad = ctx.createLinearGradient(0, 0, 0, 340);
            grad.addColorStop(0, hex + '55');
            grad.addColorStop(1, hex + '00');
            return grad;
        };

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Positive 😊',
                        data: data.positive,
                        borderColor: '#22c55e',
                        backgroundColor: makeGradient('#22c55e'),
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#22c55e',
                        pointBorderColor: '#0b1220',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                    },
                    {
                        label: 'Sad 😢',
                        data: data.sad,
                        borderColor: '#3b82f6',
                        backgroundColor: makeGradient('#3b82f6'),
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#0b1220',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                    },
                    {
                        label: 'Stressed 😩',
                        data: data.stressed,
                        borderColor: '#ef4444',
                        backgroundColor: makeGradient('#ef4444'),
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: '#0b1220',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                    },
                    {
                        label: 'Angry 😡',
                        data: data.angry,
                        borderColor: '#f97316',
                        backgroundColor: makeGradient('#f97316'),
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#f97316',
                        pointBorderColor: '#0b1220',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                    },
                    {
                        label: 'Calm 😌',
                        data: data.calm,
                        borderColor: '#06b6d4',
                        backgroundColor: makeGradient('#06b6d4'),
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#06b6d4',
                        pointBorderColor: '#0b1220',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false, // we have our own legend
                    },
                    tooltip: {
                        backgroundColor: 'rgba(11, 18, 32, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#cbd5f5',
                        borderColor: 'rgba(255, 77, 141, 0.4)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 },
                            maxRotation: 45,
                            minRotation: 0,
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 },
                            precision: 0,
                            stepSize: 1,
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                        },
                    },
                },
            },
        });
    }

    /* ================================
       INSIGHT MESSAGE
    ================================= */
    function updateInsight(data) {
        const totalPositive = data.positive.reduce((a, b) => a + b, 0);
        const totalSad      = data.sad.reduce((a, b) => a + b, 0);
        const totalStressed = data.stressed.reduce((a, b) => a + b, 0);
        const totalAngry    = data.angry.reduce((a, b) => a + b, 0);
        const totalCalm     = data.calm.reduce((a, b) => a + b, 0);

        const total = totalPositive + totalSad + totalStressed + totalAngry + totalCalm;
        if (total === 0) {
            insightEl.textContent = 'No check-ins yet for this period.';
            return;
        }

        // Trend: compare first half vs second half
        const mid = Math.floor(data.labels.length / 2);
        const firstHalf = (data.positive.slice(0, mid).reduce((a, b) => a + b, 0)
                         - data.sad.slice(0, mid).reduce((a, b) => a + b, 0)
                         - data.stressed.slice(0, mid).reduce((a, b) => a + b, 0));
        const secondHalf = (data.positive.slice(mid).reduce((a, b) => a + b, 0)
                          - data.sad.slice(mid).reduce((a, b) => a + b, 0)
                          - data.stressed.slice(mid).reduce((a, b) => a + b, 0));

        const positiveRatio = totalPositive / total;

        let message;
        if (positiveRatio > 0.6) {
            message = '😊 You have been mostly positive this period — keep up the great work!';
        } else if (totalStressed > totalPositive) {
            message = '💙 Stress has been prominent. Try the breathing exercise in Wellness Tools.';
        } else if (secondHalf > firstHalf + 2) {
            message = '📈 Your mood is improving — the second half of this period was noticeably better.';
        } else if (secondHalf < firstHalf - 2) {
            message = '📉 Your mood has dipped. Consider a check-in with a counselor or support partner.';
        } else if (totalSad > totalPositive) {
            message = '🌧️ You have had several sad days. You are not alone — reach out to the directory.';
        } else {
            message = '🙂 Your emotions look balanced. Keep logging to spot long-term patterns.';
        }

        insightEl.textContent = message;
    }

    /* ================================
       EMPTY / ERROR STATES
    ================================= */
    function showEmptyState() {
        loadingEl.style.display = 'none';
        canvas.style.display = 'none';

        const wrap = document.querySelector('.trk-chart-wrap');
        wrap.innerHTML = `
            <div class="trk-empty">
                <div class="emoji">🌱</div>
                <h5>No check-ins yet</h5>
                <p>Start by submitting a mood check-in on your dashboard.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-nk-primary" style="padding: 10px 20px;">
                    <i class="bi bi-plus-circle"></i> Submit a check-in
                </a>
            </div>
        `;

        insightEl.textContent = 'Submit your first check-in to see insights here.';
    }

    function showErrorState() {
        loadingEl.style.display = 'none';
        canvas.style.display = 'none';

        const wrap = document.querySelector('.trk-chart-wrap');
        wrap.innerHTML = `
            <div class="trk-empty">
                <div class="emoji">⚠️</div>
                <h5>Could not load chart</h5>
                <p>Please try again.</p>
                <button onclick="location.reload()" class="btn btn-nk-primary" style="padding: 10px 20px;">
                    <i class="bi bi-arrow-clockwise"></i> Retry
                </button>
            </div>
        `;
    }

    /* ================================
       FILTER HANDLERS
    ================================= */
    filters.forEach(btn => {
        btn.addEventListener('click', () => {
            filters.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentDays = parseInt(btn.dataset.days);
            loadChart(currentDays);
        });
    });

    /* ================================
       INIT
    ================================= */
    loadChart(currentDays);
})();
</script>
@endpush
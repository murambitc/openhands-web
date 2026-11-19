@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .adm-title {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .adm-subtitle {
        color: #94a3b8;
        font-size: 13px;
        margin-bottom: 22px;
    }

    /* Stat cards */
    .adm-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .adm-stat {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        padding: 18px;
        position: relative;
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .adm-stat::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #ff4d8d, #c2185b);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .adm-stat:hover {
        transform: translateY(-3px);
        border-color: rgba(255, 77, 141, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    }

    .adm-stat:hover::before { transform: scaleX(1); }

    .adm-stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #fff;
        margin-bottom: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    }

    .adm-stat-value {
        color: #fff;
        font-size: 26px;
        font-weight: 700;
        line-height: 1.1;
    }

    .adm-stat-label {
        color: #94a3b8;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-top: 4px;
    }

    /* Chart cards */
    .adm-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 18px;
    }

    .adm-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .adm-card h5 {
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .adm-card .chart-wrap {
        position: relative;
        height: 280px;
    }

    .adm-card .chart-wrap canvas {
        max-width: 100%;
    }

    /* Activity list */
    .adm-activity-list {
        max-height: 320px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .adm-activity-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .adm-activity-item:last-child { border-bottom: none; }

    .adm-activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
        color: #ff4d8d;
    }

    .adm-activity-info { flex: 1; min-width: 0; }

    .adm-activity-info .name {
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .adm-activity-info .detail {
        color: #94a3b8;
        font-size: 11.5px;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .adm-activity-time {
        color: #64748b;
        font-size: 10.5px;
        flex-shrink: 0;
        white-space: nowrap;
    }

    /* Settings form */
    .adm-settings textarea {
        width: 100%;
        background: #0f172a !important;
        color: #fff !important;
        border: 1px solid #253a5c;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 13.5px;
        min-height: 90px;
        resize: vertical;
        transition: all 0.2s ease;
    }

    .adm-settings textarea:focus {
        outline: none;
        border-color: #ff4d8d;
        box-shadow: 0 0 0 3px rgba(255, 77, 141, 0.15);
    }

    .adm-settings label {
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 6px;
        display: block;
    }

    /* Quick action pills */
    .adm-quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 10px;
        margin-bottom: 24px;
    }

    .adm-quick-btn {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 14px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #e2e8f0;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .adm-quick-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 77, 141, 0.4);
        color: #fff;
        transform: translateY(-2px);
    }

    .adm-quick-btn i {
        font-size: 18px;
        color: #ff4d8d;
    }

    /* Empty */
    .adm-empty {
        text-align: center;
        color: #94a3b8;
        font-size: 12.5px;
        padding: 30px 10px;
    }

    .adm-empty i {
        font-size: 32px;
        opacity: 0.5;
        display: block;
        margin-bottom: 8px;
    }

    .btn-adm-save {
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 11px 24px;
        font-weight: 600;
        font-size: 13.5px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-adm-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(190, 24, 93, 0.35);
        color: #fff;
    }

    @media (max-width: 576px) {
        .adm-card { padding: 14px; }
        .adm-card .chart-wrap { height: 240px; }
        .adm-stat-value { font-size: 22px; }
    }
</style>
@endpush

@section('content')
<h3 class="adm-title">📊 Admin Dashboard</h3>
<p class="adm-subtitle">Welcome back — here's what's happening across Open Hands.</p>

{{-- Quick actions --}}
<div class="adm-quick-actions">
    <a href="{{ route('admin.users.index') }}" class="adm-quick-btn">
        <i class="bi bi-people-fill"></i> Manage Users
    </a>
    <a href="{{ route('admin.sessions.index') }}" class="adm-quick-btn">
        <i class="bi bi-calendar-plus"></i> Post Session
    </a>
    <a href="{{ route('admin.organizations.create') }}" class="adm-quick-btn">
        <i class="bi bi-building-add"></i> Add Directory
    </a>
    <a href="{{ route('admin.organizations.index') }}" class="adm-quick-btn">
        <i class="bi bi-building"></i> View Directory
    </a>
</div>

{{-- Stat cards --}}
<div class="adm-stats">
    <div class="adm-stat">
        <div class="adm-stat-icon" style="background: linear-gradient(180deg, #3b82f6, #1e40af);">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="adm-stat-value">{{ number_format($userCount) }}</div>
        <div class="adm-stat-label">Total Users</div>
    </div>

    <div class="adm-stat">
        <div class="adm-stat-icon" style="background: linear-gradient(180deg, #22c55e, #15803d);">
            <i class="bi bi-journal-check"></i>
        </div>
        <div class="adm-stat-value">{{ number_format($checkinCount) }}</div>
        <div class="adm-stat-label">Total Check-ins</div>
    </div>

    <div class="adm-stat">
        <div class="adm-stat-icon" style="background: linear-gradient(180deg, #f59e0b, #b45309);">
            <i class="bi bi-calendar-event"></i>
        </div>
        <div class="adm-stat-value">{{ number_format($sessionCount) }}</div>
        <div class="adm-stat-label">Total Sessions</div>
    </div>

    <div class="adm-stat">
        <div class="adm-stat-icon" style="background: linear-gradient(180deg, #a855f7, #7c3aed);">
            <i class="bi bi-building"></i>
        </div>
        <div class="adm-stat-value">{{ number_format($organizationCount ?? 0) }}</div>
        <div class="adm-stat-label">Organizations</div>
    </div>
</div>

{{-- Charts row --}}
<div class="row g-3">
    {{-- Referrals chart --}}
    <div class="col-lg-6">
        <div class="adm-card">
            <div class="adm-card-header">
                <h5><i class="bi bi-link-45deg" style="color:#ff4d8d;"></i> Referrals by Organization</h5>
            </div>
            <div class="chart-wrap">
                @if ($referrals->count() > 0)
                    <canvas id="referralChart"></canvas>
                @else
                    <div class="adm-empty">
                        <i class="bi bi-link-45deg"></i>
                        No referrals yet.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Mood pie --}}
    <div class="col-lg-6">
        <div class="adm-card">
            <div class="adm-card-header">
                <h5><i class="bi bi-pie-chart-fill" style="color:#a855f7;"></i> Emotional Distribution</h5>
            </div>
            <div class="chart-wrap">
                @if ($moodData->count() > 0)
                    <canvas id="moodChart"></canvas>
                @else
                    <div class="adm-empty">
                        <i class="bi bi-pie-chart"></i>
                        No check-ins yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Recent activity + footer settings --}}
<div class="row g-3">
    {{-- Recent check-ins --}}
    <div class="col-lg-7">
        <div class="adm-card">
            <div class="adm-card-header">
                <h5><i class="bi bi-activity" style="color:#22c55e;"></i> Recent Check-ins</h5>
                <a href="{{ route('admin.users.index') }}" style="color:#ff4d8d; font-size:12px; text-decoration:none;">
                    View all →
                </a>
            </div>

            <div class="adm-activity-list">
                @forelse ($recentCheckins ?? [] as $checkin)
                    @php
                        $m = strtolower($checkin->mood);
                        $emoji = '😐';
                        if (str_contains($m, 'happy') || str_contains($m, 'joyful')) $emoji = '😊';
                        elseif (str_contains($m, 'excited')) $emoji = '😄';
                        elseif (str_contains($m, 'sad') || str_contains($m, 'crying')) $emoji = '😢';
                        elseif (str_contains($m, 'stressed') || str_contains($m, 'anxious')) $emoji = '😩';
                        elseif (str_contains($m, 'angry')) $emoji = '😡';
                        elseif (str_contains($m, 'calm') || str_contains($m, 'relaxed')) $emoji = '😌';
                    @endphp

                    <div class="adm-activity-item">
                        <div class="adm-activity-icon">{{ $emoji }}</div>
                        <div class="adm-activity-info">
                            <div class="name">{{ $checkin->user->username ?? 'Unknown User' }}</div>
                            <div class="detail">{{ \Illuminate\Support\Str::limit($checkin->comment, 50) }}</div>
                        </div>
                        <div class="adm-activity-time">
                            {{ $checkin->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="adm-empty">
                        <i class="bi bi-journal"></i>
                        No check-ins yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Footer settings --}}
    <div class="col-lg-5">
        <div class="adm-card adm-settings">
            <div class="adm-card-header">
                <h5><i class="bi bi-gear" style="color:#f59e0b;"></i> Footer Settings</h5>
            </div>

            <form method="POST" action="{{ route('admin.settings.footer') }}">
                @csrf
                <label for="footer_text">Website Footer Text</label>
                <textarea id="footer_text"
                          name="footer_text"
                          placeholder="Enter footer text..."
                          required>{{ old('footer_text', \App\Models\Setting::get('footer_text', '')) }}</textarea>

                <button type="submit" class="btn-adm-save mt-3">
                    <i class="bi bi-save"></i> Save Footer
                </button>
            </form>

            <hr style="border-color: rgba(255,255,255,0.08); margin: 18px 0;">

            <h5 style="color:#fff; font-size:14px; margin-bottom:12px;">
                <i class="bi bi-geo" style="color:#06b6d4;"></i> Map Link
            </h5>

            <form method="POST" action="{{ route('admin.settings.map') }}">
                @csrf
                <label for="map_url">Active Google Maps embed URL</label>
                <textarea id="map_url"
                          name="map_url"
                          placeholder="https://www.google.com/maps/..."
                          required style="min-height: 70px;">{{ old('map_url', \App\Models\MapLink::active() ?? '') }}</textarea>

                <button type="submit" class="btn-adm-save mt-3">
                    <i class="bi bi-geo-alt-fill"></i> Update Map
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    /* ==========================================
       REFERRALS BAR CHART
    ========================================== */
    const referralCanvas = document.getElementById('referralChart');
    if (referralCanvas) {
        const labels = @json($referrals->pluck('organization'));
        const data   = @json($referrals->pluck('count'));

        new Chart(referralCanvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Referrals',
                    data: data,
                    backgroundColor: 'rgba(123, 44, 191, 0.85)',
                    borderColor: '#9d4edd',
                    borderWidth: 2,
                    borderRadius: 8,
                    maxBarThickness: 32,
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(11, 18, 32, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#cbd5f5',
                        borderColor: 'rgba(123, 44, 191, 0.4)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { color: '#94a3b8', precision: 0, stepSize: 1 },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        title: {
                            display: true,
                            text: 'Referrals',
                            color: '#94a3b8',
                            font: { size: 11 },
                        },
                    },
                    y: {
                        ticks: { color: '#cbd5f5', font: { size: 11 } },
                        grid: { display: false },
                    },
                },
            },
        });
    }

    /* ==========================================
       MOOD PIE CHART
    ========================================== */
    const moodCanvas = document.getElementById('moodChart');
    if (moodCanvas) {
        const moodLabels = @json($moodData->keys());
        const moodValues = @json($moodData->values());

        const palette = [
            '#22c55e', '#3b82f6', '#ef4444', '#f97316', '#06b6d4',
            '#a855f7', '#ec4899', '#f59e0b', '#10b981', '#6366f1',
            '#dc2626', '#0ea5e9', '#8b5cf6', '#14b8a6', '#eab308',
        ];

        new Chart(moodCanvas, {
            type: 'doughnut',
            data: {
                labels: moodLabels,
                datasets: [{
                    data: moodValues,
                    backgroundColor: moodLabels.map((_, i) => palette[i % palette.length]),
                    borderColor: '#0b1220',
                    borderWidth: 3,
                    hoverOffset: 8,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#cbd5f5',
                            font: { size: 11 },
                            padding: 10,
                            usePointStyle: true,
                            pointStyle: 'circle',
                        },
                    },
                    tooltip: {
                        backgroundColor: 'rgba(11, 18, 32, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#cbd5f5',
                        borderColor: 'rgba(255, 77, 141, 0.4)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = ((ctx.raw / total) * 100).toFixed(1);
                                return `${ctx.label}: ${ctx.raw} (${pct}%)`;
                            },
                        },
                    },
                },
            },
        });
    }
})();
</script>
@endpush
@extends('layouts.app')

@section('title', 'Mood Logs')

@push('styles')
<style>
    .logs-wrap { max-width: 820px; margin: 0 auto; }

    .logs-title {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .logs-subtitle {
        color: #94a3b8;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .logs-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 20px;
    }

    .log-item {
        display: flex;
        gap: 14px;
        padding: 14px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.95);
        margin-bottom: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .log-item:hover {
        transform: translateX(4px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .log-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .log-content { flex: 1; min-width: 0; }

    .log-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        gap: 8px;
    }

    .log-mood {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        padding: 2px 10px;
        border-radius: 6px;
        white-space: nowrap;
    }

    .log-time {
        font-size: 11px;
        color: #64748b;
    }

    .log-comment {
        color: #334155;
        font-size: 13.5px;
        line-height: 1.5;
        margin: 0;
    }

    .empty-logs {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }

    .empty-logs .icon { font-size: 48px; opacity: 0.5; margin-bottom: 8px; }
    .empty-logs p { font-size: 14px; }

    .pagination-wrap {
        margin-top: 16px;
        display: flex;
        justify-content: center;
    }

    .pagination-wrap .pagination { margin: 0; }

    .pagination-wrap .page-link {
        background: rgba(255, 255, 255, 0.05);
        color: #e2e8f0;
        border-color: rgba(255, 255, 255, 0.1);
    }

    .pagination-wrap .page-item.active .page-link {
        background: #be185d;
        border-color: #be185d;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="logs-wrap">
    <h3 class="logs-title">📒 Mood Logs</h3>
    <p class="logs-subtitle">Every check-in, in reverse chronological order.</p>

    <div class="logs-card">
        @forelse($checkins as $checkin)
            @php
                $m = strtolower($checkin->mood);

                $cat = 'neutral';
                $emoji = '😐';
                $bg    = '#94a3b8';

                if (str_contains($m, 'happy') || str_contains($m, 'joyful') || str_contains($m, 'excited') || str_contains($m, 'loved') || str_contains($m, 'confident')) {
                    $cat = 'positive'; $emoji = '😊'; $bg = '#22c55e';
                } elseif (str_contains($m, 'sad') || str_contains($m, 'crying') || str_contains($m, 'tired')) {
                    $cat = 'sad'; $emoji = '😢'; $bg = '#3b82f6';
                } elseif (str_contains($m, 'stressed') || str_contains($m, 'anxious') || str_contains($m, 'worried')) {
                    $cat = 'stressed'; $emoji = '😩'; $bg = '#ef4444';
                } elseif (str_contains($m, 'angry') || str_contains($m, 'frustrated')) {
                    $cat = 'angry'; $emoji = '😡'; $bg = '#f97316';
                } elseif (str_contains($m, 'calm') || str_contains($m, 'peaceful') || str_contains($m, 'relaxed')) {
                    $cat = 'calm'; $emoji = '😌'; $bg = '#06b6d4';
                }

                // Extract the mood label without the emoji
                $label = trim(preg_replace('/^[^\p{L}]+/u', '', $checkin->mood));
            @endphp

            <div class="log-item">
                <div class="log-icon" style="background: {{ $bg }}22; color: {{ $bg }};">
                    {{ $emoji }}
                </div>
                <div class="log-content">
                    <div class="log-header">
                        <span class="log-mood" style="background: {{ $bg }}22; color: {{ $bg }};">
                            {{ $label ?: $checkin->mood }}
                        </span>
                        <span class="log-time">
                            {{ $checkin->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <p class="log-comment">{{ $checkin->comment }}</p>
                </div>
            </div>
        @empty
            <div class="empty-logs">
                <div class="icon">🌱</div>
                <p>No mood logs yet.</p>
                <p style="font-size: 12px;">Start with a check-in on your dashboard.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-nk-primary mt-3" style="padding: 10px 20px;">
                    Go to Dashboard
                </a>
            </div>
        @endforelse

        @if($checkins->hasPages())
            <div class="pagination-wrap">
                {{ $checkins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Sessions')

@push('styles')
<style>
    .ses-wrap { max-width: 1100px; margin: 0 auto; }

    .ses-title {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .ses-subtitle {
        color: #94a3b8;
        font-size: 13px;
        margin-bottom: 22px;
    }

    .ses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 18px;
    }

    .ses-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }

    .ses-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255, 77, 141, 0.3);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    }

    .ses-image {
        width: 100%;
        height: 170px;
        background: linear-gradient(135deg, #2F5DAA, #be185d);
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.5);
        font-size: 42px;
        position: relative;
    }

    .ses-image::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, transparent 60%, rgba(0, 0, 0, 0.4));
        pointer-events: none;
    }

    .ses-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 10px;
    }

    .ses-heading {
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.3;
        margin: 0;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .ses-desc {
        color: #cbd5f5;
        font-size: 12.5px;
        line-height: 1.55;
        margin: 0;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .ses-meta {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding-top: 8px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        margin-top: auto;
    }

    .ses-meta-row {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #94a3b8;
        font-size: 12px;
    }

    .ses-meta-row i {
        color: #ff4d8d;
        font-size: 13px;
        width: 16px;
        text-align: center;
        flex-shrink: 0;
    }

    .ses-empty {
        text-align: center;
        padding: 60px 20px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px dashed rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        color: #94a3b8;
    }

    .ses-empty .icon { font-size: 48px; opacity: 0.5; margin-bottom: 12px; }
    .ses-empty h5 { color: #fff; font-size: 16px; margin-bottom: 6px; }
    .ses-empty p { font-size: 13px; margin-bottom: 0; }

    /* Detail modal */
    .modal-content {
        background: rgba(27, 42, 71, 0.98) !important;
        backdrop-filter: blur(16px);
        color: #fff;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        overflow: hidden;
    }

    .modal-header { border-color: rgba(255, 255, 255, 0.1); }
    .modal-title { color: #fff; font-weight: 600; }
    .btn-close { filter: invert(1); opacity: 0.8; }

    .ses-modal-img {
        width: 100%;
        height: 220px;
        background: linear-gradient(135deg, #2F5DAA, #be185d);
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.4);
        font-size: 60px;
    }

    .ses-modal-desc {
        color: #cbd5f5;
        font-size: 14px;
        line-height: 1.7;
        white-space: pre-line;
    }

    .ses-modal-meta {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 16px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 12px;
        margin-top: 16px;
    }

    .ses-modal-meta .ses-meta-row { font-size: 13.5px; }
    .ses-modal-meta .ses-meta-row i { font-size: 16px; }

    @media (max-width: 576px) {
        .ses-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="ses-wrap">
    <h3 class="ses-title">📢 Sessions & Activities</h3>
    <p class="ses-subtitle">Upcoming wellness events, workshops, and gatherings.</p>

    @if ($sessions->count() > 0)
        <div class="ses-grid">
            @foreach ($sessions as $session)
                <div class="ses-card">
                    {{-- Image --}}
                    <div class="ses-image"
                         style="{{ $session->image ? 'background-image:url(' . asset('storage/' . $session->image) . ')' : '' }}">
                        @if (!$session->image)
                            <i class="bi bi-calendar-event"></i>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="ses-body">
                        <h5 class="ses-heading">{{ $session->title }}</h5>
                        <p class="ses-desc">{{ $session->description }}</p>

                        <div class="ses-meta">
                            @if ($session->session_date)
                                <div class="ses-meta-row">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>{{ \Carbon\Carbon::parse($session->session_date)->format('d M Y') }}</span>
                                </div>
                            @endif

                            @if ($session->session_time)
                                <div class="ses-meta-row">
                                    <i class="bi bi-clock"></i>
                                    <span>{{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}</span>
                                </div>
                            @endif

                            @if ($session->location)
                                <div class="ses-meta-row">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>{{ $session->location }}</span>
                                </div>
                            @endif
                        </div>

                        <button type="button"
                                class="wt-btn wt-btn-primary"
                                style="width: 100%; margin-top: 8px; padding: 10px;"
                                onclick='openSessionModal(@json($session->title), @json($session->description), @json($session->session_date ? \Carbon\Carbon::parse($session->session_date)->format("l, d F Y") : null), @json($session->session_time ? \Carbon\Carbon::parse($session->session_time)->format("g:i A") : null), @json($session->location), @json($session->image ? asset("storage/" . $session->image) : null))'>
                            View Details
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="ses-empty">
            <div class="icon">📅</div>
            <h5>No sessions available yet</h5>
            <p>An administrator will post upcoming activities here. Check back soon!</p>
        </div>
    @endif
</div>

{{-- SESSION DETAIL MODAL --}}
<div class="modal fade" id="sessionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="ses-modal-img" id="sesModalImg">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="modal-header">
                <h5 class="modal-title" id="sesModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="ses-modal-desc" id="sesModalDesc"></p>

                <div class="ses-modal-meta" id="sesModalMeta">
                    <div class="ses-meta-row" id="sesModalDate" style="display:none;">
                        <i class="bi bi-calendar-event"></i>
                        <span></span>
                    </div>
                    <div class="ses-meta-row" id="sesModalTime" style="display:none;">
                        <i class="bi bi-clock"></i>
                        <span></span>
                    </div>
                    <div class="ses-meta-row" id="sesModalLocation" style="display:none;">
                        <i class="bi bi-geo-alt"></i>
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-color: rgba(255,255,255,0.1);">
                <button type="button"
                        class="wt-btn wt-btn-primary"
                        data-bs-dismiss="modal"
                        style="width: auto; padding: 10px 24px;">
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

    window.openSessionModal = function (title, description, date, time, location, imageUrl) {
        // Title
        document.getElementById('sesModalTitle').textContent = title || 'Session';

        // Description
        document.getElementById('sesModalDesc').textContent = description || 'No description available.';

        // Image
        const imgEl = document.getElementById('sesModalImg');
        if (imageUrl) {
            imgEl.style.backgroundImage = `url(${imageUrl})`;
            imgEl.innerHTML = '';
        } else {
            imgEl.style.backgroundImage = '';
            imgEl.innerHTML = '<i class="bi bi-calendar-event"></i>';
        }

        // Date
        const dateRow = document.getElementById('sesModalDate');
        if (date) {
            dateRow.style.display = 'flex';
            dateRow.querySelector('span').textContent = date;
        } else {
            dateRow.style.display = 'none';
        }

        // Time
        const timeRow = document.getElementById('sesModalTime');
        if (time) {
            timeRow.style.display = 'flex';
            timeRow.querySelector('span').textContent = time;
        } else {
            timeRow.style.display = 'none';
        }

        // Location
        const locationRow = document.getElementById('sesModalLocation');
        if (location) {
            locationRow.style.display = 'flex';
            locationRow.querySelector('span').textContent = location;
        } else {
            locationRow.style.display = 'none';
        }

        new bootstrap.Modal(document.getElementById('sessionModal')).show();
    };
})();
</script>
@endpush
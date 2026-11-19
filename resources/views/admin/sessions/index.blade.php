@extends('layouts.app')

@section('title', 'Manage Sessions')

@push('styles')
<style>
    .as-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }
    .as-title { color:#fff; font-size:22px; font-weight:700; margin:0; }
    .as-subtitle { color:#94a3b8; font-size:13px; margin:4px 0 0; }

    .as-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        overflow: hidden;
    }

    .as-table {
        width: 100%;
        border-collapse: collapse;
        color: #e2e8f0;
        margin: 0;
    }
    .as-table th {
        text-align: left;
        padding: 12px 16px;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        background: rgba(255,255,255,0.04);
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .as-table td {
        padding: 12px 16px;
        font-size: 13.5px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        vertical-align: middle;
    }
    .as-table tbody tr:hover td { background: rgba(255,255,255,0.03); }

    .as-thumb {
        width: 64px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #2F5DAA, #be185d);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,0.6);
        font-size: 16px;
        overflow: hidden;
    }
    .as-thumb img { width:100%; height:100%; object-fit:cover; }

    .as-session-title {
        color: #fff;
        font-weight: 600;
        margin-bottom: 2px;
        max-width: 260px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .as-session-desc {
        color: #94a3b8;
        font-size: 11.5px;
        max-width: 260px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .as-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .as-badge-active   { background: rgba(34,197,94,0.2); color: #86efac; }
    .as-badge-inactive { background: rgba(148,163,184,0.2); color: #cbd5f5; }

    .as-actions { display: flex; gap: 6px; justify-content: flex-end; }
    .as-icon-btn {
        width: 32px; height: 32px; border-radius: 8px;
        border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 14px;
        background: rgba(255,255,255,0.08);
        color: #e2e8f0;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .as-icon-btn:hover { background: rgba(255,255,255,0.15); color: #fff; transform: translateY(-1px); }
    .as-icon-btn.edit:hover   { background: rgba(245,158,11,0.25); color: #fcd34d; }
    .as-icon-btn.delete:hover { background: rgba(239,68,68,0.25); color: #fca5a5; }

    .as-btn {
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .as-btn-primary {
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        color: #fff;
    }
    .as-btn-primary:hover { color:#fff; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(190,24,93,0.35); }

    .as-empty {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }
    .as-empty i { font-size: 42px; opacity: 0.5; margin-bottom: 10px; display: block; }
    .as-empty p { font-size: 13.5px; margin: 0; }

    .as-pagination {
        padding: 14px 16px;
        border-top: 1px solid rgba(255,255,255,0.05);
    }
    .as-pagination .page-link {
        background: rgba(255,255,255,0.05);
        color: #e2e8f0;
        border-color: rgba(255,255,255,0.1);
    }
    .as-pagination .page-item.active .page-link {
        background: #be185d;
        border-color: #be185d;
        color: #fff;
    }

    .as-modal .modal-content {
        background: rgba(27,42,71,0.98) !important;
        backdrop-filter: blur(16px);
        color: #fff;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .as-modal .modal-header { border-color: rgba(255,255,255,0.1); }
    .as-modal .modal-title { color: #fff; }
    .as-modal .btn-close { filter: invert(1); opacity: 0.8; }
    .as-modal .form-label { color:#cbd5f5; font-size:13px; font-weight:500; margin-bottom:4px; }
    .as-modal .form-control {
        background: #0f172a !important;
        border: 1px solid #253a5c;
        color: #fff !important;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13.5px;
    }
    .as-modal .form-control:focus {
        border-color: #ff4d8d;
        box-shadow: 0 0 0 3px rgba(255,77,141,0.15);
    }

    @media (max-width: 768px) {
        .as-table th:nth-child(3),
        .as-table td:nth-child(3),
        .as-table th:nth-child(4),
        .as-table td:nth-child(4) {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="as-header">
    <div>
        <h3 class="as-title">📅 Manage Sessions</h3>
        <p class="as-subtitle">Post and manage upcoming wellness sessions and activities.</p>
    </div>
    <a href="{{ route('admin.sessions.create') }}" class="as-btn as-btn-primary">
        <i class="bi bi-plus-circle"></i> Post New Session
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="as-card">
    @if ($sessions->count() > 0)
        <div style="overflow-x: auto;">
            <table class="as-table">
                <thead>
                    <tr>
                        <th>Session</th>
                        <th>Date & Time</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sessions as $session)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="as-thumb">
                                        @if ($session->image)
                                            <img src="{{ asset('storage/' . $session->image) }}" alt="">
                                        @else
                                            <i class="bi bi-calendar-event"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="as-session-title">{{ $session->title }}</div>
                                        <div class="as-session-desc">{{ $session->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="color:#cbd5f5; font-size:12.5px;">
                                @if ($session->session_date)
                                    {{ \Carbon\Carbon::parse($session->session_date)->format('d M Y') }}
                                @else
                                    —
                                @endif
                                @if ($session->session_time)
                                    <br><span style="color:#64748b;">
                                        {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}
                                    </span>
                                @endif
                            </td>
                            <td style="color:#94a3b8; font-size:12.5px;">
                                {{ $session->location ?: '—' }}
                            </td>
                            <td>
                                @if ($session->is_active)
                                    <span class="as-badge as-badge-active">Active</span>
                                @else
                                    <span class="as-badge as-badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div class="as-actions">
                                    <a href="{{ route('admin.sessions.edit', $session) }}"
                                       class="as-icon-btn edit"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button"
                                            class="as-icon-btn delete"
                                            onclick="openDeleteModal({{ $session->id }}, @json($session->title))"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="as-pagination">
            {{ $sessions->links() }}
        </div>
    @else
        <div class="as-empty">
            <i class="bi bi-calendar-x"></i>
            <p>No sessions posted yet.</p>
            <a href="{{ route('admin.sessions.create') }}" class="as-btn as-btn-primary" style="margin-top: 16px;">
                <i class="bi bi-plus-circle"></i> Post First Session
            </a>
        </div>
    @endif
</div>

{{-- DELETE MODAL --}}
<div class="modal fade as-modal" id="deleteSessionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> Delete Session
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="color: #cbd5f5; font-size: 14px;">
                    Are you sure you want to delete <strong id="deleteSessionName" style="color:#fca5a5;"></strong>?
                    This cannot be undone.
                </p>
                <input type="hidden" id="deleteSessionId">
                <div id="deleteSessionError" class="alert alert-danger" style="display:none;"></div>
            </div>
            <div class="modal-footer" style="border-color: rgba(255,255,255,0.1);">
                <button type="button" class="as-btn" style="background: rgba(255,255,255,0.1); color:#fff;" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="as-btn" style="background: #dc2626; color: #fff;" onclick="confirmDeleteSession()" id="deleteSessionBtn">
                    <i class="bi bi-trash"></i> Delete
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    window.openDeleteModal = function (id, title) {
        document.getElementById('deleteSessionId').value = id;
        document.getElementById('deleteSessionName').textContent = title;
        document.getElementById('deleteSessionError').style.display = 'none';
        new bootstrap.Modal(document.getElementById('deleteSessionModal')).show();
    };

    window.confirmDeleteSession = async function () {
        const id = document.getElementById('deleteSessionId').value;
        const errorEl = document.getElementById('deleteSessionError');
        const btn = document.getElementById('deleteSessionBtn');

        errorEl.style.display = 'none';
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Deleting...';

        try {
            const res = await fetch(`/admin/sessions/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
            });

            if (!res.ok) {
                const data = await res.json().catch(() => ({}));
                errorEl.textContent = data.message || 'Failed to delete.';
                errorEl.style.display = 'block';
                return;
            }

            bootstrap.Modal.getInstance(document.getElementById('deleteSessionModal')).hide();
            if (window.showToast) window.showToast('Session deleted.');
            setTimeout(() => location.reload(), 700);
        } catch (err) {
            errorEl.textContent = 'Network error.';
            errorEl.style.display = 'block';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-trash"></i> Delete';
        }
    };
})();
</script>
@endpush
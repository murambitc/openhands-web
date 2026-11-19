@extends('layouts.app')

@section('title', 'View Organizations')

@push('styles')
<style>
    .av-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }
    .av-title { color:#fff; font-size:22px; font-weight:700; margin:0; }
    .av-subtitle { color:#94a3b8; font-size:13px; margin:4px 0 0; }

    .av-btn {
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
    .av-btn-primary {
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        color: #fff;
    }
    .av-btn-primary:hover { color:#fff; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(190,24,93,0.35); }

    .av-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 14px;
    }

    .av-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        padding: 18px;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .av-card:hover {
        transform: translateY(-3px);
        border-color: rgba(255,77,141,0.35);
        box-shadow: 0 12px 30px rgba(0,0,0,0.35);
    }

    .av-card-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .av-logo {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 22px;
        flex-shrink: 0;
        overflow: hidden;
    }
    .av-logo img { width:100%; height:100%; object-fit:cover; }

    .av-info { flex: 1; min-width: 0; }

    .av-name {
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        margin: 0 0 4px;
        line-height: 1.3;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .av-desc {
        color: #94a3b8;
        font-size: 12.5px;
        line-height: 1.5;
        margin: 0;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .av-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 11.5px;
        color: #94a3b8;
        margin-top: 4px;
    }
    .av-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .av-meta i { color: #ff4d8d; }

    .av-actions {
        display: flex;
        gap: 6px;
        margin-top: auto;
        padding-top: 8px;
        border-top: 1px solid rgba(255,255,255,0.05);
    }

    .av-icon-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        background: rgba(255,255,255,0.08);
        color: #e2e8f0;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .av-icon-btn:hover { background: rgba(255,255,255,0.15); color: #fff; transform: translateY(-1px); }
    .av-icon-btn.edit:hover   { background: rgba(245,158,11,0.25); color: #fcd34d; }
    .av-icon-btn.delete:hover { background: rgba(239,68,68,0.25); color: #fca5a5; }
    .av-icon-btn.map:hover    { background: rgba(6,182,212,0.25); color: #67e8f9; }
    .av-icon-btn.referrals { flex: 1; font-size: 12.5px; gap: 6px; padding: 0 12px; width: auto; }
    .av-icon-btn.referrals:hover { background: rgba(123,44,191,0.25); color: #c084fc; }

    .av-empty {
        text-align: center;
        padding: 60px 20px;
        background: rgba(255,255,255,0.03);
        border: 1px dashed rgba(255,255,255,0.15);
        border-radius: 16px;
        color: #94a3b8;
    }
    .av-empty i { font-size: 48px; opacity: 0.5; margin-bottom: 12px; display: block; }
    .av-empty h5 { color: #fff; font-size: 16px; margin-bottom: 6px; }
    .av-empty p { font-size: 13px; margin-bottom: 16px; }

    /* Modals */
    .av-modal .modal-content {
        background: rgba(27,42,71,0.98) !important;
        backdrop-filter: blur(16px);
        color: #fff;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .av-modal .modal-header { border-color: rgba(255,255,255,0.1); }
    .av-modal .modal-title { color: #fff; }
    .av-modal .btn-close { filter: invert(1); opacity: 0.8; }

    .av-referrals-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .av-referral-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        background: rgba(255,255,255,0.04);
        border-radius: 10px;
        margin-bottom: 8px;
    }
    .av-referral-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
    }
    .av-referral-name {
        color: #fff;
        font-size: 13px;
        font-weight: 600;
    }
    .av-referral-meta {
        color: #94a3b8;
        font-size: 11.5px;
    }
</style>
@endpush

@section('content')
<div class="av-header">
    <div>
        <h3 class="av-title">🏢 Organizations</h3>
        <p class="av-subtitle">Manage support organizations shown in the directory.</p>
    </div>
    <a href="{{ route('admin.organizations.create') }}" class="av-btn av-btn-primary">
        <i class="bi bi-plus-circle"></i> Add Organization
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($organizations->count() > 0)
    <div class="av-grid">
        @foreach ($organizations as $org)
            <div class="av-card">
                <div class="av-card-header">
                    <div class="av-logo">
                        @if ($org->logo)
                            <img src="{{ asset('storage/' . $org->logo) }}" alt="{{ $org->name }}">
                        @else
                            <i class="bi bi-building"></i>
                        @endif
                    </div>
                    <div class="av-info">
                        <h5 class="av-name">{{ $org->name }}</h5>
                        <p class="av-desc">{{ $org->description }}</p>
                    </div>
                </div>

                <div class="av-meta">
                    @if ($org->phone)
                        <span><i class="bi bi-telephone-fill"></i> {{ $org->phone }}</span>
                    @endif
                    @if ($org->email)
                        <span><i class="bi bi-envelope-fill"></i> {{ \Illuminate\Support\Str::limit($org->email, 25) }}</span>
                    @endif
                </div>

                <div class="av-actions">
                    <button type="button"
                            class="av-icon-btn referrals"
                            onclick="openReferrals({{ $org->id }}, @json($org->name))"
                            title="View referrals">
                        <i class="bi bi-people-fill"></i>
                        {{ $org->screenings_count ?? 0 }} Referred
                    </button>

                    @if ($org->map_link)
                        <a href="{{ $org->map_link }}" target="_blank" rel="noopener"
                           class="av-icon-btn map" title="View on map">
                            <i class="bi bi-geo-alt-fill"></i>
                        </a>
                    @endif

                    <a href="{{ route('admin.organizations.edit', $org) }}"
                       class="av-icon-btn edit" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <button type="button"
                            class="av-icon-btn delete"
                            onclick="openDeleteModal({{ $org->id }}, @json($org->name))"
                            title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    @if ($organizations->hasPages())
        <div style="margin-top: 20px;">
            {{ $organizations->links() }}
        </div>
    @endif
@else
    <div class="av-empty">
        <i class="bi bi-building"></i>
        <h5>No organizations yet</h5>
        <p>Add your first organization to populate the directory.</p>
        <a href="{{ route('admin.organizations.create') }}" class="av-btn av-btn-primary">
            <i class="bi bi-plus-circle"></i> Add Organization
        </a>
    </div>
@endif

{{-- REFERRALS MODAL --}}
<div class="modal fade av-modal" id="referralsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-people-fill"></i> <span id="referralsOrgName">Referrals</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="av-referrals-list" id="referralsBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-light"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DELETE MODAL --}}
<div class="modal fade av-modal" id="deleteOrgModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> Delete Organization
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="color:#cbd5f5; font-size:14px;">
                    Are you sure you want to delete <strong id="deleteOrgName" style="color:#fca5a5;"></strong>?
                    Any screenings referring to this organization will be unlinked.
                </p>
                <input type="hidden" id="deleteOrgId">
                <div id="deleteOrgError" class="alert alert-danger" style="display:none;"></div>
            </div>
            <div class="modal-footer" style="border-color: rgba(255,255,255,0.1);">
                <button type="button" class="av-btn" style="background: rgba(255,255,255,0.1); color:#fff;" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="av-btn" style="background: #dc2626; color: #fff;" onclick="confirmDeleteOrg()" id="deleteOrgBtn">
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

    /* ==================== REFERRALS ==================== */
    window.openReferrals = async function (orgId, orgName) {
        document.getElementById('referralsOrgName').textContent = orgName + ' — Referrals';
        document.getElementById('referralsBody').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-light"></div></div>';

        new bootstrap.Modal(document.getElementById('referralsModal')).show();

        try {
            const res = await fetch(`/admin/organizations/${orgId}/referrals`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });

            if (!res.ok) throw new Error('Failed');
            const data = await res.json();

            if (data.length === 0) {
                document.getElementById('referralsBody').innerHTML = `
                    <div style="text-align:center; padding: 30px 20px; color:#94a3b8;">
                        <i class="bi bi-people" style="font-size:32px; opacity:0.5; display:block; margin-bottom:8px;"></i>
                        <p style="font-size:13px; margin:0;">No referrals yet.</p>
                    </div>`;
                return;
            }

            document.getElementById('referralsBody').innerHTML = data.map(r => `
                <div class="av-referral-item">
                    <div class="av-referral-avatar">
                        ${escapeHtml((r.username || '?').charAt(0).toUpperCase())}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div class="av-referral-name">${escapeHtml(r.username || 'Unknown')}</div>
                        <div class="av-referral-meta">${escapeHtml(r.email || '—')}</div>
                    </div>
                    <div class="av-referral-meta" style="text-align:right;">
                        ${r.created_at ? new Date(r.created_at).toLocaleDateString() : ''}
                    </div>
                </div>
            `).join('');
        } catch (err) {
            document.getElementById('referralsBody').innerHTML =
                '<div class="alert alert-danger">Failed to load referrals.</div>';
        }
    };

    /* ==================== DELETE ==================== */
    window.openDeleteModal = function (id, name) {
        document.getElementById('deleteOrgId').value = id;
        document.getElementById('deleteOrgName').textContent = name;
        document.getElementById('deleteOrgError').style.display = 'none';
        new bootstrap.Modal(document.getElementById('deleteOrgModal')).show();
    };

    window.confirmDeleteOrg = async function () {
        const id = document.getElementById('deleteOrgId').value;
        const errorEl = document.getElementById('deleteOrgError');
        const btn = document.getElementById('deleteOrgBtn');

        errorEl.style.display = 'none';
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Deleting...';

        try {
            const res = await fetch(`/admin/organizations/${id}`, {
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

            bootstrap.Modal.getInstance(document.getElementById('deleteOrgModal')).hide();
            if (window.showToast) window.showToast('Organization deleted.');
            setTimeout(() => location.reload(), 700);
        } catch (err) {
            errorEl.textContent = 'Network error.';
            errorEl.style.display = 'block';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-trash"></i> Delete';
        }
    };

    function escapeHtml(s) {
        return String(s ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
})();
</script>
@endpush
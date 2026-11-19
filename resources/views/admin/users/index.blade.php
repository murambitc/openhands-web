@extends('layouts.app')

@section('title', 'User Management')

@push('styles')
<style>
    .um-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .um-title {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .um-subtitle {
        color: #94a3b8;
        font-size: 13px;
        margin: 4px 0 0;
    }

    /* Stats row */
    .um-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .um-stat {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 14px 16px;
    }

    .um-stat-label {
        color: #94a3b8;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 4px;
    }

    .um-stat-value {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        line-height: 1;
    }

    /* Search + actions bar */
    .um-toolbar {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .um-search-wrap {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .um-search-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
        pointer-events: none;
    }

    .um-search {
        width: 100%;
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        border-radius: 10px;
        padding: 11px 14px 11px 40px;
        font-size: 14px;
    }

    .um-search:focus {
        outline: none;
        border-color: #ff4d8d;
        box-shadow: 0 0 0 3px rgba(255, 77, 141, 0.15);
    }

    .um-search::placeholder { color: #94a3b8; }

    .um-btn {
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .um-btn-primary {
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        color: #fff;
    }
    .um-btn-primary:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(190, 24, 93, 0.35);
    }

    .um-btn-success {
        background: linear-gradient(180deg, #22c55e, #15803d);
        color: #fff;
    }
    .um-btn-success:hover { color: #fff; transform: translateY(-1px); }

    /* Table */
    .um-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
    }

    .um-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
        color: #e2e8f0;
    }

    .um-table th {
        text-align: left;
        padding: 12px 16px;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        background: rgba(255, 255, 255, 0.04);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .um-table td {
        padding: 12px 16px;
        font-size: 13.5px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
    }

    .um-table tbody tr:hover td {
        background: rgba(255, 255, 255, 0.03);
    }

    .um-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 12px;
        margin-right: 8px;
        vertical-align: middle;
        overflow: hidden;
    }

    .um-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .um-name {
        display: inline-flex;
        align-items: center;
        color: #fff;
        font-weight: 500;
    }

    .um-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .um-badge-admin   { background: rgba(123, 44, 191, 0.2); color: #c084fc; }
    .um-badge-user    { background: rgba(59, 130, 246, 0.2); color: #93c5fd; }
    .um-badge-low     { background: rgba(34, 197, 94, 0.2); color: #86efac; }
    .um-badge-medium  { background: rgba(245, 158, 11, 0.2); color: #fcd34d; }
    .um-badge-high    { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }

    .um-actions {
        display: flex;
        gap: 6px;
    }

    .um-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.08);
        color: #e2e8f0;
    }

    .um-icon-btn:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        transform: translateY(-1px);
    }

    .um-icon-btn.view:hover   { background: rgba(59, 130, 246, 0.25); color: #93c5fd; }
    .um-icon-btn.edit:hover   { background: rgba(245, 158, 11, 0.25); color: #fcd34d; }
    .um-icon-btn.delete:hover { background: rgba(239, 68, 68, 0.25); color: #fca5a5; }

    .um-empty {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }

    .um-empty i { font-size: 42px; opacity: 0.5; margin-bottom: 10px; display: block; }
    .um-empty p { font-size: 13.5px; margin: 0; }

    /* Pagination */
    .um-pagination {
        padding: 14px 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        background: rgba(255, 255, 255, 0.02);
    }

    .um-pagination .page-link {
        background: rgba(255, 255, 255, 0.05);
        color: #e2e8f0;
        border-color: rgba(255, 255, 255, 0.1);
    }

    .um-pagination .page-item.active .page-link {
        background: #be185d;
        border-color: #be185d;
        color: #fff;
    }

    /* Modals */
    .modal-content {
        background: rgba(27, 42, 71, 0.98) !important;
        backdrop-filter: blur(16px);
        color: #fff;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .modal-header { border-color: rgba(255, 255, 255, 0.1); }
    .modal-title { color: #fff; font-weight: 600; }
    .btn-close { filter: invert(1); opacity: 0.8; }

    .modal .form-label {
        color: #cbd5f5;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .modal .form-control,
    .modal .form-select {
        background: #0f172a !important;
        border: 1px solid #253a5c;
        color: #fff !important;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13.5px;
    }

    .modal .form-control:focus,
    .modal .form-select:focus {
        border-color: #ff4d8d;
        box-shadow: 0 0 0 3px rgba(255, 77, 141, 0.15);
        background: #0f172a !important;
        color: #fff !important;
    }

    .modal .form-control::placeholder { color: #64748b; }

    .modal .form-text {
        color: #94a3b8;
        font-size: 11px;
    }

    /* Details modal */
    .um-details-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 16px;
    }

    .um-details-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 24px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .um-details-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .um-details-name {
        color: #fff;
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .um-details-meta {
        color: #94a3b8;
        font-size: 12.5px;
        margin-top: 2px;
    }

    .um-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 16px;
    }

    .um-detail-item {
        background: rgba(255, 255, 255, 0.04);
        border-radius: 10px;
        padding: 10px 12px;
    }

    .um-detail-item .label {
        color: #94a3b8;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 3px;
    }

    .um-detail-item .value {
        color: #fff;
        font-size: 13.5px;
        font-weight: 500;
        word-break: break-word;
    }

    .um-checkin-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    .um-checkin-item:last-child { border-bottom: none; }

    .um-checkin-emoji {
        font-size: 20px;
        flex-shrink: 0;
    }

    .um-checkin-comment {
        color: #e2e8f0;
        font-size: 12.5px;
        line-height: 1.5;
    }

    .um-checkin-time {
        color: #64748b;
        font-size: 11px;
        margin-top: 2px;
    }

    @media (max-width: 768px) {
        .um-table th:nth-child(3),
        .um-table td:nth-child(3),
        .um-table th:nth-child(5),
        .um-table td:nth-child(5) {
            display: none;
        }
        .um-detail-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="um-header">
    <div>
        <h3 class="um-title">👥 Manage Users</h3>
        <p class="um-subtitle">View, edit, and manage all accounts on Open Hands.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.users.export') }}" class="um-btn um-btn-success">
            <i class="bi bi-download"></i> Export CSV
        </a>
        <a href="{{ route('admin.users.create') }}" class="um-btn um-btn-primary">
            <i class="bi bi-person-plus"></i> Add User
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="um-stats">
    <div class="um-stat">
        <div class="um-stat-label">Total</div>
        <div class="um-stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="um-stat">
        <div class="um-stat-label">Admins</div>
        <div class="um-stat-value">{{ $stats['admins'] }}</div>
    </div>
    <div class="um-stat">
        <div class="um-stat-label">Users</div>
        <div class="um-stat-value">{{ $stats['users'] }}</div>
    </div>
    <div class="um-stat">
        <div class="um-stat-label">High Risk</div>
        <div class="um-stat-value" style="color: #fca5a5;">{{ $stats['high'] }}</div>
    </div>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.users.index') }}" class="um-toolbar">
    <div class="um-search-wrap">
        <i class="bi bi-search"></i>
        <input type="text"
               name="search"
               class="um-search"
               placeholder="Search by username, email, or country..."
               value="{{ $search }}">
    </div>
    <button type="submit" class="um-btn um-btn-primary">
        <i class="bi bi-search"></i> Search
    </button>
    @if ($search)
        <a href="{{ route('admin.users.index') }}" class="um-btn um-btn-primary" style="background: rgba(255,255,255,0.1); color:#fff;">
            Clear
        </a>
    @endif
</form>

{{-- Table --}}
<div class="um-card">
    @if ($users->count() > 0)
        <div style="overflow-x: auto;">
            <table class="um-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Risk</th>
                        <th>Registered</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <span class="um-name">
                                    <span class="um-avatar">
                                        @if ($user->profile_image)
                                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="">
                                        @else
                                            {{ strtoupper(substr($user->username, 0, 1)) }}
                                        @endif
                                    </span>
                                    {{ $user->username }}
                                </span>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="um-badge um-badge-{{ $user->role }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td>
                                <span class="um-badge um-badge-{{ $user->risk_level ?? 'low' }}">
                                    {{ $user->risk_level ?? 'low' }}
                                </span>
                            </td>
                            <td style="color:#94a3b8; font-size:12px;">
                                {{ $user->created_at?->format('d M Y') }}
                            </td>
                            <td style="text-align: right;">
                                <div class="um-actions" style="justify-content: flex-end;">
                                    <button class="um-icon-btn view"
                                            onclick="viewUser({{ $user->id }})"
                                            title="View details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="um-icon-btn edit"
                                            onclick="editUser({{ $user->id }})"
                                            title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="um-icon-btn delete"
                                            onclick="openDeleteModal({{ $user->id }}, @json($user->username))"
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

        <div class="um-pagination">
            {{ $users->links() }}
        </div>
    @else
        <div class="um-empty">
            <i class="bi bi-people"></i>
            <p>{{ $search ? 'No users match your search.' : 'No users yet.' }}</p>
        </div>
    @endif
</div>

{{-- ============================================
     VIEW USER MODAL
============================================ --}}
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-circle"></i> User Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewUserBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-light" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================
     EDIT USER MODAL
============================================ --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square"></i> Edit User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" onsubmit="submitEditUser(event)">
                <div class="modal-body">
                    <input type="hidden" id="editUserId">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" id="editUsername" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" id="editEmail" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select id="editRole" class="form-control" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" id="editCountry" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" id="editLocation" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Risk Level</label>
                            <select id="editRiskLevel" class="form-control" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password (optional)</label>
                            <input type="password" id="editPassword" class="form-control"
                                   placeholder="Leave blank to keep current">
                            <small class="form-text">Only fill this to reset the user's password.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" id="editPasswordConfirm" class="form-control">
                        </div>
                    </div>

                    <div id="editError" class="alert alert-danger mt-3 mb-0" style="display:none;"></div>
                </div>
                <div class="modal-footer" style="border-color: rgba(255,255,255,0.1);">
                    <button type="button" class="um-btn" style="background: rgba(255,255,255,0.1); color:#fff;" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="um-btn um-btn-primary" id="editSubmitBtn">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================
     DELETE USER MODAL
============================================ --}}
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> Delete User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="color: #cbd5f5; font-size: 14px;">
                    You are about to permanently delete
                    <strong id="deleteUserName" style="color:#fca5a5;"></strong>.
                    This cannot be undone and will remove all their check-ins, screenings, and messages.
                </p>

                <input type="hidden" id="deleteUserId">

                <label class="form-label">Enter your admin password to confirm</label>
                <input type="password" id="deleteAdminPassword" class="form-control" placeholder="Your password" autocomplete="new-password">
                <div id="deleteError" class="alert alert-danger mt-3 mb-0" style="display:none;"></div>
            </div>
            <div class="modal-footer" style="border-color: rgba(255,255,255,0.1);">
                <button type="button" class="um-btn" style="background: rgba(255,255,255,0.1); color:#fff;" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="um-btn" style="background: #dc2626; color: #fff;" onclick="confirmDelete()" id="deleteSubmitBtn">
                    <i class="bi bi-trash"></i> Delete Permanently
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
    const flash = (msg, type = 'success') => {
        if (window.showToast) window.showToast(msg, type);
        else alert(msg);
    };

    /* ==========================================
       VIEW USER
    ========================================== */
    window.viewUser = async function (id) {
        const modal = new bootstrap.Modal(document.getElementById('viewUserModal'));
        const body = document.getElementById('viewUserBody');
        body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-light"></div></div>';
        modal.show();

        try {
            const res = await fetch(`/admin/users/${id}/details`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            if (!res.ok) throw new Error('Failed to load');

            const data = await res.json();
            const u = data.user;
            const s = data.stats;

            const initial = (u.username || '?').charAt(0).toUpperCase();

            body.innerHTML = `
                <div class="um-details-header">
                    <div class="um-details-avatar">
                        ${u.profile_image_url ? `<img src="${u.profile_image_url}" alt="">` : initial}
                    </div>
                    <div>
                        <h4 class="um-details-name">${escapeHtml(u.username)}</h4>
                        <div class="um-details-meta">${escapeHtml(u.email)}</div>
                    </div>
                </div>

                <div class="um-detail-grid">
                    <div class="um-detail-item">
                        <div class="label">Role</div>
                        <div class="value" style="text-transform: capitalize;">${u.role}</div>
                    </div>
                    <div class="um-detail-item">
                        <div class="label">Risk Level</div>
                        <div class="value">
                            <span class="um-badge um-badge-${u.risk_level}">${u.risk_level}</span>
                        </div>
                    </div>
                    <div class="um-detail-item">
                        <div class="label">Country</div>
                        <div class="value">${escapeHtml(u.country || '—')}</div>
                    </div>
                    <div class="um-detail-item">
                        <div class="label">Location</div>
                        <div class="value">${escapeHtml(u.location || '—')}</div>
                    </div>
                    <div class="um-detail-item">
                        <div class="label">Registered</div>
                        <div class="value">${u.created_at}</div>
                    </div>
                    <div class="um-detail-item">
                        <div class="label">Total Check-ins</div>
                        <div class="value">${s.checkins}</div>
                    </div>
                </div>

                ${s.latest_screening ? `
                    <div class="um-detail-item mb-3">
                        <div class="label">Latest Screening</div>
                        <div class="value">
                            <span class="um-badge um-badge-${s.latest_screening.risk}">${s.latest_screening.risk}</span>
                            &nbsp;${escapeHtml(s.latest_screening.concern || '')}
                            <div style="color:#64748b; font-size:11.5px; margin-top:3px;">
                                ${s.latest_screening.created_at}
                            </div>
                        </div>
                    </div>
                ` : ''}

                <h6 style="color:#fff; font-size:13px; margin:16px 0 8px;">
                    <i class="bi bi-clock-history"></i> Recent Check-ins
                </h6>
                ${data.recent_checkins.length === 0 ? `
                    <p style="color:#94a3b8; font-size:12.5px;">No check-ins yet.</p>
                ` : data.recent_checkins.map(c => `
                    <div class="um-checkin-item">
                        <span class="um-checkin-emoji">${moodEmoji(c.mood)}</span>
                        <div style="flex:1;">
                            <div class="um-checkin-comment">${escapeHtml(c.comment)}</div>
                            <div class="um-checkin-time">${c.created_at}</div>
                        </div>
                    </div>
                `).join('')}
            `;
        } catch (err) {
            body.innerHTML = '<div class="alert alert-danger">Failed to load user details.</div>';
        }
    };

    /* ==========================================
       EDIT USER
    ========================================== */
    window.editUser = async function (id) {
        try {
            const res = await fetch(`/admin/users/${id}/edit`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            if (!res.ok) throw new Error('Failed to load');

            const u = await res.json();

            document.getElementById('editUserId').value       = u.id;
            document.getElementById('editName').value         = u.name || '';
            document.getElementById('editUsername').value     = u.username || '';
            document.getElementById('editEmail').value        = u.email || '';
            document.getElementById('editRole').value         = u.role || 'user';
            document.getElementById('editCountry').value      = u.country || '';
            document.getElementById('editLocation').value     = u.location || '';
            document.getElementById('editRiskLevel').value    = u.risk_level || 'low';
            document.getElementById('editPassword').value     = '';
            document.getElementById('editPasswordConfirm').value = '';
            document.getElementById('editError').style.display = 'none';

            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        } catch (err) {
            flash('Failed to load user data.', 'error');
        }
    };

    window.submitEditUser = async function (e) {
        e.preventDefault();

        const id = document.getElementById('editUserId').value;
        const errorEl = document.getElementById('editError');
        const btn = document.getElementById('editSubmitBtn');

        errorEl.style.display = 'none';
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';

        const payload = {
            name:       document.getElementById('editName').value.trim(),
            username:   document.getElementById('editUsername').value.trim(),
            email:      document.getElementById('editEmail').value.trim(),
            role:       document.getElementById('editRole').value,
            country:    document.getElementById('editCountry').value.trim(),
            location:   document.getElementById('editLocation').value.trim(),
            risk_level: document.getElementById('editRiskLevel').value,
        };

        const pass = document.getElementById('editPassword').value;
        if (pass) {
            payload.password = pass;
            payload.password_confirmation = document.getElementById('editPasswordConfirm').value;
        }

        try {
            const res = await fetch(`/admin/users/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload),
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                const errs = data.errors
                    ? Object.values(data.errors).flat().join('<br>')
                    : (data.message || 'Update failed');
                errorEl.innerHTML = errs;
                errorEl.style.display = 'block';
                return;
            }

            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
            flash('User updated successfully.');
            setTimeout(() => location.reload(), 900);
        } catch (err) {
            errorEl.textContent = 'Network error. Please try again.';
            errorEl.style.display = 'block';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-save"></i> Save Changes';
        }
    };

    /* ==========================================
       DELETE USER
    ========================================== */
    window.openDeleteModal = function (id, username) {
        document.getElementById('deleteUserId').value = id;
        document.getElementById('deleteUserName').textContent = username;
        document.getElementById('deleteAdminPassword').value = '';
        document.getElementById('deleteError').style.display = 'none';
        new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
    };

    window.confirmDelete = async function () {
        const id = document.getElementById('deleteUserId').value;
        const password = document.getElementById('deleteAdminPassword').value;
        const errorEl = document.getElementById('deleteError');
        const btn = document.getElementById('deleteSubmitBtn');

        errorEl.style.display = 'none';

        if (!password) {
            errorEl.textContent = 'Please enter your admin password.';
            errorEl.style.display = 'block';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Deleting...';

        try {
            const res = await fetch(`/admin/users/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
                body: JSON.stringify({ password }),
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                errorEl.textContent = data.message || 'Delete failed.';
                errorEl.style.display = 'block';
                return;
            }

            bootstrap.Modal.getInstance(document.getElementById('deleteUserModal')).hide();
            flash('User deleted.');
            setTimeout(() => location.reload(), 700);
        } catch (err) {
            errorEl.textContent = 'Network error. Please try again.';
            errorEl.style.display = 'block';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-trash"></i> Delete Permanently';
        }
    };

    /* ==========================================
       HELPERS
    ========================================== */
    function escapeHtml(s) {
        return String(s ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function moodEmoji(mood) {
        const m = String(mood).toLowerCase();
        if (m.includes('happy') || m.includes('joyful')) return '😊';
        if (m.includes('excited')) return '😄';
        if (m.includes('content') || m.includes('relaxed')) return '🙂';
        if (m.includes('loved')) return '😍';
        if (m.includes('confident')) return '😎';
        if (m.includes('sad') || m.includes('crying')) return '😢';
        if (m.includes('stressed') || m.includes('anxious')) return '😩';
        if (m.includes('angry') || m.includes('frustrated')) return '😡';
        if (m.includes('tired')) return '😴';
        if (m.includes('calm') || m.includes('peaceful')) return '😌';
        return '😐';
    }
})();
</script>
@endpush
<header class="nk-header-bar">
    <div class="d-flex align-items-center gap-2">
        {{-- Sidebar toggle (hamburger) --}}
        <button type="button"
                class="sidebar-toggle-btn"
                onclick="toggleSidebar()"
                aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>

        {{-- Page title --}}
        <span style="color: #fff; font-weight: 600; font-size: 15px;">
            @yield('title', 'Dashboard')
        </span>
    </div>

    <div class="d-flex align-items-center gap-2">
        {{-- Chat --}}
        <button type="button"
                class="nk-header-icon icon-gradient"
                onclick="toggleChatFAB()"
                title="Chat"
                style="border: none;">
            <i class="bi bi-chat-dots-fill"></i>
        </button>

        {{-- Change password --}}
        <button type="button"
                class="nk-header-icon icon-gradient"
                data-bs-toggle="modal"
                data-bs-target="#changePasswordModal"
                title="Change Password"
                style="border: none;">
            <i class="bi bi-key-fill"></i>
        </button>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit"
                    class="nk-header-icon icon-gradient"
                    title="Logout"
                    style="border: none;">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</header>

{{-- Change Password Modal --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('password.update') }}" class="modal-content p-4">
            @csrf
            @method('put')
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title">🔐 Change Password</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control mb-3" required>

                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control mb-3" required minlength="8">

                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control mb-3" required>

                <button class="btn btn-nk-primary w-100 mt-2">Update Password</button>
            </div>
        </form>
    </div>
</div>
<nav id="sidebar">
    {{-- Profile --}}
    <div class="p-3 text-center" style="border-bottom: 1px solid rgba(255,255,255,0.15);">
        <div class="profile-img-wrapper mx-auto" style="width: 85px; height: 85px; transition: all 0.28s;">
            <img src="{{ auth()->user()->profile_image_url }}"
                 alt="{{ auth()->user()->username }}"
                 class="profile-img"
                 style="width: 85px; height: 85px; border-radius: 50%;
                        object-fit: cover; border: 2px solid #2F5DAA;">
        </div>
        <div class="profile-text mt-2">
            <div class="profile-name" style="color: #fff; font-weight: 600; font-size: 14px;">
                {{ auth()->user()->username }}
            </div>
            <div style="color: rgba(255,255,255,0.75); font-size: 11px;">
                {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Member' }}
            </div>
        </div>
    </div>

    {{-- Menu --}}
    <div class="flex-grow-1 py-3" style="padding-left: 8px; padding-right: 8px;">
        <div class="sidebar-heading px-3 mb-2" style="color: rgba(255,255,255,0.6);
                    font-size: 10px; letter-spacing: 1.2px; text-transform: uppercase;">
            Menu
        </div>

        <div class="sidebar-menu d-flex flex-column gap-1">

            @if(auth()->user()->isAdmin())
                {{-- ADMIN MENU --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                   title="Admin Dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span class="menu-label">Admin Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                   title="User Management">
                    <i class="bi bi-person-lines-fill"></i>
                    <span class="menu-label">User Management</span>
                </a>

                <a href="{{ route('admin.sessions.index') }}"
                   class="menu-item {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}"
                   title="Post Sessions">
                    <i class="bi bi-calendar-plus"></i>
                    <span class="menu-label">Post Sessions</span>
                </a>

                <a href="{{ route('admin.organizations.create') }}"
                   class="menu-item {{ request()->routeIs('admin.organizations.create') ? 'active' : '' }}"
                   title="Add Directory">
                    <i class="bi bi-building-add"></i>
                    <span class="menu-label">Add Directory</span>
                </a>

                <a href="{{ route('admin.organizations.index') }}"
                   class="menu-item {{ request()->routeIs('admin.organizations.index') ? 'active' : '' }}"
                   title="View Directory">
                    <i class="bi bi-building"></i>
                    <span class="menu-label">View Directory</span>
                </a>
            @else
                {{-- USER MENU --}}
                <a href="{{ route('dashboard') }}"
                   class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   title="Dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span class="menu-label">Dashboard</span>
                </a>

                <a href="{{ route('screening') }}"
                   class="menu-item {{ request()->routeIs('screening*') ? 'active' : '' }}"
                   title="Screening">
                    <i class="bi bi-clipboard-heart"></i>
                    <span class="menu-label">Screening</span>
                </a>

                <a href="{{ route('checkins.index') }}"
                   class="menu-item {{ request()->routeIs('checkins.*') ? 'active' : '' }}"
                   title="Mood Logs">
                    <i class="bi bi-journal-text"></i>
                    <span class="menu-label">Mood Logs</span>
                </a>

                <a href="{{ route('tracker') }}"
                   class="menu-item {{ request()->routeIs('tracker*') ? 'active' : '' }}"
                   title="Mood Tracker">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span class="menu-label">Mood Tracker</span>
                </a>

                <a href="{{ route('organizations') }}"
                   class="menu-item {{ request()->routeIs('organizations*') ? 'active' : '' }}"
                   title="Directory">
                    <i class="bi bi-people-fill"></i>
                    <span class="menu-label">Directory</span>
                </a>

                <a href="{{ route('wellness.tools') }}"
                   class="menu-item {{ request()->routeIs('wellness*') ? 'active' : '' }}"
                   title="Wellness Tools">
                    <i class="bi bi-heart-pulse-fill"></i>
                    <span class="menu-label">Wellness Tools</span>
                </a>

                <a href="{{ route('sessions') }}"
                   class="menu-item {{ request()->routeIs('sessions*') ? 'active' : '' }}"
                   title="Sessions">
                    <i class="bi bi-calendar-check"></i>
                    <span class="menu-label">Sessions</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Logout --}}
    <div style="padding: 8px; border-top: 1px solid rgba(255,255,255,0.15);">
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="menu-item w-100" style="background: rgba(0,0,0,0.25); border: none;" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
                <span class="menu-label">Logout</span>
            </button>
        </form>
    </div>
</nav>

<style>
    /* Menu items inside the sidebar */
    #sidebar .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        color: #fff;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        transition: all 0.2s ease;
        white-space: nowrap;
        overflow: hidden;
        cursor: pointer;
    }

    #sidebar .menu-item i {
        font-size: 18px;
        flex-shrink: 0;
        width: 22px;
        text-align: center;
    }

    #sidebar .menu-item .menu-label {
        flex: 1;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    #sidebar .menu-item:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateX(3px);
    }

    #sidebar .menu-item.active {
        background: #2F5DAA;
        box-shadow: 0 4px 15px rgba(47, 93, 170, 0.5);
    }

    /* When collapsed, add tooltip behavior via title attr — no extra CSS needed */
</style>
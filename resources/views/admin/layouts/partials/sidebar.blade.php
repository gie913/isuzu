<aside class="d-sidebar">
    <div class="brand">
        <div class="brand-logo">
            <svg viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                <path fill="#ffffff" d="M14.747 9.125c.527-1.426 1.736-2.573 3.317-2.573c1.643 0 2.792 1.085 3.318 2.573l6.077 16.867c.186.496.248.931.248 1.147c0 1.209-.992 2.046-2.139 2.046c-1.303 0-1.954-.682-2.264-1.611l-.931-2.915h-8.62l-.93 2.884c-.31.961-.961 1.642-2.232 1.642c-1.24 0-2.294-.93-2.294-2.17c0-.496.155-.868.217-1.023l6.233-16.867zm.34 11.256h5.891l-2.883-8.992h-.062l-2.946 8.992z"/>
            </svg>
        </div>
        <div class="brand-text">
            <div class="brand-name">{{ config('app.name', env('APP_NAME', 'ISUZU CMS')) }}</div>
            <div class="brand-tag">v1.0 · admin</div>
        </div>
    </div>

    <!-- Workspace Section -->
    <nav class="nav-section">
        <div class="nav-label">Workspace</div>
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
            <svg viewBox="0 0 24 24"><path d="M3 12 12 3l9 9"/><path d="M5 10v10h14V10"/></svg>
            <span>{{ __('label.dashboard') }}</span>
        </a>
        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}" href="{{ route('admin.users.index') }}">
            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>{{ __('label.users') }}</span>
        </a>
    </nav>

    <!-- Components / Navigation Section -->
    <nav class="nav-section">
        <div class="nav-label">System</div>
        <div class="nav-item-group {{ request()->routeIs('admin.users.*') ? 'is-open' : '' }}" data-nav-group>
            <a class="nav-link" href="javascript:void(0)" data-nav-toggle>
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M3 16h18M9 4v16"/></svg>
                <span>Management</span>
                <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m9 18 6-6-6-6"/></svg>
            </a>
            <div class="nav-submenu">
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'fw-bold' : '' }}">Users List</a>
                <a href="{{ route('admin.users.list') }}" class="{{ request()->routeIs('admin.users.list') ? 'fw-bold' : '' }}">Users List (Ajax)</a>
                <a href="{{ route('admin.users.add') }}" class="{{ request()->routeIs('admin.users.add') ? 'fw-bold' : '' }}">Add User</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="dd-wrap" style="width: 100%;">
            <div class="workspace" data-dropdown tabindex="0" role="button" aria-label="User menu" style="cursor: pointer; width: 100%;">
                <div class="workspace-avatar">
                    {{ strtoupper(substr(Auth::user()?->name ?? 'AD', 0, 2)) }}
                </div>
                <div class="workspace-text">
                    <div class="workspace-name">{{ Auth::user()?->name ?? 'Administrator' }}</div>
                    <div class="workspace-role">{{ Auth::user()?->username ?? 'admin' }}</div>
                </div>
                <svg class="workspace-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="m7 9 5-5 5 5"/><path d="m7 15 5 5 5-5"/>
                </svg>
            </div>

            <!-- Profile Dropdown Menu -->
            <div class="dd-menu dd-profile" role="menu" style="bottom: calc(100% + 8px); top: auto; left: 0; right: 0; min-width: 100%;">
                <div class="dd-profile-head">
                    <div class="dd-profile-name">{{ Auth::user()?->name ?? 'Administrator' }}</div>
                    <div class="dd-profile-email">{{ Auth::user()?->email ?? 'admin@isuzu.com' }}</div>
                </div>
                <a class="dd-menu-item" href="#">
                    <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Profile
                </a>
                <div class="dd-divider"></div>
                <form id="sidebarLogoutForm" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a class="dd-menu-item danger" href="javascript:void(0)" onclick="document.getElementById('sidebarLogoutForm').submit();">
                    <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</aside>

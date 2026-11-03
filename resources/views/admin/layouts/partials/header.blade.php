<header class="d-topbar">
    <div class="crumbs">
        <button class="hamburger" data-drawer-open aria-label="Open navigation">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

        @if(isset($breadcrumbs) && is_array($breadcrumbs))
            @foreach($breadcrumbs as $b)
                @if(!$loop->first)
                    <svg class="sep" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                @endif
                <span class="{{ $loop->last ? 'current' : '' }}">
                    @if(!$loop->last && isset($b['url']))
                        <a href="{{ $b['url'] }}" style="color:inherit;text-decoration:none;">{{ $b['name'] }}</a>
                    @else
                        {{ $b['name'] }}
                    @endif
                </span>
            @endforeach
        @else
            <span>Workspace</span>
            <svg class="sep" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m9 18 6-6-6-6"/>
            </svg>
            <span class="current">{{ $title ?? __('label.dashboard') }}</span>
        @endif
    </div>

    <div class="topbar-actions">
        <!-- Search Palette Trigger -->
        <button class="cmd" data-palette-open>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="7"/>
                <path d="m21 21-4.3-4.3"/>
            </svg>
            <span>Search...</span>
            <kbd class="kbd">⌘K</kbd>
        </button>

        <!-- Language Switcher (?lang=) -->
        <div class="dd-wrap">
            <button class="icon-btn" data-dropdown aria-label="Language" style="width: auto; padding: 0 10px; font-size: 12px; font-weight: 600;">
                {{ strtoupper(app()->getLocale()) }}
            </button>
            <div class="dd-menu" role="menu" style="min-width: 140px; right: 0;">
                <a class="dd-menu-item {{ app()->getLocale() === 'en' ? 'fw-bold' : '' }}" href="?lang=en">
                    English (EN)
                </a>
                <a class="dd-menu-item {{ app()->getLocale() === 'id' ? 'fw-bold' : '' }}" href="?lang=id">
                    Bahasa Indonesia (ID)
                </a>
            </div>
        </div>

        <!-- Notifications Dropdown -->
        <div class="dd-wrap">
            <button class="icon-btn" data-dropdown aria-label="Notifications">
                <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <span class="count danger">2</span>
            </button>
            <div class="dd-menu" role="menu">
                <div class="dd-head">
                    <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    Notifications
                </div>
                <div class="dd-list">
                    <a class="dd-item" href="#">
                        <div class="dd-avatar a1">IZ</div>
                        <div class="dd-body">
                            <div class="dd-text"><strong>System</strong> updated to Laravel 12</div>
                            <div class="dd-time">JUST NOW</div>
                        </div>
                    </a>
                    <a class="dd-item" href="#">
                        <div class="dd-avatar a2">DB</div>
                        <div class="dd-body">
                            <div class="dd-text"><strong>Database</strong> MySQL connected</div>
                            <div class="dd-time">10 MIN AGO</div>
                        </div>
                    </a>
                </div>
                <a class="dd-footer" href="#">View all notifications →</a>
            </div>
        </div>

        <!-- Theme Mode Toggle (Dark/Light) -->
        <button class="icon-btn" id="themeToggle" aria-label="Toggle theme"></button>

        <!-- User Profile Dropdown -->
        <div class="dd-wrap">
            <div class="avatar" data-dropdown tabindex="0" role="button" aria-label="Account menu" style="overflow: hidden; padding: 0;">
                @if (Auth::user()?->image_src)
                    <img src="{{ Str::startsWith(Auth::user()->image_src, 'http') ? Auth::user()->image_src : asset(Auth::user()->image_src) }}" alt="{{ Auth::user()->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ strtoupper(substr(Auth::user()?->name ?? 'AD', 0, 2)) }}
                @endif
            </div>
            <div class="dd-menu dd-profile" role="menu">
                <div class="dd-profile-head">
                    <div class="dd-profile-name">{{ Auth::user()?->name ?? 'Administrator' }}</div>
                    <div class="dd-profile-email">{{ Auth::user()?->email ?? 'admin@isuzu.com' }}</div>
                </div>
                <a class="dd-menu-item" href="{{ route('admin.my-profile') }}">
                    <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    My Profile
                </a>
                <a class="dd-menu-item" href="{{ route('admin.change-password') }}">
                    <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Change Password
                </a>
                <div class="dd-divider"></div>
                <form id="adminLogoutForm" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a class="dd-menu-item danger" href="javascript:void(0)" onclick="document.getElementById('adminLogoutForm').submit();">
                    <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>

<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Inter+Tight:wght@500;600;700&family=JetBrains+Mono:wght@400;500&display=swap">
    <title>Sign in · ISUZU Admin Workspace</title>
    <script>
        !function(){try{var t=localStorage.getItem("dash26-theme"),e=window.matchMedia("(prefers-color-scheme: dark)").matches;document.documentElement.setAttribute("data-theme",t||(e?"dark":"light"))}catch(t){document.documentElement.setAttribute("data-theme","light")}}()
    </script>
    <script defer="defer" src="{{ asset('themes/admin/runtime.096d507f.js') }}"></script>
    <script defer="defer" src="{{ asset('themes/admin/2026.5a84696f.js') }}"></script>
    <link href="{{ asset('themes/admin/style.76ee5eee.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-shell">
        <aside class="auth-aside">
            <div class="auth-brand">
                <div class="logo">
                    <svg viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                    </svg>
                </div>
                <div class="name">{{ config('app.name', env('APP_NAME', 'ISUZU CMS')) }}</div>
            </div>
            <div class="auth-aside-body">
                <span class="auth-aside-eyebrow">Enterprise · Control Panel</span>
                <h1>The administrative workspace built for performance.</h1>
                <p>Modular Laravel architecture with real-time analytics, RBAC permissions, and multi-language support.</p>
                <div class="auth-quote">
                    "Reliable, responsive, and secure. Built upon layered Service-Repository architecture with Laravel Passport integration."
                    <div class="auth-quote-author">
                        <div class="av">IZ</div>
                        <div>ISUZU Technical Architecture</div>
                    </div>
                </div>
            </div>
            <div class="auth-aside-footer">
                <span>© {{ date('Y') }} ISUZU</span>
                <span>ENTERPRISE CMS</span>
            </div>
        </aside>

        <main class="auth-main">
            <div class="auth-main-top">
                <div style="font-size:12.5px;color:var(--t-muted);display:inline-flex;align-items:center;gap:6px">
                    <span class="badge" style="background:var(--surface-sunken);color:var(--t-muted);padding:4px 8px;border-radius:4px;font-size:11px;">
                        LANGUAGE:
                    </span>
                    <a href="?lang=en" style="color:{{ app()->getLocale() === 'en' ? 'var(--primary)' : 'var(--t-muted)' }};font-weight:600;text-decoration:none">EN</a>
                    <span>·</span>
                    <a href="?lang=id" style="color:{{ app()->getLocale() === 'id' ? 'var(--primary)' : 'var(--t-muted)' }};font-weight:600;text-decoration:none">ID</a>
                </div>
            </div>

            <div class="auth-card">
                <h2>Welcome back</h2>
                <p class="sub">Sign in to your ISUZU Admin workspace to continue.</p>

                @if ($errors->any())
                    <div style="background:#fee2e2;border:1px solid #f87171;color:#b91c1c;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;">
                        <ul style="margin:0;padding-left:16px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div style="background:#dcfce7;border:1px solid #4ade80;color:#15803d;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="auth-form" method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    <div class="field">
                        <label class="field-label" for="username">{{ __('form.username') }}</label>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/></svg>
                            </span>
                            <input id="username" name="username" class="input" type="text" value="{{ old('username') }}" placeholder="admin" autocomplete="username" required autofocus>
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-row">
                            <label class="field-label" for="password">{{ __('form.password') }}</label>
                        </div>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </span>
                            <input id="password" name="password" class="input" type="password" placeholder="••••••••" autocomplete="current-password" required style="padding-right: 38px;">
                            <button type="button" id="togglePasswordBtn" aria-label="Toggle password visibility" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; padding: 4px; display: inline-flex; align-items: center; justify-content: center; color: var(--t-muted); cursor: pointer; border-radius: 4px;">
                                <!-- Eye Open (Show password) -->
                                <svg id="eyeOpenIcon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <!-- Eye Off/Closed (Hide password) -->
                                <svg id="eyeClosedIcon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <label class="check">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span class="box"></span> Keep me signed in
                    </label>

                    <button class="btn btn--primary auth-submit" type="submit">
                        {{ __('form.submit') }} (Sign in)
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                    </button>
                </form>
            </div>

            <div class="auth-main-bottom">
                ISUZU Administrative System &copy; {{ date('Y') }}. All rights reserved.
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('password');
            const eyeOpenIcon = document.getElementById('eyeOpenIcon');
            const eyeClosedIcon = document.getElementById('eyeClosedIcon');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                    if (isPassword) {
                        // Password visible -> show eyeClosedIcon (click to hide)
                        eyeOpenIcon.style.display = 'none';
                        eyeClosedIcon.style.display = 'inline';
                    } else {
                        // Password hidden -> show eyeOpenIcon (click to view)
                        eyeOpenIcon.style.display = 'inline';
                        eyeClosedIcon.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>

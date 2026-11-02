@extends('admin.layouts.theme', [
    'title' => 'Change Password',
    'activeMenu' => 'change-password',
    'breadcrumbsText' => 'Workspace | Profile | Change Password'
])

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Profile</span>
            <h1 class="hero-title">Change Password</h1>
            <p class="hero-sub">Ensure your account remains safe by configuring a strong, modern password.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <button type="submit" form="changePasswordForm" class="btn btn--primary">
                {{ __('form.submit') }}
            </button>
        </div>
    </section>

    @if (session('success'))
        <div class="alert success" style="margin-bottom: 20px;">
            <span class="ico">
                <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
            <div class="body">{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert danger" style="margin-bottom: 20px;">
            <span class="ico">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </span>
            <div class="body">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid">
        <section class="col-12 card" style="max-width: 760px;">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">Authentication Security</span>
                    <h2 class="card-title">Update Your Password</h2>
                </div>
            </div>

            <form id="changePasswordForm" action="{{ route('admin.change-password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid" style="grid-template-columns: 1fr;">
                    <!-- Old Password -->
                    <div class="field">
                        <label class="field-label" for="current_password">
                            Old Password <span class="req">*</span>
                        </label>
                        <div class="input-icon" style="position:relative;">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </span>
                            <input id="current_password" name="current_password" class="input @error('current_password') is-invalid @enderror" type="password" required placeholder="Enter your current password" style="padding-right: 42px;">
                            <button type="button" class="toggle-password-btn" data-target="current_password" aria-label="Toggle password visibility" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; padding: 4px; display: inline-flex; align-items: center; justify-content: center; color: var(--t-muted); cursor: pointer; border-radius: 4px; z-index: 2;">
                                <svg class="eye-open" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="eye-closed" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></line>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="field">
                        <div class="field-row" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <label class="field-label" for="password" style="margin-bottom:0;">
                                New Password <span class="req">*</span>
                            </label>
                            <button type="button" id="generatePasswordBtn" class="btn btn--ghost" style="padding:3px 10px;font-size:11.5px;height:auto;gap:5px;display:inline-flex;align-items:center;">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 4V2m0 20v-2m8-8h-2M3 12H1m18.36-6.36l-1.42 1.42M6.06 17.94l-1.42 1.42m0-14.84l1.42 1.42m12.3 12.3l1.42 1.42"></path>
                                    <path d="m14 10-8.5 8.5a2.12 2.12 0 1 0 3 3L17 13"></path>
                                </svg>
                                <span>Generate Password</span>
                            </button>
                        </div>
                        <div class="input-icon" style="position:relative;">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </span>
                            <input id="password" name="password" class="input @error('password') is-invalid @enderror" type="password" required placeholder="Enter new password (min. 8 characters)" minlength="8" style="padding-right: 42px;">
                            <button type="button" class="toggle-password-btn" data-target="password" aria-label="Toggle password visibility" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; padding: 4px; display: inline-flex; align-items: center; justify-content: center; color: var(--t-muted); cursor: pointer; border-radius: 4px; z-index: 2;">
                                <svg class="eye-open" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="eye-closed" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></line>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="field-help" id="newPasswordHelp">Minimum 8 characters with combination of letters, numbers, and symbols.</div>
                        @error('password')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password Confirmation -->
                    <div class="field">
                        <label class="field-label" for="password_confirmation">
                            New Password Confirmation <span class="req">*</span>
                        </label>
                        <div class="input-icon" style="position:relative;">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </span>
                            <input id="password_confirmation" name="password_confirmation" class="input @error('password_confirmation') is-invalid @enderror" type="password" required placeholder="Re-enter your new password" minlength="8" style="padding-right: 42px;">
                            <button type="button" class="toggle-password-btn" data-target="password_confirmation" aria-label="Toggle password visibility" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; padding: 4px; display: inline-flex; align-items: center; justify-content: center; color: var(--t-muted); cursor: pointer; border-radius: 4px; z-index: 2;">
                                <svg class="eye-open" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="eye-closed" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></line>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                    <span class="spacer"></span>
                    <button type="submit" class="btn btn--primary">{{ __('form.submit') }}</button>
                </div>
            </form>
        </section>
    </div>
</main>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Password toggles for each field
    document.querySelectorAll('.toggle-password-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = btn.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            if (!targetInput) return;

            const isPassword = targetInput.getAttribute('type') === 'password';
            targetInput.setAttribute('type', isPassword ? 'text' : 'password');

            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');

            if (eyeOpen && eyeClosed) {
                eyeOpen.style.display = isPassword ? 'none' : 'inline';
                eyeClosed.style.display = isPassword ? 'inline' : 'none';
            }
        });
    });

    // Password generator for new password & auto-fill confirmation
    const generateBtn = document.getElementById('generatePasswordBtn');
    const newPasswordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const helpText = document.getElementById('newPasswordHelp');

    if (generateBtn && newPasswordInput && confirmPasswordInput) {
        generateBtn.addEventListener('click', function () {
            const length = 14;
            const upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
            const lower = 'abcdefghijkmnpqrstuvwxyz';
            const digits = '23456789';
            const special = '!@#$%^&*()_+~';
            const all = upper + lower + digits + special;

            let generated = '';
            generated += upper[Math.floor(Math.random() * upper.length)];
            generated += lower[Math.floor(Math.random() * lower.length)];
            generated += digits[Math.floor(Math.random() * digits.length)];
            generated += special[Math.floor(Math.random() * special.length)];

            for (let i = 4; i < length; i++) {
                generated += all[Math.floor(Math.random() * all.length)];
            }

            generated = generated.split('').sort(() => 0.5 - Math.random()).join('');

            // Set both fields to the generated password
            newPasswordInput.value = generated;
            confirmPasswordInput.value = generated;

            // Make them visible so user can see what was generated
            ['password', 'password_confirmation'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el) {
                    el.setAttribute('type', 'text');
                }
                const btn = document.querySelector(`.toggle-password-btn[data-target="${id}"]`);
                if (btn) {
                    const eyeOpen = btn.querySelector('.eye-open');
                    const eyeClosed = btn.querySelector('.eye-closed');
                    if (eyeOpen && eyeClosed) {
                        eyeOpen.style.display = 'none';
                        eyeClosed.style.display = 'inline';
                    }
                }
            });

            newPasswordInput.focus();
            newPasswordInput.select();

            if (helpText) {
                helpText.innerHTML = '<span style="color:var(--success, #10b981);font-weight:600;">✓ Generated secure password (visible above and filled into confirmation). Make sure to copy it!</span>';
            }
        });
    }
});
</script>
@endsection

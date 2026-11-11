@extends('admin.layouts.theme', [
    'title' => __('label.add_user'),
    'activeMenu' => 'users',
    'breadcrumbsText' => 'Workspace | ' . __('label.users') . ' | ' . __('label.add_user')
])

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Users</span>
            <h1 class="hero-title">{{ __('label.add_user') }}</h1>
            <p class="hero-sub">Create a new user account with credentials and permissions.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <button type="submit" form="addUserForm" class="btn btn--primary">
                {{ __('form.submit') }}
            </button>
        </div>
    </section>

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
        <section class="col-12 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">User Information</span>
                    <h2 class="card-title">Account details</h2>
                </div>
            </div>

            <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <!-- Full Name -->
                    <div class="field">
                        <label class="field-label" for="name">
                            {{ __('form.name') }} <span class="req">*</span>
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </span>
                            <input id="name" name="name" class="input @error('name') is-invalid @enderror" type="text" placeholder="John Doe" value="{{ old('name') }}" required>
                        </div>
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="field">
                        <label class="field-label" for="username">
                            {{ __('form.username') }}
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg>
                            </span>
                            <input id="username" name="username" class="input @error('username') is-invalid @enderror" type="text" placeholder="johndoe" value="{{ old('username') }}">
                        </div>
                        @error('username')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="field">
                        <label class="field-label" for="email">
                            {{ __('form.email') }} <span class="req">*</span>
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                            </span>
                            <input id="email" name="email" class="input @error('email') is-invalid @enderror" type="email" placeholder="john@example.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="field-help">Used for sign-in, alerts, and dispatches.</div>
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="field">
                        <label class="field-label" for="m_role_id">
                            {{ __('label.role') ?? 'Role' }}
                        </label>
                        <select id="m_role_id" name="m_role_id" class="input @error('m_role_id') is-invalid @enderror">
                            <option value="">-- {{ __('label.select_role') ?? 'Select Role' }} --</option>
                            @foreach($roles ?? [] as $role)
                                <option value="{{ $role->id }}" {{ old('m_role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-help">Assign user permissions and access rights.</div>
                        @error('m_role_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Profile Image (image_src) -->
                    <div class="field span-2">
                        <label class="field-label" for="file_image_src">
                            Profile Picture 
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </span>
                            <input type="file" id="file_image_src" class="input upload_file @error('image_src') is-invalid @enderror" accept="image/*" {!! upload_setup_attrs('user', 'image_src', ['preview' => '#preview_image_src', 'setvalue' => '#image_src']) !!}>
                        </div>
                        <input type="hidden" id="image_src" name="image_src" value="{{ old('image_src') }}">
                        <div class="field-help">Upload an avatar image. Automatically compressed, cropped (1:1), and stored safely.</div>
                        <div id="preview_image_src" class="upload-preview-container">
                            @if(old('image_src'))
                                <div class="upload-preview-card" style="display:inline-flex; align-items:center; gap:12px; background:var(--bg-sub, #f8fafc); border:1px solid var(--border, #e2e8f0); border-radius:8px; padding:8px 12px; margin-top:8px;">
                                    <img src="{{ Str::startsWith(old('image_src'), 'http') ? old('image_src') : asset(old('image_src')) }}" alt="Preview" style="width:48px; height:48px; object-fit:cover; border-radius:6px; border:1px solid var(--border, #e2e8f0);">
                                    <div style="font-size:12px; line-height:1.4;">
                                        <div style="font-weight:600; color:var(--success, #10b981);">Image Ready</div>
                                        <a href="{{ Str::startsWith(old('image_src'), 'http') ? old('image_src') : asset(old('image_src')) }}" target="_blank" style="color:var(--t-body, #1e293b); text-decoration:underline;">View full image</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @error('image_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="field span-2">
                        <div class="field-row" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <label class="field-label" for="password" style="margin-bottom:0;">
                                {{ __('form.password') }} <span class="req">*</span>
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
                            <input id="password" name="password" class="input @error('password') is-invalid @enderror" type="password" placeholder="Minimum 8 characters" required minlength="8" style="padding-right: 42px;">
                            <button type="button" id="togglePasswordBtn" aria-label="Toggle password visibility" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; padding: 4px; display: inline-flex; align-items: center; justify-content: center; color: var(--t-muted); cursor: pointer; border-radius: 4px; z-index: 2;">
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
                        <div class="field-help" id="passwordHelpText">Must be at least 8 characters long. Click "Generate Password" to create a secure one.</div>
                        @error('password')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.users.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const eyeOpenIcon = document.getElementById('eyeOpenIcon');
    const eyeClosedIcon = document.getElementById('eyeClosedIcon');
    const generateBtn = document.getElementById('generatePasswordBtn');
    const helpText = document.getElementById('passwordHelpText');

    function setPasswordVisibility(visible) {
        if (!passwordInput) return;
        passwordInput.setAttribute('type', visible ? 'text' : 'password');
        if (eyeOpenIcon && eyeClosedIcon) {
            eyeOpenIcon.style.display = visible ? 'none' : 'inline';
            eyeClosedIcon.style.display = visible ? 'inline' : 'none';
        }
    }

    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            setPasswordVisibility(isPassword);
        });
    }

    if (generateBtn && passwordInput) {
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

            // Shuffle generated password characters
            generated = generated.split('').sort(() => 0.5 - Math.random()).join('');

            passwordInput.value = generated;
            setPasswordVisibility(true); // show generated password so user can see/copy it
            passwordInput.focus();
            passwordInput.select();

            if (helpText) {
                helpText.innerHTML = '<span style="color:var(--success, #10b981);font-weight:600;">✓ Generated secure password (visible above). Make sure to copy it!</span>';
            }
        });
    }
});
</script>
@endsection

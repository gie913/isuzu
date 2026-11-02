@extends('admin.layouts.theme', [
    'title' => 'My Profile',
    'activeMenu' => 'my-profile',
    'breadcrumbsText' => 'Workspace | Profile | My Profile'
])

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Profile</span>
            <h1 class="hero-title">{{ $user->name }}</h1>
            <p class="hero-sub">Manage your account information, role identity, and sign-in credentials.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <button type="submit" form="myProfileForm" class="btn btn--primary">
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
        <section class="col-12 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">Identity Card</span>
                    <h2 class="card-title">My Profile Details</h2>
                </div>
                <span class="badge {{ $user->is_active ? 'success dot' : 'dot' }}">
                    {{ $user->is_active ? __('label.active') : __('label.inactive') }}
                </span>
            </div>

            <form id="myProfileForm" action="{{ route('admin.my-profile.update') }}" method="POST">
                @csrf
                @method('PUT')
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
                            <input id="name" name="name" class="input @error('name') is-invalid @enderror" type="text" value="{{ old('name', $user->name) }}" required>
                        </div>
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Username (Read-only) -->
                    <div class="field">
                        <label class="field-label" for="username">
                            {{ __('form.username') }}
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg>
                            </span>
                            <input id="username" class="input" type="text" value="{{ $user->username ?? '-' }}" readonly disabled style="background: var(--bg-hover, #f8fafc); cursor: not-allowed;">
                        </div>
                        <div class="field-help">Your username is a unique system identifier and cannot be changed.</div>
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
                            <input id="email" name="email" class="input @error('email') is-invalid @enderror" type="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="field-help">Used for authentication and notifications.</div>
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role Name -->
                    <div class="field">
                        <label class="field-label">
                            {{ __('label.role') ?? 'Role' }}
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 0 0-10 10c0 4.1 2.5 7.6 6 9.2M12 6a6 6 0 0 0-6 6c0 2.8 1.8 5.2 4.3 6.1M12 10a2 2 0 0 0-2 2c0 1.5 1 2.7 2.4 2.9M12 2c5.5 0 10 4.5 10 10 0 3-1.3 5.7-3.4 7.5M16 12a4 4 0 0 1-4 4"/></svg>
                            </span>
                            <input class="input" type="text" value="{{ $user->role ? $user->role->name : ($user->m_role_id ?? 'No Role Assigned') }}" readonly disabled style="background: var(--bg-hover, #f8fafc); cursor: not-allowed;">
                        </div>
                        <div class="field-help">Your system role permissions are assigned by administrative authority.</div>
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

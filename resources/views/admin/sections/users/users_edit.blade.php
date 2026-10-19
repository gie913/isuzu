@extends('admin.layouts.theme', [
    'title' => __('label.edit_user'),
    'activeMenu' => 'users',
    'breadcrumbsText' => 'Workspace | ' . __('label.users') . ' | ' . __('label.edit_user')
])

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Users</span>
            <h1 class="hero-title">{{ __('label.edit_user') }}</h1>
            <p class="hero-sub">Update profile information, username, and email for <strong>{{ $user->name }}</strong>.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <button type="submit" form="editUserForm" class="btn btn--primary">
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
                    <h2 class="card-title">Edit account</h2>
                </div>
                <span class="badge {{ $user->is_active ? 'success dot' : 'dot' }}">
                    {{ $user->is_active ? __('label.active') : __('label.inactive') }}
                </span>
            </div>

            <form id="editUserForm" action="{{ route('admin.users.update', $user->id) }}" method="POST">
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

                    <!-- Username -->
                    <div class="field">
                        <label class="field-label" for="username">
                            {{ __('form.username') }}
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg>
                            </span>
                            <input id="username" name="username" class="input @error('username') is-invalid @enderror" type="text" value="{{ old('username', $user->username) }}">
                        </div>
                        @error('username')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="field span-2">
                        <label class="field-label" for="email">
                            {{ __('form.email') }} <span class="req">*</span>
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                            </span>
                            <input id="email" name="email" class="input @error('email') is-invalid @enderror" type="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="field-help">Used for sign-in and account notifications.</div>
                        @error('email')
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

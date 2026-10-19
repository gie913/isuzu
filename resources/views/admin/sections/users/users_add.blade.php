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
                    <div class="field span-2">
                        <label class="field-label" for="email">
                            {{ __('form.email') }} <span class="req">*</span>
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                            </span>
                            <input id="email" name="email" class="input @error('email') is-invalid @enderror" type="email" placeholder="john@example.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="field-help">Used for sign-in, system alerts, and notification dispatches.</div>
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="field span-2">
                        <label class="field-label" for="password">
                            {{ __('form.password') }} <span class="req">*</span>
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </span>
                            <input id="password" name="password" class="input @error('password') is-invalid @enderror" type="password" placeholder="Minimum 8 characters" required minlength="8">
                        </div>
                        <div class="field-help">Must be at least 8 characters long.</div>
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

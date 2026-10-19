@extends('admin.layouts.theme', [
    'title' => 'User Details',
    'activeMenu' => 'users',
    'breadcrumbsText' => 'Workspace | ' . __('label.users') . ' | User Details'
])

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Users</span>
            <h1 class="hero-title">{{ $user->name }}</h1>
            <p class="hero-sub">Detailed profile, identity record, and active permissions.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
                {{ __('label.edit_user') }}
            </a>
        </div>
    </section>

    <div class="grid">
        <section class="col-12 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">Identity Card</span>
                    <h2 class="card-title">User overview</h2>
                </div>
                @if($user->is_active)
                    <span class="badge success dot">{{ __('label.active') }}</span>
                @else
                    <span class="badge dot" style="color:var(--t-light)">{{ __('label.inactive') }}</span>
                @endif
            </div>

            <div class="form-grid">
                <div class="field">
                    <label class="field-label">{{ __('label.name') }}</label>
                    <input class="input" type="text" value="{{ $user->name }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">{{ __('form.username') }}</label>
                    <input class="input" type="text" value="{{ $user->username ?? '-' }}" readonly disabled>
                </div>

                <div class="field span-2">
                    <label class="field-label">{{ __('form.email') }}</label>
                    <div class="input-icon">
                        <span class="ico">
                            <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        </span>
                        <input class="input" type="email" value="{{ $user->email }}" readonly disabled>
                    </div>
                </div>

                <div class="field">
                    <label class="field-label">User ID (UUID)</label>
                    <input class="input" type="text" value="{{ $user->id }}" readonly disabled style="font-family: var(--font-mono, monospace); font-size: 12px;">
                </div>

                <div class="field">
                    <label class="field-label">Role ID</label>
                    <input class="input" type="text" value="{{ $user->m_role_id ?? '-' }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">{{ __('label.created_at') }}</label>
                    <input class="input" type="text" value="{{ $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '-' }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">Last Updated</label>
                    <input class="input" type="text" value="{{ $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : '-' }}" readonly disabled>
                </div>
            </div>

            <div class="form-actions" style="margin-top: 24px;">
                <a href="{{ route('admin.users.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                <span class="spacer"></span>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn--primary">{{ __('label.edit_user') }}</a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--ghost" style="color:var(--danger, #ef4444); border-color: rgba(239, 68, 68, 0.2);">
                        Delete User
                    </button>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection

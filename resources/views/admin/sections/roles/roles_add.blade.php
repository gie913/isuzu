@extends('admin.layouts.theme', [
    'title' => 'Add Role',
    'activeMenu' => 'roles',
    'breadcrumbsText' => 'Workspace | Roles | Add Role'
])

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Roles</span>
            <h1 class="hero-title">Add Role</h1>
            <p class="hero-sub">Create a new record in Roles.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.roles.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <button type="submit" form="addForm" class="btn btn--primary">
                {{ __('form.submit') }}
            </button>
        </div>
    </section>

    @if ($errors->any())
        <div class="alert danger" style="margin-bottom: 20px;">
            <span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
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
                    <span class="eyebrow">Information</span>
                    <h2 class="card-title">Role details</h2>
                </div>
            </div>

            <form id="addForm" action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="code">
                            Code <span class="req">*</span>
                        </label>
                        <input id="code" name="code" class="input @error('code') is-invalid @enderror" type="text" placeholder="e.g. admin, manager, staff" value="{{ old('code') }}" required>
                        <div class="field-help">Unique role identifier code.</div>
                        @error('code')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-label" for="name">
                            {{ __('form.name') }} <span class="req">*</span>
                        </label>
                        <input id="name" name="name" class="input @error('name') is-invalid @enderror" type="text" placeholder="e.g. Administrator, Manager" value="{{ old('name') }}" required>
                        <div class="field-help">Display name of the role.</div>
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-label" for="order_number">
                            Order Number <span class="req">*</span>
                        </label>
                        <input id="order_number" name="order_number" class="input @error('order_number') is-invalid @enderror" type="number" placeholder="e.g. 1, 2, 3" value="{{ old('order_number', 1) }}" required>
                        <div class="field-help">Priority sequence for role ordering.</div>
                        @error('order_number')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-label" for="is_verifier">Is Verifier</label>
                        <select id="is_verifier" name="is_verifier" class="input @error('is_verifier') is-invalid @enderror">
                            <option value="0" {{ old('is_verifier', 0) == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_verifier') == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                        <div class="field-help">Indicates if this role has verifier privileges.</div>
                        @error('is_verifier')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                    <span class="spacer"></span>
                    <button type="submit" class="btn btn--primary">{{ __('form.submit') }}</button>
                </div>
            </form>
        </section>
    </div>
</main>
@endsection
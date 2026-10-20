@extends('admin.layouts.theme', [
    'title' => 'Edit Module',
    'activeMenu' => 'modules',
    'breadcrumbsText' => 'Workspace | Modules | Edit Module'
])

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Modules</span>
            <h1 class="hero-title">Edit Module</h1>
            <p class="hero-sub">Update record details for <strong>{{ $item->label ?? $item->name }}</strong>.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.modules.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <button type="submit" form="editForm" class="btn btn--primary">
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
                    <span class="eyebrow">Configuration</span>
                    <h2 class="card-title">Edit Module Details</h2>
                </div>
                <span class="badge {{ $item->is_active ? 'success dot' : 'dot' }}">
                    {{ $item->is_active ? __('label.active') : __('label.inactive') }}
                </span>
            </div>

            <form id="editForm" action="{{ route('admin.modules.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Hidden ID field -->
                <input type="hidden" name="id" value="{{ $item->id }}">

                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="name">
                            Module Code / Key <span class="req">*</span>
                        </label>
                        <input id="name" name="name" class="input @error('name') is-invalid @enderror" type="text" value="{{ old('name', $item->name) }}" required>
                        <div class="field-help">Unique machine identifier used for permissions check.</div>
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-label" for="label">
                            Display Label <span class="req">*</span>
                        </label>
                        <input id="label" name="label" class="input @error('label') is-invalid @enderror" type="text" value="{{ old('label', $item->label) }}" required>
                        <div class="field-help">Human-readable label shown in navigation and headers.</div>
                        @error('label')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-label" for="parent_id">Parent Module</label>
                        <select id="parent_id" name="parent_id" class="input @error('parent_id') is-invalid @enderror">
                            <option value="">-- Root Level (No Parent) --</option>
                            @foreach($modules as $m)
                                @if($m->id !== $item->id)
                                    <option value="{{ $m->id }}" {{ old('parent_id', $item->parent_id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->label ?? $m->name }} ({{ $m->name }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <div class="field-help">Select a parent to nest this module in the tree hierarchy.</div>
                        @error('parent_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-label" for="sort_number">Sort Number</label>
                        <input id="sort_number" name="sort_number" class="input @error('sort_number') is-invalid @enderror" type="number" step="0.1" value="{{ old('sort_number', $item->sort_number) }}">
                        <div class="field-help">Ordering priority in the navigation list.</div>
                        @error('sort_number')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-label" for="path">Route Path / URL</label>
                        <input id="path" name="path" class="input @error('path') is-invalid @enderror" type="text" value="{{ old('path', $item->path) }}">
                        <div class="field-help">URL slug or route path (or '#' for pure parent folders).</div>
                        @error('path')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-label" for="icon">Icon Identifier</label>
                        <input id="icon" name="icon" class="input @error('icon') is-invalid @enderror" type="text" value="{{ old('icon', $item->icon) }}">
                        <div class="field-help">Icon class or identifier used in the sidebar.</div>
                        @error('icon')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field span-2">
                        <label class="field-label" for="resource_class">Resource / Controller Class</label>
                        <input id="resource_class" name="resource_class" class="input @error('resource_class') is-invalid @enderror" type="text" value="{{ old('resource_class', $item->resource_class) }}">
                        <div class="field-help">Optional controller class reference or API resource class name.</div>
                        @error('resource_class')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field span-2">
                        <label class="check" style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="is_menubar" value="1" {{ old('is_menubar', $item->is_menubar) ? 'checked' : '' }}>
                            <span class="box"></span>
                            <span style="font-weight: 600; font-size: 13.5px; color: var(--t-base);">Show in Navigation Menubar</span>
                        </label>
                        <div class="field-help" style="margin-left: 28px;">When enabled, this module appears in the sidebar and navigation menus.</div>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.modules.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                    <span class="spacer"></span>
                    <button type="submit" class="btn btn--primary">{{ __('form.submit') }}</button>
                </div>
            </form>
        </section>
    </div>
</main>
@endsection
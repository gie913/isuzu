@extends('admin.layouts.theme', [
    'title' => 'Module Details',
    'activeMenu' => 'modules',
    'breadcrumbsText' => 'Workspace | Modules | Details'
])

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Modules</span>
            <h1 class="hero-title">{{ $item->label ?? $item->name }}</h1>
            <p class="hero-sub">Complete metadata and module configuration overview.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.modules.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <a href="{{ route('admin.modules.edit', $item->id) }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
                Edit Module
            </a>
        </div>
    </section>

    <div class="grid">
        <section class="col-12 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">Identity Card</span>
                    <h2 class="card-title">Module Information</h2>
                </div>
                <div style="display: flex; gap: 8px;">
                    @if($item->is_menubar)
                        <span class="badge neutral">Menubar Item</span>
                    @endif
                    @if($item->is_active)
                        <span class="badge success dot">{{ __('label.active') }}</span>
                    @else
                        <span class="badge dot" style="color:var(--t-light)">{{ __('label.inactive') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label class="field-label">Module Code / Key</label>
                    <input class="input" type="text" value="{{ $item->name }}" readonly disabled style="font-family: var(--font-mono, monospace);">
                </div>

                <div class="field">
                    <label class="field-label">Display Label</label>
                    <input class="input" type="text" value="{{ $item->label ?? '-' }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">Parent Module</label>
                    <input class="input" type="text" value="{{ $item->parent ? ($item->parent->label ?? $item->parent->name) . ' (' . $item->parent->name . ')' : 'None (Root Level)' }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">Sort Number</label>
                    <input class="input" type="text" value="{{ $item->sort_number ?? '-' }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">Route Path / URL</label>
                    <input class="input" type="text" value="{{ $item->path ?? '-' }}" readonly disabled style="font-family: var(--font-mono, monospace);">
                </div>

                <div class="field">
                    <label class="field-label">Icon Identifier</label>
                    <input class="input" type="text" value="{{ $item->icon ?? '-' }}" readonly disabled>
                </div>

                <div class="field span-2">
                    <label class="field-label">Resource / Controller Class</label>
                    <input class="input" type="text" value="{{ $item->resource_class ?? '-' }}" readonly disabled style="font-family: var(--font-mono, monospace);">
                </div>

                <!-- Audit & System Meta Separator -->
                <div class="field span-2" style="margin-top: 10px; padding-top: 16px; border-top: 1px dashed var(--border, #e2e8f0);">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span class="eyebrow" style="color: var(--t-light, #64748b);">Audit & System Information</span>
                    </div>
                </div>

                <div class="field">
                    <label class="field-label">{{ __('label.created_at') }}</label>
                    <input class="input" type="text" value="{{ $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '-' }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">Created By</label>
                    <input class="input" type="text" value="{{ $item->creator ? ($item->creator->name . ($item->creator->username ? ' (@' . $item->creator->username . ')' : '')) : ($item->created_by ?? '-') }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">Updated At</label>
                    <input class="input" type="text" value="{{ $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : '-' }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">Updated By</label>
                    <input class="input" type="text" value="{{ $item->updater ? ($item->updater->name . ($item->updater->username ? ' (@' . $item->updater->username . ')' : '')) : ($item->updated_by ?? '-') }}" readonly disabled>
                </div>
            </div>

            <div class="form-actions" style="margin-top: 24px;">
                <a href="{{ route('admin.modules.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                <span class="spacer"></span>
                <a href="{{ route('admin.modules.edit', $item->id) }}" class="btn btn--primary">Edit Module</a>
                <form action="{{ route('admin.modules.destroy', $item->id) }}" method="POST" class="form-delete-swal" data-record-name="{{ $item->label ?? $item->name ?? 'Module' }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--ghost" style="color:var(--danger, #ef4444); border-color: rgba(239, 68, 68, 0.2);">
                        Delete Module
                    </button>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection
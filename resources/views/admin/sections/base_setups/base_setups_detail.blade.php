@extends('admin.layouts.theme', [
    'title' => 'Base Setup Details',
    'activeMenu' => 'base_setups',
    'breadcrumbsText' => 'Workspace | Base Setups | Details'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Base Setups</span>
            <h1 class="hero-title">{{ $item->name ?? 'Record' }}</h1>
            <p class="hero-sub">Complete details and metadata overview.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.base_setups.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <a href="{{ route('admin.base_setups.edit', $item->id) }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
                Edit Base Setup
            </a>
        </div>
    </section>

    <div class="grid">
        <section class="col-12 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">Identity Card</span>
                    <h2 class="card-title">Base Setup overview</h2>
                </div>
                @if($item->is_active)
                    <span class="badge success dot">{{ __('label.active') }}</span>
                @else
                    <span class="badge dot" style="color:var(--t-light)">{{ __('label.inactive') }}</span>
                @endif
            </div>

            <div class="form-grid">
                <div class="field">
                    <label class="field-label">Title</label>
                    <input class="input" type="text" value="{{ $item->title }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Meta Description</label>
                    <input class="input" type="text" value="{{ $item->meta_description }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Meta Keyword</label>
                    <input class="input" type="text" value="{{ $item->meta_keyword }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Long Description</label>
                    <input class="input" type="text" value="{{ $item->long_description }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Using Dual Lang</label>
                    <input class="input" type="text" value="{{ $item->using_dual_lang }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Default Lang</label>
                    <input class="input" type="text" value="{{ $item->default_lang }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Primary Image Src</label>
                    <input class="input" type="text" value="{{ $item->primary_image_src }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Secondary Image Src</label>
                    <input class="input" type="text" value="{{ $item->secondary_image_src }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Alternative1 Image Src</label>
                    <input class="input" type="text" value="{{ $item->alternative1_image_src }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Alternative2 Image Src</label>
                    <input class="input" type="text" value="{{ $item->alternative2_image_src }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Alternative Image Src</label>
                    <input class="input" type="text" value="{{ $item->alternative_image_src }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">X Icon Src</label>
                    <input class="input" type="text" value="{{ $item->x_icon_src }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Author</label>
                    <input class="input" type="text" value="{{ $item->author }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Address</label>
                    <input class="input" type="text" value="{{ $item->address }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Contact</label>
                    <input class="input" type="text" value="{{ $item->contact }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Dashboard Theme Config</label>
                    <input class="input" type="text" value="{{ $item->dashboard_theme_config }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Asset Version</label>
                    <input class="input" type="text" value="{{ $item->asset_version }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Additional Scripts</label>
                    <input class="input" type="text" value="{{ $item->additional_scripts }}" readonly disabled>
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
                <a href="{{ route('admin.base_setups.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                <span class="spacer"></span>
                <a href="{{ route('admin.base_setups.edit', $item->id) }}" class="btn btn--primary">Edit Base Setup</a>
                <form action="{{ route('admin.base_setups.destroy', $item->id) }}" method="POST" class="form-delete-swal" data-record-name="{{ $item->name ?? 'Base Setup' }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--ghost" style="color:var(--danger, #ef4444); border-color: rgba(239, 68, 68, 0.2);">
                        Delete Base Setup
                    </button>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection

@section('js')
@endsection
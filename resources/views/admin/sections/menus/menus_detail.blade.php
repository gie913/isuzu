@extends('admin.layouts.theme', [
    'title' => 'Menu Details',
    'activeMenu' => 'menus',
    'breadcrumbsText' => 'Workspace | Menus | Details'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Menus</span>
            <h1 class="hero-title">{{ $item->name ?? 'Record' }}</h1>
            <p class="hero-sub">Complete details and metadata overview.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.menus.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <a href="{{ route('admin.menus.edit', $item->id) }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
                Edit Menu
            </a>
        </div>
    </section>

    <div class="grid">
        <section class="col-12 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">Identity Card</span>
                    <h2 class="card-title">Menu overview</h2>
                </div>
                @if($item->is_active)
                    <span class="badge success dot">{{ __('label.active') }}</span>
                @else
                    <span class="badge dot" style="color:var(--t-light)">{{ __('label.inactive') }}</span>
                @endif
            </div>

            <div class="form-grid">
                <div class="field">
                    <label class="field-label">Code</label>
                    <input class="input" type="text" value="{{ $item->code }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Name</label>
                    <input class="input" type="text" value="{{ $item->name }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Redirect To</label>
                    <input class="input" type="text" value="{{ $item->redirect_to }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Type</label>
                    <input class="input" type="text" value="{{ $item->type }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Category</label>
                    <input class="input" type="text" value="{{ $item->category }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">T Page Id</label>
                    <input class="input" type="text" value="{{ $item->t_page_id }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Sort Number</label>
                    <input class="input" type="text" value="{{ $item->sort_number }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Is Devider</label>
                    <input class="input" type="text" value="{{ $item->is_devider }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Is Submenu</label>
                    <input class="input" type="text" value="{{ $item->is_submenu }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Submenu From Id</label>
                    <input class="input" type="text" value="{{ $item->submenu_from_id }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Icon</label>
                    <input class="input" type="text" value="{{ $item->icon }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Submenu Level</label>
                    <input class="input" type="text" value="{{ $item->submenu_level }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Text</label>
                    <input class="input" type="text" value="{{ $item->text }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Text Lang Eng</label>
                    <input class="input" type="text" value="{{ $item->text_lang_eng }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Text Lang Ina</label>
                    <input class="input" type="text" value="{{ $item->text_lang_ina }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Slug</label>
                    <input class="input" type="text" value="{{ $item->slug }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Is Published</label>
                    <input class="input" type="text" value="{{ $item->is_published }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Is Footer</label>
                    <input class="input" type="text" value="{{ $item->is_footer }}" readonly disabled>
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
                <a href="{{ route('admin.menus.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                <span class="spacer"></span>
                <a href="{{ route('admin.menus.edit', $item->id) }}" class="btn btn--primary">Edit Menu</a>
                <form action="{{ route('admin.menus.destroy', $item->id) }}" method="POST" class="form-delete-swal" data-record-name="{{ $item->name ?? 'Menu' }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--ghost" style="color:var(--danger, #ef4444); border-color: rgba(239, 68, 68, 0.2);">
                        Delete Menu
                    </button>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection

@section('js')
@endsection
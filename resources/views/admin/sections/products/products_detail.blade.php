@extends('admin.layouts.theme', [
    'title' => 'Products Details',
    'activeMenu' => 'products',
    'breadcrumbsText' => 'Workspace | Products | Details'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Products</span>
            <h1 class="hero-title">{{ $item->name ?? 'Record' }}</h1>
            <p class="hero-sub">Complete details and metadata overview.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <a href="{{ route('admin.products.edit', $item->id) }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
                Edit Products
            </a>
        </div>
    </section>

    <div class="grid">
        <section class="col-12 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">Identity Card</span>
                    <h2 class="card-title">Products overview</h2>
                </div>
                @if($item->is_active)
                    <span class="badge success dot">{{ __('label.active') }}</span>
                @else
                    <span class="badge dot" style="color:var(--t-light)">{{ __('label.inactive') }}</span>
                @endif
            </div>

            <div class="form-grid">
                <div class="field">
                    <label class="field-label">Slug</label>
                    <input class="input" type="text" value="{{ $item->slug }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Meta Description</label>
                    <input class="input" type="text" value="{{ $item->meta_description }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Name</label>
                    <input class="input" type="text" value="{{ $item->name }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">M Product Type Id</label>
                    <input class="input" type="text" value="{{ $item->m_product_type_id }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">M Product Category Id</label>
                    <input class="input" type="text" value="{{ $item->m_product_category_id }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Wheel Configuration Id</label>
                    <input class="input" type="text" value="{{ $item->wheel_configuration_id }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Load Capacity Id</label>
                    <input class="input" type="text" value="{{ $item->load_capacity_id }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Wheelbase Id</label>
                    <input class="input" type="text" value="{{ $item->wheelbase_id }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Horse Power Id</label>
                    <input class="input" type="text" value="{{ $item->horse_power_id }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Seating Capacity Id</label>
                    <input class="input" type="text" value="{{ $item->seating_capacity_id }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Production Year</label>
                    <input class="input" type="text" value="{{ $item->production_year }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Price</label>
                    <input class="input" type="text" value="{{ $item->price }}" readonly disabled>
                </div>
                <div class="field span-2">
                    <label class="field-label">Description</label>
                    <textarea class="textarea" readonly disabled>{{ $item->description ?? '-' }}</textarea>
                </div>
                <div class="field">
                    <label class="field-label">Image1 Src</label>
                    <input class="input" type="text" value="{{ $item->image1_src }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Image2 Src</label>
                    <input class="input" type="text" value="{{ $item->image2_src }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Image3 Src</label>
                    <input class="input" type="text" value="{{ $item->image3_src }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Image4 Src</label>
                    <input class="input" type="text" value="{{ $item->image4_src }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Image5 Src</label>
                    <input class="input" type="text" value="{{ $item->image5_src }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">File</label>
                    <input class="input" type="text" value="{{ $item->file }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Tag</label>
                    <input class="input" type="text" value="{{ $item->tag }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Is Published</label>
                    <input class="input" type="text" value="{{ $item->is_published }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Published At</label>
                    <input class="input" type="text" value="{{ $item->published_at }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Published By</label>
                    <input class="input" type="text" value="{{ $item->published_by }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Published By Name</label>
                    <input class="input" type="text" value="{{ $item->published_by_name }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Show As Banner</label>
                    <input class="input" type="text" value="{{ $item->show_as_banner }}" readonly disabled>
                </div>
                <div class="field">
                    <label class="field-label">Total Views</label>
                    <input class="input" type="text" value="{{ $item->total_views }}" readonly disabled>
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
                <a href="{{ route('admin.products.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                <span class="spacer"></span>
                <a href="{{ route('admin.products.edit', $item->id) }}" class="btn btn--primary">Edit Products</a>
                <form action="{{ route('admin.products.destroy', $item->id) }}" method="POST" class="form-delete-swal" data-record-name="{{ $item->name ?? 'Products' }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--ghost" style="color:var(--danger, #ef4444); border-color: rgba(239, 68, 68, 0.2);">
                        Delete Products
                    </button>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection

@section('js')
@endsection
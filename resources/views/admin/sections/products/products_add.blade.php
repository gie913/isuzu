@extends('admin.layouts.theme', [
    'title' => 'Add Products',
    'activeMenu' => 'products',
    'breadcrumbsText' => 'Workspace | Products | Add Products'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Products</span>
            <h1 class="hero-title">Add Products</h1>
            <p class="hero-sub">Create a new record in Products.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">Products details</h2>
                </div>
            </div>

            <form id="addForm" action="{{ route('admin.products.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="slug">
                            Slug <span class="req">*</span>
                        </label>
                        <input id="slug" name="slug" class="input @error('slug') is-invalid @enderror" type="text" placeholder="Enter Slug" value="{{ old('slug') }}" required>
                        @error('slug')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="meta_description">
                            Meta Description <span class="req">*</span>
                        </label>
                        <input id="meta_description" name="meta_description" class="input @error('meta_description') is-invalid @enderror" type="text" placeholder="Enter Meta Description" value="{{ old('meta_description') }}" required>
                        @error('meta_description')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="name">
                            Name <span class="req">*</span>
                        </label>
                        <input id="name" name="name" class="input @error('name') is-invalid @enderror" type="text" placeholder="Enter Name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="m_product_type_id">
                            M Product Type Id <span class="req">*</span>
                        </label>
                        <input id="m_product_type_id" name="m_product_type_id" class="input @error('m_product_type_id') is-invalid @enderror" type="text" placeholder="Enter M Product Type Id" value="{{ old('m_product_type_id') }}" required>
                        @error('m_product_type_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="m_product_category_id">
                            M Product Category Id <span class="req">*</span>
                        </label>
                        <input id="m_product_category_id" name="m_product_category_id" class="input @error('m_product_category_id') is-invalid @enderror" type="text" placeholder="Enter M Product Category Id" value="{{ old('m_product_category_id') }}" required>
                        @error('m_product_category_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="wheel_configuration_id">
                            Wheel Configuration Id <span class="req">*</span>
                        </label>
                        <input id="wheel_configuration_id" name="wheel_configuration_id" class="input @error('wheel_configuration_id') is-invalid @enderror" type="text" placeholder="Enter Wheel Configuration Id" value="{{ old('wheel_configuration_id') }}" required>
                        @error('wheel_configuration_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="load_capacity_id">
                            Load Capacity Id <span class="req">*</span>
                        </label>
                        <input id="load_capacity_id" name="load_capacity_id" class="input @error('load_capacity_id') is-invalid @enderror" type="text" placeholder="Enter Load Capacity Id" value="{{ old('load_capacity_id') }}" required>
                        @error('load_capacity_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="wheelbase_id">
                            Wheelbase Id <span class="req">*</span>
                        </label>
                        <input id="wheelbase_id" name="wheelbase_id" class="input @error('wheelbase_id') is-invalid @enderror" type="text" placeholder="Enter Wheelbase Id" value="{{ old('wheelbase_id') }}" required>
                        @error('wheelbase_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="horse_power_id">
                            Horse Power Id <span class="req">*</span>
                        </label>
                        <input id="horse_power_id" name="horse_power_id" class="input @error('horse_power_id') is-invalid @enderror" type="text" placeholder="Enter Horse Power Id" value="{{ old('horse_power_id') }}" required>
                        @error('horse_power_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="seating_capacity_id">
                            Seating Capacity Id <span class="req">*</span>
                        </label>
                        <input id="seating_capacity_id" name="seating_capacity_id" class="input @error('seating_capacity_id') is-invalid @enderror" type="text" placeholder="Enter Seating Capacity Id" value="{{ old('seating_capacity_id') }}" required>
                        @error('seating_capacity_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="production_year">
                            Production Year <span class="req">*</span>
                        </label>
                        <input id="production_year" name="production_year" class="input @error('production_year') is-invalid @enderror" type="text" placeholder="Enter Production Year" value="{{ old('production_year') }}" required>
                        @error('production_year')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="price">
                            Price <span class="req">*</span>
                        </label>
                        <input id="price" name="price" class="input @error('price') is-invalid @enderror" type="text" placeholder="Enter Price" value="{{ old('price') }}" required>
                        @error('price')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field span-2">
                        <label class="field-label" for="description">Description</label>
                        <textarea id="description" name="description" class="textarea @error('description') is-invalid @enderror" placeholder="Optional details...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="image1_src">
                            Image1 Src <span class="req">*</span>
                        </label>
                        <input id="image1_src" name="image1_src" class="input @error('image1_src') is-invalid @enderror" type="text" placeholder="Enter Image1 Src" value="{{ old('image1_src') }}" required>
                        @error('image1_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="image2_src">
                            Image2 Src <span class="req">*</span>
                        </label>
                        <input id="image2_src" name="image2_src" class="input @error('image2_src') is-invalid @enderror" type="text" placeholder="Enter Image2 Src" value="{{ old('image2_src') }}" required>
                        @error('image2_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="image3_src">
                            Image3 Src <span class="req">*</span>
                        </label>
                        <input id="image3_src" name="image3_src" class="input @error('image3_src') is-invalid @enderror" type="text" placeholder="Enter Image3 Src" value="{{ old('image3_src') }}" required>
                        @error('image3_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="image4_src">
                            Image4 Src <span class="req">*</span>
                        </label>
                        <input id="image4_src" name="image4_src" class="input @error('image4_src') is-invalid @enderror" type="text" placeholder="Enter Image4 Src" value="{{ old('image4_src') }}" required>
                        @error('image4_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="image5_src">
                            Image5 Src <span class="req">*</span>
                        </label>
                        <input id="image5_src" name="image5_src" class="input @error('image5_src') is-invalid @enderror" type="text" placeholder="Enter Image5 Src" value="{{ old('image5_src') }}" required>
                        @error('image5_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="file">
                            File <span class="req">*</span>
                        </label>
                        <input id="file" name="file" class="input @error('file') is-invalid @enderror" type="text" placeholder="Enter File" value="{{ old('file') }}" required>
                        @error('file')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="tag">
                            Tag <span class="req">*</span>
                        </label>
                        <input id="tag" name="tag" class="input @error('tag') is-invalid @enderror" type="text" placeholder="Enter Tag" value="{{ old('tag') }}" required>
                        @error('tag')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="is_published">
                            Is Published <span class="req">*</span>
                        </label>
                        <input id="is_published" name="is_published" class="input @error('is_published') is-invalid @enderror" type="text" placeholder="Enter Is Published" value="{{ old('is_published') }}" required>
                        @error('is_published')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="published_at">
                            Published At <span class="req">*</span>
                        </label>
                        <input id="published_at" name="published_at" class="input @error('published_at') is-invalid @enderror" type="text" placeholder="Enter Published At" value="{{ old('published_at') }}" required>
                        @error('published_at')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="published_by">
                            Published By <span class="req">*</span>
                        </label>
                        <input id="published_by" name="published_by" class="input @error('published_by') is-invalid @enderror" type="text" placeholder="Enter Published By" value="{{ old('published_by') }}" required>
                        @error('published_by')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="published_by_name">
                            Published By Name <span class="req">*</span>
                        </label>
                        <input id="published_by_name" name="published_by_name" class="input @error('published_by_name') is-invalid @enderror" type="text" placeholder="Enter Published By Name" value="{{ old('published_by_name') }}" required>
                        @error('published_by_name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="show_as_banner">
                            Show As Banner <span class="req">*</span>
                        </label>
                        <input id="show_as_banner" name="show_as_banner" class="input @error('show_as_banner') is-invalid @enderror" type="text" placeholder="Enter Show As Banner" value="{{ old('show_as_banner') }}" required>
                        @error('show_as_banner')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="total_views">
                            Total Views <span class="req">*</span>
                        </label>
                        <input id="total_views" name="total_views" class="input @error('total_views') is-invalid @enderror" type="text" placeholder="Enter Total Views" value="{{ old('total_views') }}" required>
                        @error('total_views')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.products.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                    <span class="spacer"></span>
                    <button type="submit" class="btn btn--primary">{{ __('form.submit') }}</button>
                </div>
            </form>
        </section>
    </div>
</main>
@endsection

@section('js')
@endsection
@extends('admin.layouts.theme', [
    'title' => 'Add Testimonial',
    'activeMenu' => 'testimonials',
    'breadcrumbsText' => 'Workspace | Testimonials | Add Testimonial'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Testimonials</span>
            <h1 class="hero-title">Add Testimonial</h1>
            <p class="hero-sub">Create a new record in Testimonials.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">Testimonial details</h2>
                </div>
            </div>

            <form id="addForm" action="{{ route('admin.testimonials.store') }}" method="POST">
                @csrf
                <div class="form-grid">
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
                        <label class="field-label" for="occupation">
                            Occupation <span class="req">*</span>
                        </label>
                        <input id="occupation" name="occupation" class="input @error('occupation') is-invalid @enderror" type="text" placeholder="Enter Occupation" value="{{ old('occupation') }}" required>
                        @error('occupation')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="sort_number">
                            Sort Number <span class="req">*</span>
                        </label>
                        <input id="sort_number" name="sort_number" class="input @error('sort_number') is-invalid @enderror" type="text" placeholder="Enter Sort Number" value="{{ old('sort_number') }}" required>
                        @error('sort_number')
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
                        <label class="field-label" for="image">
                            Image <span class="req">*</span>
                        </label>
                        <input id="image" name="image" class="input @error('image') is-invalid @enderror" type="text" placeholder="Enter Image" value="{{ old('image') }}" required>
                        @error('image')
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
                        <label class="field-label" for="published_by_name">
                            Published By Name <span class="req">*</span>
                        </label>
                        <input id="published_by_name" name="published_by_name" class="input @error('published_by_name') is-invalid @enderror" type="text" placeholder="Enter Published By Name" value="{{ old('published_by_name') }}" required>
                        @error('published_by_name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.testimonials.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
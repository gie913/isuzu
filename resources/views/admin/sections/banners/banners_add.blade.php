@extends('admin.layouts.theme', [
    'title' => 'Add Banner',
    'activeMenu' => 'banners',
    'breadcrumbsText' => 'Workspace | Banners | Add Banner'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Banners</span>
            <h1 class="hero-title">Add Banner</h1>
            <p class="hero-sub">Create a new record in Banners.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.banners.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">Banner details</h2>
                </div>
            </div>

            <form id="addForm" action="{{ route('admin.banners.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="title">
                            Title <span class="req">*</span>
                        </label>
                        <input id="title" name="title" class="input @error('title') is-invalid @enderror" type="text" placeholder="Enter Title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="subtitle">
                            Subtitle <span class="req">*</span>
                        </label>
                        <input id="subtitle" name="subtitle" class="input @error('subtitle') is-invalid @enderror" type="text" placeholder="Enter Subtitle" value="{{ old('subtitle') }}" required>
                        @error('subtitle')
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
                        <label class="field-label" for="image_large">
                            Image Large <span class="req">*</span>
                        </label>
                        <input id="image_large" name="image_large" class="input @error('image_large') is-invalid @enderror" type="text" placeholder="Enter Image Large" value="{{ old('image_large') }}" required>
                        @error('image_large')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="image_small">
                            Image Small <span class="req">*</span>
                        </label>
                        <input id="image_small" name="image_small" class="input @error('image_small') is-invalid @enderror" type="text" placeholder="Enter Image Small" value="{{ old('image_small') }}" required>
                        @error('image_small')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="link">
                            Link <span class="req">*</span>
                        </label>
                        <input id="link" name="link" class="input @error('link') is-invalid @enderror" type="text" placeholder="Enter Link" value="{{ old('link') }}" required>
                        @error('link')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="link_text">
                            Link Text <span class="req">*</span>
                        </label>
                        <input id="link_text" name="link_text" class="input @error('link_text') is-invalid @enderror" type="text" placeholder="Enter Link Text" value="{{ old('link_text') }}" required>
                        @error('link_text')
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
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.banners.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
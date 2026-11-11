@extends('admin.layouts.theme', [
    'title' => 'Add Page',
    'activeMenu' => 'pages',
    'breadcrumbsText' => 'Workspace | Pages | Add Page'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Pages</span>
            <h1 class="hero-title">Add Page</h1>
            <p class="hero-sub">Create a new record in Pages.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.pages.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">Page details</h2>
                </div>
            </div>

            <form id="addForm" action="{{ route('admin.pages.store') }}" method="POST">
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
                        <label class="field-label" for="layout">
                            Layout <span class="req">*</span>
                        </label>
                        <input id="layout" name="layout" class="input @error('layout') is-invalid @enderror" type="text" placeholder="Enter Layout" value="{{ old('layout') }}" required>
                        @error('layout')
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
                        <label class="field-label" for="background">
                            Background <span class="req">*</span>
                        </label>
                        <input id="background" name="background" class="input @error('background') is-invalid @enderror" type="text" placeholder="Enter Background" value="{{ old('background') }}" required>
                        @error('background')
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
                    <a href="{{ route('admin.pages.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
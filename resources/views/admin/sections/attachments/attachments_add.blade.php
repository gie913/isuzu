@extends('admin.layouts.theme', [
    'title' => 'Add Attachment',
    'activeMenu' => 'attachments',
    'breadcrumbsText' => 'Workspace | Attachments | Add Attachment'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Attachments</span>
            <h1 class="hero-title">Add Attachment</h1>
            <p class="hero-sub">Create a new record in Attachments.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.attachments.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">Attachment details</h2>
                </div>
            </div>

            <form id="addForm" action="{{ route('admin.attachments.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="category">
                            Category <span class="req">*</span>
                        </label>
                        <input id="category" name="category" class="input @error('category') is-invalid @enderror" type="text" placeholder="Enter Category" value="{{ old('category') }}" required>
                        @error('category')
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
                        <label class="field-label" for="file">
                            File <span class="req">*</span>
                        </label>
                        <input id="file" name="file" class="input @error('file') is-invalid @enderror" type="text" placeholder="Enter File" value="{{ old('file') }}" required>
                        @error('file')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="period_month">
                            Period Month <span class="req">*</span>
                        </label>
                        <input id="period_month" name="period_month" class="input @error('period_month') is-invalid @enderror" type="text" placeholder="Enter Period Month" value="{{ old('period_month') }}" required>
                        @error('period_month')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="period_year">
                            Period Year <span class="req">*</span>
                        </label>
                        <input id="period_year" name="period_year" class="input @error('period_year') is-invalid @enderror" type="text" placeholder="Enter Period Year" value="{{ old('period_year') }}" required>
                        @error('period_year')
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
                    <a href="{{ route('admin.attachments.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
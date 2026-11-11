@extends('admin.layouts.theme', [
    'title' => 'Edit Content',
    'activeMenu' => 'contents',
    'breadcrumbsText' => 'Workspace | Contents | Edit Content'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Contents</span>
            <h1 class="hero-title">Edit Content</h1>
            <p class="hero-sub">Update record details for <strong>{{ $item->name ?? 'Record' }}</strong>.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.contents.index') }}" class="btn btn--ghost">
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
                    <span class="eyebrow">Information</span>
                    <h2 class="card-title">Edit Content</h2>
                </div>
                <span class="badge {{ $item->is_active ? 'success dot' : 'dot' }}">
                    {{ $item->is_active ? __('label.active') : __('label.inactive') }}
                </span>
            </div>

            <form id="editForm" action="{{ route('admin.contents.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="slug">
                            Slug <span class="req">*</span>
                        </label>
                        <input id="slug" name="slug" class="input @error('slug') is-invalid @enderror" type="text" value="{{ old('slug', $item->slug) }}" required>
                        @error('slug')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="t_page_id">
                            T Page Id <span class="req">*</span>
                        </label>
                        <input id="t_page_id" name="t_page_id" class="input @error('t_page_id') is-invalid @enderror" type="text" value="{{ old('t_page_id', $item->t_page_id) }}" required>
                        @error('t_page_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="meta_description">
                            Meta Description <span class="req">*</span>
                        </label>
                        <input id="meta_description" name="meta_description" class="input @error('meta_description') is-invalid @enderror" type="text" value="{{ old('meta_description', $item->meta_description) }}" required>
                        @error('meta_description')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="title">
                            Title <span class="req">*</span>
                        </label>
                        <input id="title" name="title" class="input @error('title') is-invalid @enderror" type="text" value="{{ old('title', $item->title) }}" required>
                        @error('title')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="subtitle">
                            Subtitle <span class="req">*</span>
                        </label>
                        <input id="subtitle" name="subtitle" class="input @error('subtitle') is-invalid @enderror" type="text" value="{{ old('subtitle', $item->subtitle) }}" required>
                        @error('subtitle')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field span-2">
                        <label class="field-label" for="description">Description</label>
                        <textarea id="description" name="description" class="textarea @error('description') is-invalid @enderror">{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="image_description">
                            Image Description <span class="req">*</span>
                        </label>
                        <input id="image_description" name="image_description" class="input @error('image_description') is-invalid @enderror" type="text" value="{{ old('image_description', $item->image_description) }}" required>
                        @error('image_description')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="image_large">
                            Image Large <span class="req">*</span>
                        </label>
                        <input id="image_large" name="image_large" class="input @error('image_large') is-invalid @enderror" type="text" value="{{ old('image_large', $item->image_large) }}" required>
                        @error('image_large')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="image_small">
                            Image Small <span class="req">*</span>
                        </label>
                        <input id="image_small" name="image_small" class="input @error('image_small') is-invalid @enderror" type="text" value="{{ old('image_small', $item->image_small) }}" required>
                        @error('image_small')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="file">
                            File <span class="req">*</span>
                        </label>
                        <input id="file" name="file" class="input @error('file') is-invalid @enderror" type="text" value="{{ old('file', $item->file) }}" required>
                        @error('file')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="activity_datetime">
                            Activity Datetime <span class="req">*</span>
                        </label>
                        <input id="activity_datetime" name="activity_datetime" class="input @error('activity_datetime') is-invalid @enderror" type="text" value="{{ old('activity_datetime', $item->activity_datetime) }}" required>
                        @error('activity_datetime')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="is_published">
                            Is Published <span class="req">*</span>
                        </label>
                        <input id="is_published" name="is_published" class="input @error('is_published') is-invalid @enderror" type="text" value="{{ old('is_published', $item->is_published) }}" required>
                        @error('is_published')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="published_at">
                            Published At <span class="req">*</span>
                        </label>
                        <input id="published_at" name="published_at" class="input @error('published_at') is-invalid @enderror" type="text" value="{{ old('published_at', $item->published_at) }}" required>
                        @error('published_at')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="published_by">
                            Published By <span class="req">*</span>
                        </label>
                        <input id="published_by" name="published_by" class="input @error('published_by') is-invalid @enderror" type="text" value="{{ old('published_by', $item->published_by) }}" required>
                        @error('published_by')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="published_by_name">
                            Published By Name <span class="req">*</span>
                        </label>
                        <input id="published_by_name" name="published_by_name" class="input @error('published_by_name') is-invalid @enderror" type="text" value="{{ old('published_by_name', $item->published_by_name) }}" required>
                        @error('published_by_name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="redirect_to">
                            Redirect To <span class="req">*</span>
                        </label>
                        <input id="redirect_to" name="redirect_to" class="input @error('redirect_to') is-invalid @enderror" type="text" value="{{ old('redirect_to', $item->redirect_to) }}" required>
                        @error('redirect_to')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="location">
                            Location <span class="req">*</span>
                        </label>
                        <input id="location" name="location" class="input @error('location') is-invalid @enderror" type="text" value="{{ old('location', $item->location) }}" required>
                        @error('location')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="show_as_banner">
                            Show As Banner <span class="req">*</span>
                        </label>
                        <input id="show_as_banner" name="show_as_banner" class="input @error('show_as_banner') is-invalid @enderror" type="text" value="{{ old('show_as_banner', $item->show_as_banner) }}" required>
                        @error('show_as_banner')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="total_views">
                            Total Views <span class="req">*</span>
                        </label>
                        <input id="total_views" name="total_views" class="input @error('total_views') is-invalid @enderror" type="text" value="{{ old('total_views', $item->total_views) }}" required>
                        @error('total_views')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.contents.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
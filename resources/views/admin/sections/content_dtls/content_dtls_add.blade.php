@extends('admin.layouts.theme', [
    'title' => 'Add Content_dtl',
    'activeMenu' => 'content_dtls',
    'breadcrumbsText' => 'Workspace | Content_dtls | Add Content_dtl'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Content_dtls</span>
            <h1 class="hero-title">Add Content_dtl</h1>
            <p class="hero-sub">Create a new record in Content_dtls.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.content_dtls.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">Content_dtl details</h2>
                </div>
            </div>

            <form id="addForm" action="{{ route('admin.content_dtls.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="t_content_id">
                            T Content Id <span class="req">*</span>
                        </label>
                        <input id="t_content_id" name="t_content_id" class="input @error('t_content_id') is-invalid @enderror" type="text" placeholder="Enter T Content Id" value="{{ old('t_content_id') }}" required>
                        @error('t_content_id')
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
                        <label class="field-label" for="sort_number">
                            Sort Number <span class="req">*</span>
                        </label>
                        <input id="sort_number" name="sort_number" class="input @error('sort_number') is-invalid @enderror" type="text" placeholder="Enter Sort Number" value="{{ old('sort_number') }}" required>
                        @error('sort_number')
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
                        <label class="field-label" for="background">
                            Background <span class="req">*</span>
                        </label>
                        <input id="background" name="background" class="input @error('background') is-invalid @enderror" type="text" placeholder="Enter Background" value="{{ old('background') }}" required>
                        @error('background')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="icon">
                            Icon <span class="req">*</span>
                        </label>
                        <input id="icon" name="icon" class="input @error('icon') is-invalid @enderror" type="text" placeholder="Enter Icon" value="{{ old('icon') }}" required>
                        @error('icon')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="redirect_to">
                            Redirect To <span class="req">*</span>
                        </label>
                        <input id="redirect_to" name="redirect_to" class="input @error('redirect_to') is-invalid @enderror" type="text" placeholder="Enter Redirect To" value="{{ old('redirect_to') }}" required>
                        @error('redirect_to')
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
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.content_dtls.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
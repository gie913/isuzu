@extends('admin.layouts.theme', [
    'title' => 'Edit Uploads',
    'activeMenu' => 'uploads',
    'breadcrumbsText' => 'Workspace | Uploads | Edit Uploads'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Uploads</span>
            <h1 class="hero-title">Edit Uploads</h1>
            <p class="hero-sub">Update record details for <strong>{{ $item->name ?? 'Record' }}</strong>.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.uploads.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">Edit Uploads</h2>
                </div>
                <span class="badge {{ $item->is_active ? 'success dot' : 'dot' }}">
                    {{ $item->is_active ? __('label.active') : __('label.inactive') }}
                </span>
            </div>

            <form id="editForm" action="{{ route('admin.uploads.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="m_upload_setup_id">
                            M Upload Setup Id <span class="req">*</span>
                        </label>
                        <input id="m_upload_setup_id" name="m_upload_setup_id" class="input @error('m_upload_setup_id') is-invalid @enderror" type="text" value="{{ old('m_upload_setup_id', $item->m_upload_setup_id) }}" required>
                        @error('m_upload_setup_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="m_module_id">
                            M Module Id <span class="req">*</span>
                        </label>
                        <input id="m_module_id" name="m_module_id" class="input @error('m_module_id') is-invalid @enderror" type="text" value="{{ old('m_module_id', $item->m_module_id) }}" required>
                        @error('m_module_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="name">
                            Name <span class="req">*</span>
                        </label>
                        <input id="name" name="name" class="input @error('name') is-invalid @enderror" type="text" value="{{ old('name', $item->name) }}" required>
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="path">
                            Path <span class="req">*</span>
                        </label>
                        <input id="path" name="path" class="input @error('path') is-invalid @enderror" type="text" value="{{ old('path', $item->path) }}" required>
                        @error('path')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="type">
                            Type <span class="req">*</span>
                        </label>
                        <input id="type" name="type" class="input @error('type') is-invalid @enderror" type="text" value="{{ old('type', $item->type) }}" required>
                        @error('type')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="file_source">
                            File Source <span class="req">*</span>
                        </label>
                        <input id="file_source" name="file_source" class="input @error('file_source') is-invalid @enderror" type="text" value="{{ old('file_source', $item->file_source) }}" required>
                        @error('file_source')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="is_external_storage">
                            Is External Storage <span class="req">*</span>
                        </label>
                        <input id="is_external_storage" name="is_external_storage" class="input @error('is_external_storage') is-invalid @enderror" type="text" value="{{ old('is_external_storage', $item->is_external_storage) }}" required>
                        @error('is_external_storage')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="is_temporary">
                            Is Temporary <span class="req">*</span>
                        </label>
                        <input id="is_temporary" name="is_temporary" class="input @error('is_temporary') is-invalid @enderror" type="text" value="{{ old('is_temporary', $item->is_temporary) }}" required>
                        @error('is_temporary')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.uploads.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
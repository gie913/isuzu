@extends('admin.layouts.theme', [
    'title' => 'Edit Upload setup',
    'activeMenu' => 'upload_setups',
    'breadcrumbsText' => 'Workspace | Upload setups | Edit Upload setup'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Upload setups</span>
            <h1 class="hero-title">Edit Upload setup</h1>
            <p class="hero-sub">Update record details for <strong>{{ $item->name ?? 'Record' }}</strong>.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.upload_setups.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">Edit Upload setup</h2>
                </div>
                <span class="badge {{ $item->is_active ? 'success dot' : 'dot' }}">
                    {{ $item->is_active ? __('label.active') : __('label.inactive') }}
                </span>
            </div>

            <form id="editForm" action="{{ route('admin.upload_setups.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
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
                        <label class="field-label" for="allowed_files">
                            Allowed Files <span class="req">*</span>
                        </label>
                        <input id="allowed_files" name="allowed_files" class="input @error('allowed_files') is-invalid @enderror" type="text" value="{{ old('allowed_files', $item->allowed_files) }}" required>
                        @error('allowed_files')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="is_resized">
                            Is Resized <span class="req">*</span>
                        </label>
                        <input id="is_resized" name="is_resized" class="input @error('is_resized') is-invalid @enderror" type="text" value="{{ old('is_resized', $item->is_resized) }}" required>
                        @error('is_resized')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="pic_height">
                            Pic Height <span class="req">*</span>
                        </label>
                        <input id="pic_height" name="pic_height" class="input @error('pic_height') is-invalid @enderror" type="text" value="{{ old('pic_height', $item->pic_height) }}" required>
                        @error('pic_height')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="pic_width">
                            Pic Width <span class="req">*</span>
                        </label>
                        <input id="pic_width" name="pic_width" class="input @error('pic_width') is-invalid @enderror" type="text" value="{{ old('pic_width', $item->pic_width) }}" required>
                        @error('pic_width')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="is_cropped">
                            Is Cropped <span class="req">*</span>
                        </label>
                        <input id="is_cropped" name="is_cropped" class="input @error('is_cropped') is-invalid @enderror" type="text" value="{{ old('is_cropped', $item->is_cropped) }}" required>
                        @error('is_cropped')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="ratio_height">
                            Ratio Height <span class="req">*</span>
                        </label>
                        <input id="ratio_height" name="ratio_height" class="input @error('ratio_height') is-invalid @enderror" type="text" value="{{ old('ratio_height', $item->ratio_height) }}" required>
                        @error('ratio_height')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="ratio_width">
                            Ratio Width <span class="req">*</span>
                        </label>
                        <input id="ratio_width" name="ratio_width" class="input @error('ratio_width') is-invalid @enderror" type="text" value="{{ old('ratio_width', $item->ratio_width) }}" required>
                        @error('ratio_width')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="using_thumb">
                            Using Thumb <span class="req">*</span>
                        </label>
                        <input id="using_thumb" name="using_thumb" class="input @error('using_thumb') is-invalid @enderror" type="text" value="{{ old('using_thumb', $item->using_thumb) }}" required>
                        @error('using_thumb')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="thumb_height">
                            Thumb Height <span class="req">*</span>
                        </label>
                        <input id="thumb_height" name="thumb_height" class="input @error('thumb_height') is-invalid @enderror" type="text" value="{{ old('thumb_height', $item->thumb_height) }}" required>
                        @error('thumb_height')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="thumb_width">
                            Thumb Width <span class="req">*</span>
                        </label>
                        <input id="thumb_width" name="thumb_width" class="input @error('thumb_width') is-invalid @enderror" type="text" value="{{ old('thumb_width', $item->thumb_width) }}" required>
                        @error('thumb_width')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="max_size">
                            Max Size <span class="req">*</span>
                        </label>
                        <input id="max_size" name="max_size" class="input @error('max_size') is-invalid @enderror" type="text" value="{{ old('max_size', $item->max_size) }}" required>
                        @error('max_size')
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
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.upload_setups.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
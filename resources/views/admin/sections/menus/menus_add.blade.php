@extends('admin.layouts.theme', [
    'title' => 'Add Menu',
    'activeMenu' => 'menus',
    'breadcrumbsText' => 'Workspace | Menus | Add Menu'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Menus</span>
            <h1 class="hero-title">Add Menu</h1>
            <p class="hero-sub">Create a new record in Menus.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.menus.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">Menu details</h2>
                </div>
            </div>

            <form id="addForm" action="{{ route('admin.menus.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="code">
                            Code <span class="req">*</span>
                        </label>
                        <input id="code" name="code" class="input @error('code') is-invalid @enderror" type="text" placeholder="Enter Code" value="{{ old('code') }}" required>
                        @error('code')
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
                        <label class="field-label" for="redirect_to">
                            Redirect To <span class="req">*</span>
                        </label>
                        <input id="redirect_to" name="redirect_to" class="input @error('redirect_to') is-invalid @enderror" type="text" placeholder="Enter Redirect To" value="{{ old('redirect_to') }}" required>
                        @error('redirect_to')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="type">
                            Type <span class="req">*</span>
                        </label>
                        <input id="type" name="type" class="input @error('type') is-invalid @enderror" type="text" placeholder="Enter Type" value="{{ old('type') }}" required>
                        @error('type')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
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
                        <label class="field-label" for="t_page_id">
                            T Page Id <span class="req">*</span>
                        </label>
                        <input id="t_page_id" name="t_page_id" class="input @error('t_page_id') is-invalid @enderror" type="text" placeholder="Enter T Page Id" value="{{ old('t_page_id') }}" required>
                        @error('t_page_id')
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
                        <label class="field-label" for="is_devider">
                            Is Devider <span class="req">*</span>
                        </label>
                        <input id="is_devider" name="is_devider" class="input @error('is_devider') is-invalid @enderror" type="text" placeholder="Enter Is Devider" value="{{ old('is_devider') }}" required>
                        @error('is_devider')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="is_submenu">
                            Is Submenu <span class="req">*</span>
                        </label>
                        <input id="is_submenu" name="is_submenu" class="input @error('is_submenu') is-invalid @enderror" type="text" placeholder="Enter Is Submenu" value="{{ old('is_submenu') }}" required>
                        @error('is_submenu')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="submenu_from_id">
                            Submenu From Id <span class="req">*</span>
                        </label>
                        <input id="submenu_from_id" name="submenu_from_id" class="input @error('submenu_from_id') is-invalid @enderror" type="text" placeholder="Enter Submenu From Id" value="{{ old('submenu_from_id') }}" required>
                        @error('submenu_from_id')
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
                        <label class="field-label" for="submenu_level">
                            Submenu Level <span class="req">*</span>
                        </label>
                        <input id="submenu_level" name="submenu_level" class="input @error('submenu_level') is-invalid @enderror" type="text" placeholder="Enter Submenu Level" value="{{ old('submenu_level') }}" required>
                        @error('submenu_level')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="text">
                            Text <span class="req">*</span>
                        </label>
                        <input id="text" name="text" class="input @error('text') is-invalid @enderror" type="text" placeholder="Enter Text" value="{{ old('text') }}" required>
                        @error('text')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="text_lang_eng">
                            Text Lang Eng <span class="req">*</span>
                        </label>
                        <input id="text_lang_eng" name="text_lang_eng" class="input @error('text_lang_eng') is-invalid @enderror" type="text" placeholder="Enter Text Lang Eng" value="{{ old('text_lang_eng') }}" required>
                        @error('text_lang_eng')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="text_lang_ina">
                            Text Lang Ina <span class="req">*</span>
                        </label>
                        <input id="text_lang_ina" name="text_lang_ina" class="input @error('text_lang_ina') is-invalid @enderror" type="text" placeholder="Enter Text Lang Ina" value="{{ old('text_lang_ina') }}" required>
                        @error('text_lang_ina')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
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
                        <label class="field-label" for="is_published">
                            Is Published <span class="req">*</span>
                        </label>
                        <input id="is_published" name="is_published" class="input @error('is_published') is-invalid @enderror" type="text" placeholder="Enter Is Published" value="{{ old('is_published') }}" required>
                        @error('is_published')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="is_footer">
                            Is Footer <span class="req">*</span>
                        </label>
                        <input id="is_footer" name="is_footer" class="input @error('is_footer') is-invalid @enderror" type="text" placeholder="Enter Is Footer" value="{{ old('is_footer') }}" required>
                        @error('is_footer')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.menus.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
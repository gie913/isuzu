@extends('admin.layouts.theme', [
    'title' => 'Add General Data',
    'activeMenu' => 'general_datas',
    'breadcrumbsText' => 'Workspace | General Datas | Add General Data'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · General Datas</span>
            <h1 class="hero-title">Add General Data</h1>
            <p class="hero-sub">Create a new record in General Datas.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.general_datas.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">General Data details</h2>
                </div>
            </div>

            <form id="addForm" action="{{ route('admin.general_datas.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="group_">
                            Group <span class="req">*</span>
                        </label>
                        <input id="group_" name="group_" class="input @error('group_') is-invalid @enderror" type="text" placeholder="Enter Group" value="{{ old('group_') }}" required>
                        @error('group_')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
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
                        <label class="field-label" for="sort_number">
                            Sort Number <span class="req">*</span>
                        </label>
                        <input id="sort_number" name="sort_number" class="input @error('sort_number') is-invalid @enderror" type="text" placeholder="Enter Sort Number" value="{{ old('sort_number') }}" required>
                        @error('sort_number')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="list_data">
                            List Data <span class="req">*</span>
                        </label>
                        <input id="list_data" name="list_data" class="input @error('list_data') is-invalid @enderror" type="text" placeholder="Enter List Data" value="{{ old('list_data') }}" required>
                        @error('list_data')
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
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.general_datas.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
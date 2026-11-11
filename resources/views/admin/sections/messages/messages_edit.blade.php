@extends('admin.layouts.theme', [
    'title' => 'Edit Message',
    'activeMenu' => 'messages',
    'breadcrumbsText' => 'Workspace | Messages | Edit Message'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Messages</span>
            <h1 class="hero-title">Edit Message</h1>
            <p class="hero-sub">Update record details for <strong>{{ $item->name ?? 'Record' }}</strong>.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.messages.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">Edit Message</h2>
                </div>
                <span class="badge {{ $item->is_active ? 'success dot' : 'dot' }}">
                    {{ $item->is_active ? __('label.active') : __('label.inactive') }}
                </span>
            </div>

            <form id="editForm" action="{{ route('admin.messages.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="from_user_id">
                            From User Id <span class="req">*</span>
                        </label>
                        <input id="from_user_id" name="from_user_id" class="input @error('from_user_id') is-invalid @enderror" type="text" value="{{ old('from_user_id', $item->from_user_id) }}" required>
                        @error('from_user_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="to_user_id">
                            To User Id <span class="req">*</span>
                        </label>
                        <input id="to_user_id" name="to_user_id" class="input @error('to_user_id') is-invalid @enderror" type="text" value="{{ old('to_user_id', $item->to_user_id) }}" required>
                        @error('to_user_id')
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
                        <label class="field-label" for="phone">
                            Phone <span class="req">*</span>
                        </label>
                        <input id="phone" name="phone" class="input @error('phone') is-invalid @enderror" type="text" value="{{ old('phone', $item->phone) }}" required>
                        @error('phone')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="email">
                            Email <span class="req">*</span>
                        </label>
                        <input id="email" name="email" class="input @error('email') is-invalid @enderror" type="text" value="{{ old('email', $item->email) }}" required>
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="address">
                            Address <span class="req">*</span>
                        </label>
                        <input id="address" name="address" class="input @error('address') is-invalid @enderror" type="text" value="{{ old('address', $item->address) }}" required>
                        @error('address')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="category">
                            Category <span class="req">*</span>
                        </label>
                        <input id="category" name="category" class="input @error('category') is-invalid @enderror" type="text" value="{{ old('category', $item->category) }}" required>
                        @error('category')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="message">
                            Message <span class="req">*</span>
                        </label>
                        <input id="message" name="message" class="input @error('message') is-invalid @enderror" type="text" value="{{ old('message', $item->message) }}" required>
                        @error('message')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="status">
                            Status <span class="req">*</span>
                        </label>
                        <input id="status" name="status" class="input @error('status') is-invalid @enderror" type="text" value="{{ old('status', $item->status) }}" required>
                        @error('status')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="is_replied">
                            Is Replied <span class="req">*</span>
                        </label>
                        <input id="is_replied" name="is_replied" class="input @error('is_replied') is-invalid @enderror" type="text" value="{{ old('is_replied', $item->is_replied) }}" required>
                        @error('is_replied')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="replied_at">
                            Replied At <span class="req">*</span>
                        </label>
                        <input id="replied_at" name="replied_at" class="input @error('replied_at') is-invalid @enderror" type="text" value="{{ old('replied_at', $item->replied_at) }}" required>
                        @error('replied_at')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="read_at">
                            Read At <span class="req">*</span>
                        </label>
                        <input id="read_at" name="read_at" class="input @error('read_at') is-invalid @enderror" type="text" value="{{ old('read_at', $item->read_at) }}" required>
                        @error('read_at')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.messages.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
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
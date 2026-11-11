@extends('admin.layouts.theme', [
    'title' => 'Edit Base Setup',
    'activeMenu' => 'base_setups',
    'breadcrumbsText' => 'Workspace | Base Setups | Edit Base Setup'
])

@section('css')
<style>
    .ui-switch {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        user-select: none;
        padding-top: 4px;
    }
    .ui-switch input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .ui-switch-track {
        width: 44px;
        height: 24px;
        background: var(--border, #cbd5e1);
        border-radius: 999px;
        position: relative;
        transition: background .2s ease;
        display: inline-block;
        flex-shrink: 0;
    }
    .ui-switch-track::after {
        content: "";
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background: #fff;
        border-radius: 50%;
        transition: transform .2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.25);
    }
    .ui-switch input:checked + .ui-switch-track {
        background: var(--primary, #2563eb);
    }
    .ui-switch input:checked + .ui-switch-track::after {
        transform: translateX(20px);
    }
    .ui-switch-label {
        font-size: 13px;
        color: var(--t-base, inherit);
        font-weight: 500;
    }
    .subform-card {
        grid-column: span 2;
        background: var(--bg-hover, #f8fafc);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 8px;
        padding: 16px;
    }
    .subform-head {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-size: 13px;
        font-weight: 600;
        color: var(--t-base, inherit);
    }
    .code-editor-box {
        grid-column: span 2;
    }
    #ace_editor {
        width: 100%;
        height: 240px;
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 6px;
        font-size: 13px;
        font-family: 'JetBrains Mono', monospace;
    }
</style>
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Base Setups</span>
            <h1 class="hero-title">Edit Base Setup</h1>
            <p class="hero-sub">Update record details for <strong>{{ $item->name ?? 'Record' }}</strong>.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.base_setups.index') }}" class="btn btn--ghost">
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
                    <h2 class="card-title">Edit Base Setup</h2>
                </div>
                <span class="badge {{ $item->is_active ? 'success dot' : 'dot' }}">
                    {{ $item->is_active ? __('label.active') : __('label.inactive') }}
                </span>
            </div>

            <form id="editForm" action="{{ route('admin.base_setups.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="title">
                            Title
                        </label>
                        <input id="title" name="title" class="input @error('title') is-invalid @enderror" type="text" value="{{ old('title', $item->title) }}">
                        @error('title')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="meta_description">
                            Meta Description
                        </label>
                        <input id="meta_description" name="meta_description" class="input @error('meta_description') is-invalid @enderror" type="text" value="{{ old('meta_description', $item->meta_description) }}">
                        @error('meta_description')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="meta_keyword">
                            Meta Keyword
                        </label>
                        <input id="meta_keyword" name="meta_keyword" class="input @error('meta_keyword') is-invalid @enderror" type="text" value="{{ old('meta_keyword', $item->meta_keyword) }}">
                        @error('meta_keyword')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="long_description">
                            Long Description
                        </label>
                        <input id="long_description" name="long_description" class="input @error('long_description') is-invalid @enderror" type="text" value="{{ old('long_description', $item->long_description) }}">
                        @error('long_description')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="using_dual_lang">
                            Using Dual Lang
                        </label>
                        <div>
                            <input type="hidden" name="using_dual_lang" value="0">
                            <label class="ui-switch">
                                <input type="checkbox" id="using_dual_lang" name="using_dual_lang" value="1" {{ old('using_dual_lang', (string) ($item->using_dual_lang ? '1' : '0')) == '1' ? 'checked' : '' }}>
                                <span class="ui-switch-track"></span>
                                <span class="ui-switch-label">Enable Dual Language</span>
                            </label>
                        </div>
                        @error('using_dual_lang')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="default_lang">
                            Default Lang
                        </label>
                        <select id="default_lang" name="default_lang" class="select @error('default_lang') is-invalid @enderror">
                            <option value="en" {{ old('default_lang', $item->default_lang ?? 'en') == 'en' ? 'selected' : '' }}>English (en)</option>
                            <option value="id" {{ old('default_lang', $item->default_lang) == 'id' ? 'selected' : '' }}>Bahasa Indonesia (id)</option>
                        </select>
                        @error('default_lang')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="primary_image_src">
                            Primary Image Src
                        </label>
                        <input id="primary_image_src" name="primary_image_src" class="input @error('primary_image_src') is-invalid @enderror" type="text" value="{{ old('primary_image_src', $item->primary_image_src) }}">
                        @error('primary_image_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="secondary_image_src">
                            Secondary Image Src
                        </label>
                        <input id="secondary_image_src" name="secondary_image_src" class="input @error('secondary_image_src') is-invalid @enderror" type="text" value="{{ old('secondary_image_src', $item->secondary_image_src) }}">
                        @error('secondary_image_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="alternative1_image_src">
                            Alternative1 Image Src
                        </label>
                        <input id="alternative1_image_src" name="alternative1_image_src" class="input @error('alternative1_image_src') is-invalid @enderror" type="text" value="{{ old('alternative1_image_src', $item->alternative1_image_src) }}">
                        @error('alternative1_image_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="alternative2_image_src">
                            Alternative2 Image Src
                        </label>
                        <input id="alternative2_image_src" name="alternative2_image_src" class="input @error('alternative2_image_src') is-invalid @enderror" type="text" value="{{ old('alternative2_image_src', $item->alternative2_image_src) }}">
                        @error('alternative2_image_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="alternative_image_src">
                            Alternative Image Src
                        </label>
                        <input id="alternative_image_src" name="alternative_image_src" class="input @error('alternative_image_src') is-invalid @enderror" type="text" value="{{ old('alternative_image_src', $item->alternative_image_src) }}">
                        @error('alternative_image_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="x_icon_src">
                            X Icon Src
                        </label>
                        <input id="x_icon_src" name="x_icon_src" class="input @error('x_icon_src') is-invalid @enderror" type="text" value="{{ old('x_icon_src', $item->x_icon_src) }}">
                        @error('x_icon_src')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="author">
                            Author
                        </label>
                        <input id="author" name="author" class="input @error('author') is-invalid @enderror" type="text" value="{{ old('author', $item->author) }}">
                        @error('author')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="subform-card">
                        <div class="subform-head">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>Address & Location</span>
                        </div>
                        <div class="form-grid">
                            <div class="field span-2">
                                <label class="field-label" for="address_text">Street Address</label>
                                <input id="address_text" name="address[address]" class="input @error('address.address') is-invalid @enderror" type="text" placeholder="Enter Full Address" value="{{ old('address.address', is_array($item->address) ? ($item->address['address'] ?? '') : '') }}">
                                @error('address.address')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field">
                                <label class="field-label" for="address_gps_lng">GPS Longitude</label>
                                <input id="address_gps_lng" name="address[gps_lng]" class="input @error('address.gps_lng') is-invalid @enderror" type="text" placeholder="e.g. 106.8456" value="{{ old('address.gps_lng', is_array($item->address) ? ($item->address['gps_lng'] ?? '') : '') }}">
                                @error('address.gps_lng')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field">
                                <label class="field-label" for="address_gps_lat">GPS Latitude</label>
                                <input id="address_gps_lat" name="address[gps_lat]" class="input @error('address.gps_lat') is-invalid @enderror" type="text" placeholder="e.g. -6.2088" value="{{ old('address.gps_lat', is_array($item->address) ? ($item->address['gps_lat'] ?? '') : '') }}">
                                @error('address.gps_lat')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="subform-card">
                        <div class="subform-head">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>Contact Channels</span>
                        </div>
                        <div class="form-grid">
                            <div class="field">
                                <label class="field-label" for="contact_phone">Phone</label>
                                <input id="contact_phone" name="contact[phone]" class="input @error('contact.phone') is-invalid @enderror" type="text" placeholder="Enter Phone Number" value="{{ old('contact.phone', is_array($item->contact) ? ($item->contact['phone'] ?? '') : '') }}">
                                @error('contact.phone')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field">
                                <label class="field-label" for="contact_whatsapp">WhatsApp</label>
                                <input id="contact_whatsapp" name="contact[whatsapp]" class="input @error('contact.whatsapp') is-invalid @enderror" type="text" placeholder="Enter WhatsApp Number" value="{{ old('contact.whatsapp', is_array($item->contact) ? ($item->contact['whatsapp'] ?? '') : '') }}">
                                @error('contact.whatsapp')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field">
                                <label class="field-label" for="contact_email">Email</label>
                                <input id="contact_email" name="contact[email]" class="input @error('contact.email') is-invalid @enderror" type="email" placeholder="Enter Contact Email" value="{{ old('contact.email', is_array($item->contact) ? ($item->contact['email'] ?? '') : '') }}">
                                @error('contact.email')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field">
                                <label class="field-label" for="contact_pic">Person in Charge (PIC)</label>
                                <input id="contact_pic" name="contact[pic]" class="input @error('contact.pic') is-invalid @enderror" type="text" placeholder="Enter PIC Name" value="{{ old('contact.pic', is_array($item->contact) ? ($item->contact['pic'] ?? '') : '') }}">
                                @error('contact.pic')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="field-label" for="dashboard_theme_config">
                            Dashboard Theme Config
                        </label>
                        <input id="dashboard_theme_config" name="dashboard_theme_config" class="input @error('dashboard_theme_config') is-invalid @enderror" type="text" value="{{ old('dashboard_theme_config', $item->dashboard_theme_config) }}">
                        @error('dashboard_theme_config')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="asset_version">
                            Asset Version
                        </label>
                        <input id="asset_version" name="asset_version" class="input @error('asset_version') is-invalid @enderror" type="text" value="{{ old('asset_version', $item->asset_version) }}">
                        @error('asset_version')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field code-editor-box">
                        <label class="field-label" for="additional_scripts">
                            Additional Scripts
                        </label>
                        <div id="ace_editor"></div>
                        <textarea id="additional_scripts" name="additional_scripts" style="display:none;">{{ old('additional_scripts', $item->additional_scripts) }}</textarea>
                        @error('additional_scripts')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.base_setups.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                    <span class="spacer"></span>
                    <button type="submit" class="btn btn--primary">{{ __('form.submit') }}</button>
                </div>
            </form>
        </section>
    </div>
</main>
@endsection

@section('js')
<script src="{{ asset('plugins/ace/ace.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('additional_scripts');
        if (document.getElementById('ace_editor') && window.ace) {
            const editor = ace.edit("ace_editor");
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            editor.setTheme(isDark ? "ace/theme/monokai" : "ace/theme/chrome");
            editor.session.setMode("ace/mode/html");
            editor.setValue(textarea.value || '', -1);
            editor.setOptions({
                fontSize: "13px",
                showPrintMargin: false,
                tabSize: 4,
                useSoftTabs: true,
                wrap: true
            });

            editor.session.on('change', function () {
                textarea.value = editor.getValue();
            });

            // Form submit sync
            const form = document.getElementById('editForm');
            if (form) {
                form.addEventListener('submit', function () {
                    textarea.value = editor.getValue();
                });
            }
        }
    });
</script>
@endsection
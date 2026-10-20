<footer class="d-footer">
    <div>&copy; {{ date('Y') }} · <a href="{{ route('admin.dashboard') }}">{{ config('app.name', env('APP_NAME', 'ISUZU Administration')) }}</a></div>
    <div class="d-footer-meta">
        <span>v1.0.0</span>
        <span>Enterprise Control Panel</span>
    </div>
</footer>

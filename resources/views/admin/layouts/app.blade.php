<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $moduleName ?? 'Admin Panel' }} - Laravel App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">ISUZU Admin</a>
            <div class="d-flex align-items-center">
                <div class="btn-group me-3">
                    <a href="?lang=en" class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-light' }}">EN</a>
                    <a href="?lang=id" class="btn btn-sm {{ app()->getLocale() === 'id' ? 'btn-primary' : 'btn-outline-light' }}">ID</a>
                </div>
                <span class="text-white small">Locale: <strong>{{ strtoupper(app()->getLocale()) }}</strong></span>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        @if(isset($breadcrumbs))
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @foreach($breadcrumbs as $b)
                        @if($loop->last)
                            <li class="breadcrumb-item active" aria-current="page">{{ $b['name'] }}</li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ $b['url'] }}">{{ $b['name'] }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('flash_notification'))
            <div class="alert alert-{{ session('flash_notification.type') }}">
                {{ session('flash_notification.message') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

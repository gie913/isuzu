<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $moduleName ?? 'Admin Panel' }} - Laravel App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('css')
    @stack('styles')
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
    <link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @yield('js')
    @stack('scripts')
    <script>
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form || !form.classList.contains('form-delete-swal')) return;
            if (form.dataset.swalConfirmed === 'true') return;

            e.preventDefault();

            const recordName = form.getAttribute('data-record-name') || '';
            const confirmTitle = form.getAttribute('data-swal-title') || 'Delete Confirmation';
            let confirmMessage = form.getAttribute('data-swal-message');

            if (!confirmMessage) {
                if (recordName) {
                    confirmMessage = `Are you sure you want to delete <strong>${recordName}</strong>?<br><span style="font-size:0.875rem;color:#6c757d;">This action cannot be undone.</span>`;
                } else {
                    confirmMessage = 'Are you sure you want to delete this record?<br><span style="font-size:0.875rem;color:#6c757d;">This action cannot be undone.</span>';
                }
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: confirmTitle,
                    html: confirmMessage,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.dataset.swalConfirmed = 'true';
                        form.submit();
                    }
                });
            } else {
                const plainText = recordName 
                    ? `Are you sure you want to delete "${recordName}"?` 
                    : 'Are you sure you want to delete this record?';
                if (confirm(plainText)) {
                    form.dataset.swalConfirmed = 'true';
                    form.submit();
                }
            }
        });
    </script>
</body>
</html>

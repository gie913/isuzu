<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Inter+Tight:wght@500;600;700&family=JetBrains+Mono:wght@400;500&display=swap">
    <title>{{ $title ?? 'Dashboard' }} · {{ config('app.name', env('APP_NAME', 'ISUZU')) }} Admin Panel</title>
    <script>
        !function(){try{var t=localStorage.getItem("dash26-theme"),e=window.matchMedia("(prefers-color-scheme: dark)").matches;document.documentElement.setAttribute("data-theme",t||(e?"dark":"light"))}catch(t){document.documentElement.setAttribute("data-theme","light")}}()
    </script>
    <script defer="defer" src="{{ asset('themes/admin/runtime.096d507f.js') }}"></script>
    <script defer="defer" src="{{ asset('themes/admin/2026.5a84696f.js') }}"></script>
    <script defer="defer" src="{{ asset('themes/admin/vendor-chartjs.149393ea.js') }}"></script>
    <link href="{{ asset('themes/admin/style.76ee5eee.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @yield('css')
    @stack('styles')
</head>
<body data-active="{{ $activeMenu ?? 'dashboard' }}" data-crumbs="{{ $breadcrumbsText ?? 'Workspace | Dashboard' }}">
    <div class="shell">
        <!-- Sliced Sidebar / Navbar -->
        @include('admin.layouts.partials.sidebar')

        <div class="main">
            <!-- Sliced Header / Topbar -->
            @include('admin.layouts.partials.header')
            
            <!-- Main Content Area for Yield -->
            @yield('content')

            <!-- Sliced Footer -->
            @include('admin.layouts.partials.footer')
        </div>
    </div>

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
                    confirmMessage = `Are you sure you want to delete <strong>${recordName}</strong>?<br><span style="font-size:0.875rem;color:var(--t-light,#64748b);">This action cannot be undone.</span>`;
                } else {
                    confirmMessage = 'Are you sure you want to delete this record?<br><span style="font-size:0.875rem;color:var(--t-light,#64748b);">This action cannot be undone.</span>';
                }
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: confirmTitle,
                    html: confirmMessage,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
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

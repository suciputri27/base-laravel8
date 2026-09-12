<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Base App')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        @include('partials.sidebar')

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="app-main">
            @include('partials.header')

            <main class="app-content">
                <div id="globalAlert" class="d-none"></div>

                <nav class="breadcrumb-nav" aria-label="breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <span class="separator"><i class="fas fa-chevron-right"></i></span>
                    @hasSection('breadcrumb')
                        @yield('breadcrumb')
                    @else
                        <span class="current">@yield('page-title', 'Dashboard')</span>
                    @endif
                </nav>

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        NProgress.configure({ showSpinner: false });
        NProgress.start();
        window.addEventListener('load', function () {
            NProgress.done();
        });

        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.body.classList.add('sidebar-open');
        });

        document.getElementById('sidebarClose').addEventListener('click', function () {
            document.body.classList.remove('sidebar-open');
        });

        document.getElementById('sidebarOverlay').addEventListener('click', function () {
            document.body.classList.remove('sidebar-open');
        });
    </script>
    @stack('scripts')
</body>
</html>

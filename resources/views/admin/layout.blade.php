<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $pageTitle ?? 'Dashboard Admin Arena Gym' }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=JetBrains+Mono:wght@400;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script>
            (() => {
                const savedTheme = localStorage.getItem('arena-gym-theme');
                const preferredTheme = savedTheme || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
                document.documentElement.setAttribute('data-theme', preferredTheme);
            })();
        </script>

        <link rel="stylesheet" href="{{ asset('css/industrial-theme.css') }}">
        @stack('styles')
    </head>
    @php
        $authRole = session('auth.role');
        $isMasterAdmin = $authRole === 'master_admin';
        $isCashierArea = str_starts_with(($activePage ?? 'dashboard'), 'cashier');
    @endphp
    <body class="sidebar-hidden {{ $isCashierArea ? 'cashier-layout' : 'admin-layout' }} page-{{ str_replace('.', '-', $activePage ?? 'dashboard') }}">
        <div class="page-loader" id="pageLoader" aria-live="polite" aria-label="Memuat halaman">
            <div class="page-loader-card">
                <div class="page-loader-brand">
                    <span class="page-loader-mark"><img src="{{ asset('images/arena-fitness-logo.jpg') }}" alt="Arena Fitness" class="brand-logo"></span>
                    <div>
                        <div class="page-loader-wordmark">Loading</div>
                        <div class="page-loader-title">Arena Fitness</div>
                        <div class="page-loader-subtitle" id="pageLoaderText">Menyiapkan halaman...</div>
                    </div>
                </div>
                <div class="page-loader-bar" aria-hidden="true"></div>
            </div>
        </div>

        <div class="container-fluid py-3 py-lg-4">
            <button type="button" class="btn sidebar-toggle" id="sidebarToggle" aria-label="Buka navigasi" aria-expanded="false" aria-controls="adminSidebar">
                <div class="sidebar-toggle-icon" id="sidebarToggleIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </div>
            </button>
            <div class="sidebar-overlay" id="sidebarOverlay"></div>
            <div class="row g-3 g-lg-4">
                @include('admin.partials.sidebar')

                <main class="col-12">
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-3">
                            <div class="fw-semibold mb-1">Data belum tersimpan.</div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/admin-app.js') }}"></script>
        @stack('scripts')
    </body>
</html>

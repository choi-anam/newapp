<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="app-name" content="{{ config('app.name', 'Laravel') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- PWA Manifest & Theme -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#0d6efd">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        
        <style>
            :root {
                --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --safe-area-inset-bottom: env(safe-area-inset-bottom);
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                background-color: #f8f9fa;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
                padding-bottom: var(--safe-area-inset-bottom);
            }

            /* Top Navigation */
            .navbar-custom {
                background: var(--primary-gradient);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                padding: 1rem 0;
                position: sticky;
                top: 0;
                z-index: 990;
                color: white;
            }

            .navbar-custom .navbar-brand {
                color: white !important;
                font-weight: 700;
                font-size: 1.25rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .navbar-custom .nav-link {
                color: rgba(255, 255, 255, 0.8) !important;
                transition: color 0.3s ease;
            }

            .navbar-custom .nav-link:hover {
                color: white !important;
            }

            .navbar-custom .nav-link.active {
                color: white !important;
                border-bottom: 2px solid white;
            }

            .navbar-custom .dropdown-toggle::after {
                border: none;
            }

            .navbar-custom .dropdown-toggle:hover::after {
                opacity: 1;
            }

            .navbar-toggler {
                background-color: rgba(255, 255, 255, 0.3);
                border: none;
            }

            .navbar-toggler:focus {
                box-shadow: none;
                background-color: rgba(255, 255, 255, 0.4);
            }

            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            }

            /* Main Content */
            .main-content {
                min-height: 100vh;
                padding-bottom: 70px;
            }

            .page-header {
                background: var(--primary-gradient);
                color: white;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .page-header h1 {
                margin: 0;
                font-size: 1.75rem;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            /* Cards */
            .card {
                border: none;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                margin-bottom: 1rem;
                transition: all 0.3s ease;
            }

            .card:active {
                transform: scale(0.98);
            }

            /* Bottom Navigation (Mobile) */
            .bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid #e9ecef;
                box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.05);
                padding-bottom: var(--safe-area-inset-bottom);
                z-index: 1000;
                display: flex;
                flex-wrap: nowrap;
                align-items: stretch;
            }

            .nav-item-bottom {
                flex: 1;
                text-align: center;
                padding: 0.6rem 0;
                color: #6c757d;
                text-decoration: none;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 0.25rem;
                font-size: 0.65rem;
                transition: all 0.3s ease;
                white-space: nowrap;
                min-height: 60px;
                border: none;
                background: none;
                cursor: pointer;
            }

            .nav-item-bottom i {
                font-size: 1.3rem;
                display: block;
            }

            .nav-item-bottom.active {
                color: #667eea;
            }

            .nav-item-bottom:active {
                background-color: #f8f9fa;
            }

            /* Dropdown in bottom nav */
            .bottom-nav .dropdown {
                flex: 1;
                position: relative;
                padding: 0;
            }

            .bottom-nav .dropdown-menu {
                position: fixed;
                bottom: 60px;
                right: 0;
                left: auto;
                max-width: 180px;
            }

            /* Responsive - Hide bottom nav on desktop */
            @media (min-width: 768px) {
                .bottom-nav {
                    display: none;
                }

                .main-content {
                    padding-bottom: 0;
                }

                .navbar-custom {
                    padding: 0.5rem 0;
                }
            }

            /* Button styles */
            .btn-primary {
                background: var(--primary-gradient);
                border: none;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
                color: white;
            }

            .btn-primary:active {
                transform: scale(0.98);
            }

            /* Alert */
            .alert {
                border-radius: 12px;
                border: none;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            /* Form */
            .form-control, .form-select {
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                padding: 0.75rem;
            }

            .form-control:focus, .form-select:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            }

            /* Dropdown menu */
            .dropdown-menu {
                border-radius: 12px;
                border: none;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .dropdown-item {
                padding: 0.75rem 1rem;
            }

            .dropdown-item:active {
                background: var(--primary-gradient);
            }
        </style>
    </head>
    <body>
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-md navbar-custom">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="bi bi-app"></i>
                    {{ config('app.name') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                Home
                            </a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                    Profil
                                </a>
                            </li>
                            @if(auth()->user()->hasRole(['admin', 'super-admin']))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                        Admin
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear"></i> Pengaturan</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item" style="border: none; background: none; cursor: pointer;">
                                                <i class="bi bi-box-arrow-right"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            @isset($header)
                <div class="page-header">
                    {{ $header }}
                </div>
            @endisset

            <div class="container-fluid">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> <strong>Terjadi kesalahan!</strong>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>

        <!-- Bottom Navigation (Mobile) -->
        @auth
            <div class="bottom-nav">
                <a href="{{ route('dashboard') }}" class="nav-item-bottom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="nav-item-bottom {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person"></i>
                    <span>Profil</span>
                </a>
                @if(auth()->user()->hasRole(['admin', 'super-admin']))
                    <a href="{{ route('admin.dashboard') }}" class="nav-item-bottom {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i>
                        <span>Admin</span>
                    </a>
                @endif
                <div class="dropdown nav-item-bottom">
                    <button class="nav-item-bottom" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0.6rem; border: none; background: none; width: 100%; height: 100%;">
                        <i class="bi bi-person-circle"></i>
                        <span>Akun</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear"></i> Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer; padding: 0.5rem 1rem;">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @endauth

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Register Service Worker for PWA -->
        <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js');
            });
        }
        </script>
    </body>
</html>

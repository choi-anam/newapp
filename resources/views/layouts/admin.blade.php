<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    
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
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .sidebar-nav .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Top Navigation */
        .topnav {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e9ecef;
        }

        .topnav-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topnav-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            cursor: pointer;
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 2rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            background-color: transparent;
            padding: 0 0 1rem 0;
            margin: 0;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #212529;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-header h1 i {
            color: var(--primary-color);
        }

        /* Alert Styles */
        .alert {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Card Styles */
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem;
            font-weight: 600;
            color: #212529;
        }

        /* Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
            font-weight: 600;
            color: #495057;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e9ecef;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Button Styles */
        .btn {
            border-radius: 0.375rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.625rem 0.875rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        /* Badge Styles */
        .badge {
            padding: 0.4rem 0.8rem;
            border-radius: 0.25rem;
            font-weight: 500;
            font-size: 0.85rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .topnav {
                padding: 0.75rem 1rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table td, .table th {
                padding: 0.5rem 0.25rem !important;
            }

            .btn-group-sm {
                gap: 2px;
            }

            .btn-group-sm .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            .badge {
                font-size: 0.7rem;
            }

            .action-buttons {
                width: 100%;
            }

            .action-buttons .btn {
                flex: 1;
                min-width: 60px;
            }
        }

        .text-truncate-2 {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: slideIn 0.3s ease;
        }

        /* Utilities */
        .text-muted-sm {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .border-left-primary {
            border-left: 4px solid var(--primary-color);
        }

        .permission-checkbox {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid #e9ecef;
            border-radius: 0.375rem;
            margin: 0.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .permission-checkbox:hover {
            background-color: #f8f9fa;
            border-color: var(--primary-color);
        }

        .permission-checkbox input[type="checkbox"]:checked + label {
            font-weight: 600;
            color: var(--primary-color);
        }
    </style>
    @stack('css')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h5><i class="bi bi-shield-lock"></i> Admin Panel</h5>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div style="padding: 0.75rem 1rem; color: rgba(255, 255, 255, 0.5); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Management
                </div>

                <div class="nav-item">
                    <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i>
                        <span>Roles</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <i class="bi bi-key"></i>
                        <span>Permissions</span>
                    </a>
                </div>

                <hr style="border-color: rgba(255, 255, 255, 0.1); margin: 1rem 0;">

                <div style="padding: 0.75rem 1rem; color: rgba(255, 255, 255, 0.5); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    User
                </div>

                <div class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link">
                        <i class="bi bi-person-circle"></i>
                        <span>Profil</span>
                    </a>
                </div>

                <div class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer;">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <nav class="topnav">
                <div class="topnav-left">
                    <button class="btn btn-link d-md-none" id="sidebarToggle">
                        <i class="bi bi-list" style="font-size: 1.5rem; color: #212529;"></i>
                    </button>
                    <h6 class="mb-0" style="color: #495057;">@yield('breadcrumb', 'Dashboard')</h6>
                </div>

                <div class="topnav-right">
                    <div class="user-profile">
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div>
                            <small class="d-block" style="color: #6c757d;">Selamat Datang</small>
                            <strong style="color: #212529;">{{ auth()->user()->name }}</strong>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Content Area -->
            <div class="content">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="bi bi-exclamation-circle"></i> Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Register Service Worker for PWA -->
    <script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/service-worker.js');
    });
}
</script>
    @stack('js')
</body>
</html>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'NewApp') }} — Admin & Activity Suite</title>
    <meta name="description" content="Admin panel profesional berbasis Laravel 12 dengan Role & Permission, Activity Log, Export Excel, dan UI Bootstrap 5.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .hero {
            background: radial-gradient(60% 60% at 50% 0%, rgba(13,110,253,0.1), rgba(13,110,253,0));
        }
        .feature-icon {
            width: 48px; height: 48px; border-radius: .75rem;
            display: inline-flex; align-items: center; justify-content: center;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-shield-check text-primary"></i> {{ config('app.name', 'NewApp') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how">Cara Kerja</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-dark btn-sm" href="https://github.com/choi-anam" target="_blank" rel="noopener">
                        <i class="bi bi-github"></i> GitHub
                    </a>
                    @auth
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Admin Dashboard
                        </a>
                    @else
                        @if (Route::has('login'))
                            <a class="btn btn-primary btn-sm" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Masuk
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <header class="hero py-5 py-lg-6 border-bottom">
        <div class="container py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h1 class="display-5 fw-bold mb-3">Admin Panel & Activity Logging yang Modern</h1>
                    <p class="lead text-secondary">Kelola Roles & Permissions, lacak aktivitas pengguna secara detail, ekspor laporan ke Excel, dan nikmati UI yang responsif dengan Bootstrap 5.</p>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">
                                Mulai Kelola <i class="bi bi-arrow-right-short"></i>
                            </a>
                        @else
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                    Masuk Admin <i class="bi bi-box-arrow-in-right"></i>
                                </a>
                            @endif
                        @endauth
                        <a href="https://github.com/choi-anam" class="btn btn-outline-dark btn-lg" target="_blank" rel="noopener">
                            <i class="bi bi-github"></i> GitHub Saya
                        </a>
                    </div>
                    <div class="d-flex gap-4 mt-4 text-secondary small">
                        <span><i class="bi bi-bootstrap-fill text-primary"></i> Bootstrap 5</span>
                        <span><i class="bi bi-layers-fill text-primary"></i> Laravel 12</span>
                        <span><i class="bi bi-filetype-xlsx text-success"></i> Export Excel</span>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="h3 fw-bold text-primary">{{ \Spatie\Permission\Models\Role::count() }}</div>
                                    <div class="text-muted">Roles</div>
                                </div>
                                <div class="col-4">
                                    <div class="h3 fw-bold text-success">{{ \Spatie\Permission\Models\Permission::count() }}</div>
                                    <div class="text-muted">Permissions</div>
                                </div>
                                <div class="col-4">
                                    <div class="h3 fw-bold text-dark">{{ \App\Models\User::count() }}</div>
                                    <div class="text-muted">Users</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow-1">
        <section id="features" class="py-5">
            <div class="container">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Fitur Unggulan</h2>
                    <p class="text-secondary">Semua yang Anda butuhkan untuk administrasi modern.</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="feature-icon bg-primary bg-opacity-10 text-primary mb-3"><i class="bi bi-people"></i></div>
                                <h5 class="card-title">Role & Permission</h5>
                                <p class="card-text text-secondary">RBAC lengkap dengan Spatie. Kelola roles, permissions, dan assignment ke user.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="feature-icon bg-info bg-opacity-10 text-info mb-3"><i class="bi bi-activity"></i></div>
                                <h5 class="card-title">Activity Logging</h5>
                                <p class="card-text text-secondary">Audit trail otomatis: siapa, apa, kapan, dan perubahan atribut.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="feature-icon bg-success bg-opacity-10 text-success mb-3"><i class="bi bi-filetype-xlsx"></i></div>
                                <h5 class="card-title">Export Excel</h5>
                                <p class="card-text text-secondary">Ekspor Users, Activities, dan Arsip sesuai filter ke Excel.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="feature-icon bg-warning bg-opacity-10 text-warning mb-3"><i class="bi bi-archive"></i></div>
                                <h5 class="card-title">Archive & Cleanup</h5>
                                <p class="card-text text-secondary">Arsipkan log lama, pulihkan bila perlu, atau bersihkan permanen.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        Lihat Dashboard Admin
                    </a>
                </div>
            </div>
        </section>

        <section id="how" class="py-5 bg-light border-top border-bottom">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <h3 class="fw-bold mb-2">Alur Aktivitas yang Transparan</h3>
                        <p class="text-secondary">Setiap perubahan penting akan tercatat otomatis melalui observer dan tersimpan di Activity Log. Admin dapat meninjau, mengekspor, mengarsipkan, atau membersihkan log sesuai kebutuhan.</p>
                        <ul class="text-secondary">
                            <li>Logging otomatis create / update / delete</li>
                            <li>Filter berdasarkan user, model, dan tanggal</li>
                            <li>Arsip + restore dengan aman</li>
                            <li>Export dengan satu klik</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 border-end">
                                        <div class="fw-bold">Log Hari Ini</div>
                                        <div class="display-6 text-primary">{{ \Spatie\Activitylog\Models\Activity::whereDate('created_at', now()->toDateString())->count() }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="fw-bold">Total Arsip</div>
                                        <div class="display-6 text-warning">{{ \App\Models\ActivityLogArchive::count() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="py-5">
            <div class="container">
                <div class="text-center">
                    <h3 class="fw-bold mb-2">Butuh Bantuan atau Kolaborasi?</h3>
                    <p class="text-secondary">Kunjungi profil GitHub saya atau masuk ke dashboard admin untuk mulai mengelola.</p>
                    <div class="d-flex justify-content-center gap-2 mt-2">
                        <a href="https://github.com/choi-anam" class="btn btn-dark" target="_blank" rel="noopener">
                            <i class="bi bi-github"></i> GitHub: choi-anam
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                            Admin Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="mt-auto py-4 bg-white border-top">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="text-secondary small">© {{ date('Y') }} {{ config('app.name', 'NewApp') }}. All rights reserved.</span>
            <a class="text-decoration-none small" href="https://github.com/choi-anam" target="_blank" rel="noopener">
                <i class="bi bi-github"></i> github.com/choi-anam
            </a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<x-app-layout>
    <x-slot name="header">
        <h1>
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </h1>
    </x-slot>

    <div class="row mb-4">
        <!-- Welcome Card -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Selamat Datang, {{ auth()->user()->name }}! 👋</h5>
                    <p class="card-text text-muted mb-0">
                        Anda login sebagai <strong>{{ auth()->user()->roles->pluck('name')->implode(', ') ?: 'User' }}</strong>.
                        Nikmati pengalaman aplikasi kami.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-person-circle" style="font-size: 2rem; color: #667eea;"></i>
                    <h6 class="card-title mt-2 text-muted-sm">Akun Anda</h6>
                    <p class="mb-0" style="font-size: 0.9rem;">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-person-badge" style="font-size: 2rem; color: #764ba2;"></i>
                    <h6 class="card-title mt-2 text-muted-sm">Total Roles</h6>
                    <p class="mb-0" style="font-size: 0.9rem;">{{ auth()->user()->roles->count() }}</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-key" style="font-size: 2rem; color: #6f42c1;"></i>
                    <h6 class="card-title mt-2 text-muted-sm">Permissions</h6>
                    <p class="mb-0" style="font-size: 0.9rem;">{{ auth()->user()->getAllPermissions()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-calendar-check" style="font-size: 2rem; color: #20c997;"></i>
                    <h6 class="card-title mt-2 text-muted-sm">Member Sejak</h6>
                    <p class="mb-0" style="font-size: 0.9rem;">{{ auth()->user()->created_at->locale('id')->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Shortcuts -->
    @if(auth()->user()->hasRole(['admin', 'super-admin']))
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="text-muted-sm mb-3"><i class="bi bi-lightning"></i> Admin Menu</h6>
            </div>
            <div class="col-6 col-md-4">
                <a href="{{ route('admin.roles.index') }}" class="card text-decoration-none" style="transition: all 0.3s;">
                    <div class="card-body text-center">
                        <i class="bi bi-person-badge" style="font-size: 2.5rem; color: #0d6efd;"></i>
                        <h6 class="card-title mt-2">Manage Roles</h6>
                        <p class="card-text text-muted-sm small">Kelola user roles</p>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4">
                <a href="{{ route('admin.permissions.index') }}" class="card text-decoration-none" style="transition: all 0.3s;">
                    <div class="card-body text-center">
                        <i class="bi bi-key" style="font-size: 2.5rem; color: #198754;"></i>
                        <h6 class="card-title mt-2">Manage Permissions</h6>
                        <p class="card-text text-muted-sm small">Kelola permissions</p>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4">
                <a href="{{ route('admin.dashboard') }}" class="card text-decoration-none" style="transition: all 0.3s;">
                    <div class="card-body text-center">
                        <i class="bi bi-gear" style="font-size: 2.5rem; color: #6f42c1;"></i>
                        <h6 class="card-title mt-2">Admin Dashboard</h6>
                        <p class="card-text text-muted-sm small">Ke dashboard admin</p>
                    </div>
                </a>
            </div>
        </div>
    @endif

    <!-- Info Cards -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <i class="bi bi-info-circle"></i> Tentang Dashboard
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        Ini adalah dashboard pribadi Anda. Di sini Anda dapat melihat informasi akun dan mengakses berbagai fitur aplikasi.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <i class="bi bi-question-circle"></i> Butuh Bantuan?
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        Kunjungi <a href="#">halaman bantuan</a> atau hubungi tim support kami untuk pertanyaan lebih lanjut.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

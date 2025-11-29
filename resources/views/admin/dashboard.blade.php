@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-people"></i> Kelola Users
        </a>
        <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-clock-history"></i> Activity Log
        </a>
        <a href="{{ route('admin.activities.archives') }}" class="btn btn-outline-info btn-sm">
            <i class="bi bi-archive"></i> Arsip Aktivitas
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted-sm d-block mb-1">Total Roles</small>
                        <h2 style="margin: 0; font-weight: 700; color: #0d6efd;">
                            {{ \Spatie\Permission\Models\Role::count() }}
                        </h2>
                    </div>
                    <div style="font-size: 2rem; color: #0d6efd; opacity: 0.2;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
                <hr>
                <a href="{{ route('admin.roles.index') }}" class="text-decoration-none text-muted-sm small">
                    Lihat semua roles <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted-sm d-block mb-1">Total Permissions</small>
                        <h2 style="margin: 0; font-weight: 700; color: #198754;">
                            {{ \Spatie\Permission\Models\Permission::count() }}
                        </h2>
                    </div>
                    <div style="font-size: 2rem; color: #198754; opacity: 0.2;">
                        <i class="bi bi-key"></i>
                    </div>
                </div>
                <hr>
                <a href="{{ route('admin.permissions.index') }}" class="text-decoration-none text-muted-sm small">
                    Lihat semua permissions <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted-sm d-block mb-1">Total Users</small>
                        <h2 style="margin: 0; font-weight: 700; color: #6f42c1;">
                            {{ \App\Models\User::count() }}
                        </h2>
                    </div>
                    <div style="font-size: 2rem; color: #6f42c1; opacity: 0.2;">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <hr>
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted-sm small">
                    Lihat semua users <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Activity Logging Status -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-record-circle"></i> Activity Logging Status
            </div>
            <div class="card-body">
                @php
                    try {
                        $settings = \App\Models\ActivityLogSetting::all();
                        $enabledCount = $settings->where('enabled', true)->count();
                        $disabledCount = $settings->where('enabled', false)->count();
                        $totalCount = $settings->count();
                        $totalLogs = \Spatie\Activitylog\Models\Activity::count();
                        $logsToday = \Spatie\Activitylog\Models\Activity::whereDate('created_at', now()->toDateString())->count();
                        $logsThisMonth = \Spatie\Activitylog\Models\Activity::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
                        $archivedCount = \App\Models\ActivityLogArchive::count();
                    } catch (\Exception $e) {
                        $enabledCount = 0;
                        $disabledCount = 0;
                        $totalCount = 0;
                        $totalLogs = 0;
                        $logsToday = 0;
                        $logsThisMonth = 0;
                        $archivedCount = 0;
                    }
                @endphp
                
                @if($totalCount > 0)
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <div class="h4 mb-0 text-primary">{{ $enabledCount }}</div>
                                <small class="text-muted">Models Tracked</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <div class="h4 mb-0 text-danger">{{ $disabledCount }}</div>
                                <small class="text-muted">Models Disabled</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <div class="h4 mb-0 text-info">{{ $totalCount }}</div>
                                <small class="text-muted">Total Models</small>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center mt-2">
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded border-left-primary">
                                <div class="h5 mb-0">{{ $totalLogs }}</div>
                                <small class="text-muted">Total Logs</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded border-left-primary">
                                <div class="h5 mb-0">{{ $logsToday }}</div>
                                <small class="text-muted">Logs Hari Ini</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded border-left-primary">
                                <div class="h5 mb-0">{{ $logsThisMonth }}</div>
                                <small class="text-muted">Logs Bulan Ini</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded border-left-primary">
                                <div class="h5 mb-0">{{ $archivedCount }}</div>
                                <small class="text-muted">Total Arsip</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.settings.activity-log.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-sliders"></i> Configure Tracking
                        </a>
                        <a href="{{ route('admin.activities.management') }}" class="btn btn-sm btn-outline-primary ms-2">
                            <i class="bi bi-gear"></i> Manage / Archive
                        </a>
                    </div>
                @else
                    <p class="text-muted-sm text-center mb-0">No activity logging configuration found. Please run migrations.</p>
                @endif
            </div>
        </div>
    </div>

<div class="row mb-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-lightning"></i> Quick Actions
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-auto">
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Role
                        </a>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Permission
                        </a>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-info btn-sm">
                            <i class="bi bi-list"></i> Manage Roles
                        </a>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-list"></i> Manage Permissions
                        </a>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.settings.activity-log.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-sliders"></i> Activity Log Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Online Users -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-wifi"></i> User Online (5 menit terakhir)</span>
                <small id="online-users-updated" class="text-muted-sm"></small>
            </div>
            <div class="card-body">
                <div id="online-users-empty" class="text-muted-sm">Memuat data user online...</div>
                <ul id="online-users-list" class="list-group list-group-flush d-none"></ul>
            </div>
        </div>
    </div>
    
</div>

<!-- Recent Roles -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Roles Terbaru
            </div>
            <div class="card-body">
                @php
                    $recentRoles = \Spatie\Permission\Models\Role::latest()->take(5)->get();
                @endphp
                
                @if($recentRoles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                @foreach($recentRoles as $role)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $role->name }}</span>
                                        </td>
                                        <td class="text-end">
                                            <small class="text-muted-sm">{{ $role->created_at->locale('id')->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-2">
                        <a href="{{ route('admin.roles.index') }}" class="text-decoration-none text-muted-sm small">
                            Lihat semua roles →
                        </a>
                    </div>
                @else
                    <p class="text-muted-sm text-center mb-0">Belum ada roles</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Permissions -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Permissions Terbaru
            </div>
            <div class="card-body">
                @php
                    $recentPermissions = \Spatie\Permission\Models\Permission::latest()->take(5)->get();
                @endphp
                
                @if($recentPermissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                @foreach($recentPermissions as $permission)
                                    <tr>
                                        <td>
                                            <span class="badge bg-success">{{ $permission->name }}</span>
                                        </td>
                                        <td class="text-end">
                                            <small class="text-muted-sm">{{ $permission->created_at->locale('id')->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-2">
                        <a href="{{ route('admin.permissions.index') }}" class="text-decoration-none text-muted-sm small">
                            Lihat semua permissions →
                        </a>
                    </div>
                @else
                    <p class="text-muted-sm text-center mb-0">Belum ada permissions</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-activity"></i> Aktivitas Terbaru
            </div>
            <div class="card-body">
                @php
                    $recentActivities = \Spatie\Activitylog\Models\Activity::with('causer')->latest()->take(6)->get();
                @endphp

                @if($recentActivities->count())
                    <ul class="list-group list-group-flush">
                        @foreach($recentActivities as $act)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <div><strong>{{ $act->description }}</strong></div>
                                    <div class="small text-muted">{{ $act->causer ? $act->causer->name : 'system' }} • {{ class_basename($act->subject_type ?? '') }} @if($act->subject) #{{ $act->subject->id }} @endif</div>
                                </div>
                                <div class="text-end small text-muted">{{ $act->created_at->diffForHumans() }}</div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="text-center mt-2">
                        <a href="{{ route('admin.activities.index') }}" class="text-decoration-none small">Lihat semua aktivitas →</a>
                    </div>
                @else
                    <p class="text-muted-sm text-center mb-0">Belum ada aktivitas</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Mini Charts: Logs 7 hari terakhir -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-graph-up"></i> Tren Aktivitas (7 Hari Terakhir)
            </div>
            <div class="card-body">
                <canvas id="logsTrendChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Info Cards -->
<div class="row mt-4">
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Tentang Admin Panel
            </div>
            <div class="card-body">
                <p class="text-muted-sm">
                    Selamat datang di Admin Panel Manajemen Role dan Permission. Gunakan sidebar untuk navigasi ke berbagai menu.
                </p>
                <ul class="text-muted-sm">
                    <li><strong>Roles:</strong> Kelompok izin untuk user</li>
                    <li><strong>Permissions:</strong> Izin individual untuk akses fitur</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-exclamation-triangle"></i> Catatan Penting
            </div>
            <div class="card-body">
                <ul class="text-muted-sm mb-0">
                    <li>Role "super-admin" tidak dapat dihapus</li>
                    <li>Hapus permission dengan hati-hati</li>
                    <li>Gunakan naming convention yang konsisten</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Build last 7 days labels and counts inline via PHP
    const labels = [
        @php
            $days = collect(range(6,0))->map(function($d){ return now()->subDays($d); });
            echo $days->map(fn($d) => '"'.$d->format('d M').'"')->implode(',');
        @endphp
    ];
    const dataCounts = [
        @php
            $counts = $days->map(function($d){
                return \Spatie\Activitylog\Models\Activity::whereDate('created_at', $d->toDateString())->count();
            });
            echo $counts->implode(',');
        @endphp
    ];

    const ctx = document.getElementById('logsTrendChart');
    if (ctx && window.Chart) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Aktivitas / Hari',
                    data: dataCounts,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.15)',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision:0 } }
                }
            }
        });
    }
});

// Online users auto-refresh
document.addEventListener('DOMContentLoaded', function () {
    const listEl = document.getElementById('online-users-list');
    const emptyEl = document.getElementById('online-users-empty');
    const updatedEl = document.getElementById('online-users-updated');

    async function fetchOnlineUsers() {
        try {
            const res = await fetch("{{ route('admin.users.online-data') }}?minutes=5", { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            if (!res.ok) throw new Error('Failed to fetch');
            const json = await res.json();
            const users = json.users || [];

            listEl.innerHTML = '';
            if (users.length === 0) {
                emptyEl.textContent = 'Tidak ada user online saat ini.';
                emptyEl.classList.remove('d-none');
                listEl.classList.add('d-none');
            } else {
                emptyEl.classList.add('d-none');
                listEl.classList.remove('d-none');

                users.forEach(u => {
                    const roles = (u.roles || []).map(r => `<span class="badge bg-secondary me-1">${r}</span>`).join('');
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.innerHTML = `
                        <div>
                            <div><strong>${u.name}</strong> <small class="text-muted">${u.email || ''}</small></div>
                            <div>${roles}</div>
                        </div>
                        <div class="small text-muted">${u.last_seen_human || ''}</div>
                    `;
                    listEl.appendChild(li);
                });
            }

            const now = new Date();
            updatedEl.textContent = `Diperbarui: ${now.toLocaleTimeString()}`;
        } catch (e) {
            emptyEl.textContent = 'Gagal memuat data user online.';
            emptyEl.classList.remove('d-none');
            listEl.classList.add('d-none');
        }
    }

    fetchOnlineUsers();
    setInterval(fetchOnlineUsers, 30000); // 30s fallback (SSE/WebSocket may update faster)
});
</script>
@endpush

@extends('layouts.admin')

@section('title', 'Activity Log Management')
@section('breadcrumb', 'Activity Log Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="bi bi-file-earmark-arrow-up"></i> Manajemen Log Aktivitas
        </h1>
        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Log
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Terjadi Kesalahan!</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Total Log</h5>
                    <h2 class="text-primary">{{ number_format($totalLogs) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Log Hari Ini</h5>
                    <h2 class="text-info">{{ number_format($logsToday) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Log Bulan Ini</h5>
                    <h2 class="text-success">{{ number_format($logsThisMonth) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Log Terarsipkan</h5>
                    <h2 class="text-warning">{{ number_format($archivedCount) }}</h2>
                    @if($archivedCount > 0)
                        <small><a href="{{ route('admin.activities.archives') }}" class="text-decoration-none">Lihat Arsip</a></small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Operations -->
    <div class="row">
        <!-- Archive Logs -->
        <div class="col-lg-6 mb-4">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-archive"></i> Arsipkan Log Lama
                </div>
                <div class="card-body">
                    <p class="text-muted">Pindahkan log yang lebih lama dari X hari ke arsip. Data tetap tersimpan dengan aman.</p>
                    
                    @if($oldestActivity)
                        <small class="d-block mb-3 text-muted">
                            <i class="bi bi-clock"></i> Log tertua: {{ $oldestActivity->created_at->locale('id')->format('d M Y H:i') }}
                        </small>
                    @endif

                    <form action="{{ route('admin.activities.archive') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="archive_days" class="form-label">Arsipkan log lebih lama dari:</label>
                            <div class="input-group">
                                <input type="number" id="archive_days" name="days" class="form-control" value="90" min="1" max="365" required>
                                <span class="input-group-text">hari</span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Contoh: 90 akan mengarsipkan semua log dari 90 hari yang lalu
                            </small>
                        </div>
                        <button type="submit" class="btn btn-info w-100" onclick="return confirm('Arsipkan log? Data akan dipindahkan ke tabel arsip.')">
                            <i class="bi bi-archive"></i> Arsipkan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Logs -->
        <div class="col-lg-6 mb-4">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="bi bi-trash"></i> Hapus Log Lama
                </div>
                <div class="card-body">
                    <p class="text-muted">Hapus permanen log yang lebih lama dari X hari. <strong>Aksi ini tidak dapat dibatalkan!</strong></p>
                    
                    <form action="{{ route('admin.activities.cleanup') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="cleanup_days" class="form-label">Hapus log lebih lama dari:</label>
                            <div class="input-group">
                                <input type="number" id="cleanup_days" name="days" class="form-control" value="180" min="1" max="365" required>
                                <span class="input-group-text">hari</span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Contoh: 180 akan menghapus semua log dari 180 hari yang lalu
                            </small>
                        </div>
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('⚠️ PERHATIAN! Ini akan menghapus log secara permanen dan tidak dapat dibatalkan. Lanjutkan?')">
                            <i class="bi bi-trash"></i> Hapus Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Dangerous Operations -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-dark">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-exclamation-triangle"></i> Operasi Berbahaya
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h6>Hapus Semua Log</h6>
                            <p class="text-muted small">Hapus SEMUA log aktivitas dari database. <strong class="text-danger">TIDAK DAPAT DIBATALKAN!</strong></p>
                            <form action="{{ route('admin.activities.truncate') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="confirm" value="yes">
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirmDangerous('Anda yakin ingin menghapus SEMUA log? Ini tidak dapat dibatalkan!')">
                                    <i class="bi bi-trash"></i> Hapus Semua
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-header">
                    <i class="bi bi-lightbulb"></i> Rekomendasi
                </div>
                <div class="card-body text-muted small">
                    <ul class="mb-0">
                        <li><strong>Arsipkan secara rutin:</strong> Arsipkan log yang lebih dari 90 hari setiap bulan untuk menjaga performa database</li>
                        <li><strong>Backup sebelum menghapus:</strong> Pastikan Anda memiliki backup database sebelum menghapus log</li>
                        <li><strong>Gunakan scheduled command:</strong> Buat task scheduler untuk otomasi pengarsipan log bulanan</li>
                        <li><strong>Monitor ukuran database:</strong> Cek secara berkala ukuran tabel activities untuk mendeteksi pertumbuhan data</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDangerous(message) {
    return confirm(message);
}
</script>
@endsection

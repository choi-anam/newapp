@extends('layouts.admin')

@section('title', 'Archived Activity Logs')
@section('breadcrumb', 'Archived Activity Logs')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="bi bi-archive"></i> Log Terarsipkan
        </h1>
        <a href="{{ route('admin.activities.management') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

    <!-- Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="log_type" class="form-label">Tipe Arsip</label>
                            <select name="log_type" id="log_type" class="form-select">
                                <option value="">-- Semua Tipe --</option>
                                <option value="manual" @selected(request('log_type') === 'manual')>Manual</option>
                                <option value="scheduled" @selected(request('log_type') === 'scheduled')>Terjadwal</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.activities.archives') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Archives Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total: <strong>{{ $archives->total() }}</strong> arsip</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.activities.archives-export', request()->only('log_type','date_from','date_to')) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </a>
                            <form action="{{ route('admin.activities.archives-bulk-restore') }}" method="POST" onsubmit="return confirm('Pulihkan semua arsip sesuai filter saat ini?')">
                                @csrf
                                <input type="hidden" name="log_type" value="{{ request('log_type') }}">
                                <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                                <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                                <input type="hidden" name="confirm" value="yes">
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="bi bi-arrow-counterclockwise"></i> Pulihkan Semua (Filter)
                                </button>
                            </form>
                            <form action="{{ route('admin.activities.archives-bulk-delete') }}" method="POST" onsubmit="return confirm('⚠️ PERHATIAN! Ini akan menghapus arsip sesuai filter saat ini dan tidak dapat dibatalkan. Lanjutkan?')">
                                @csrf
                                <input type="hidden" name="log_type" value="{{ request('log_type') }}">
                                <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                                <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                                <input type="hidden" name="confirm" value="yes">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Hapus Arsip (Filter)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($archives->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu Arsip</th>
                                        <th>Deskripsi Log</th>
                                        <th>Model</th>
                                        <th>User</th>
                                        <th>Tipe</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($archives as $archive)
                                        <tr>
                                            <td>
                                                <small>{{ $archive->archived_at->locale('id')->format('d M Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $archive->activity_data['description'] ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <small>
                                                    @if($archive->activity_data['subject_type'])
                                                        <span class="badge bg-secondary">
                                                            {{ class_basename($archive->activity_data['subject_type']) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <small>
                                                    @if($archive->activity_data['causer_id'])
                                                        @php
                                                            $user = \App\Models\User::find($archive->activity_data['causer_id']);
                                                        @endphp
                                                        {{ $user?->name ?? 'Deleted' }}
                                                    @else
                                                        <span class="text-muted">System</span>
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <small>
                                                    <span class="badge bg-{{ $archive->log_type === 'manual' ? 'info' : 'warning' }}">
                                                        {{ ucfirst($archive->log_type) }}
                                                    </span>
                                                </small>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.activities.restore-archive', $archive) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-outline-success" onclick="return confirm('Pulihkan log ini dari arsip?')" title="Restore">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </button>
                                                </form>
                                                <button class="btn btn-xs btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $archive->id }}" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <form action="{{ route('admin.activities.archives-destroy', $archive) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-outline-danger" onclick="return confirm('Hapus arsip ini secara permanen?')" title="Hapus Arsip">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Detail Modal -->
                                        <div class="modal fade" id="detailModal{{ $archive->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detail Arsip</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <dl class="row">
                                                            <dt class="col-sm-3">Deskripsi:</dt>
                                                            <dd class="col-sm-9"><code>{{ $archive->activity_data['description'] ?? '-' }}</code></dd>

                                                            <dt class="col-sm-3">Model:</dt>
                                                            <dd class="col-sm-9"><code>{{ $archive->activity_data['subject_type'] ?? '-' }}</code></dd>

                                                            <dt class="col-sm-3">Model ID:</dt>
                                                            <dd class="col-sm-9"><code>{{ $archive->activity_data['subject_id'] ?? '-' }}</code></dd>

                                                            <dt class="col-sm-3">Event:</dt>
                                                            <dd class="col-sm-9"><code>{{ $archive->activity_data['event'] ?? '-' }}</code></dd>

                                                            <dt class="col-sm-3">Waktu Asli:</dt>
                                                            <dd class="col-sm-9">{{ \Carbon\Carbon::parse($archive->activity_data['created_at'] ?? now())->locale('id')->format('d M Y H:i:s') }}</dd>

                                                            <dt class="col-sm-3">Data:</dt>
                                                            <dd class="col-sm-9">
                                                                <pre class="bg-light p-2 rounded" style="max-height: 300px; overflow: auto;"><code>{{ json_encode($archive->activity_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        <form action="{{ route('admin.activities.restore-archive', $archive) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success" onclick="return confirm('Pulihkan log ini?')">
                                                                <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $archives->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Tidak ada log terarsipkan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endsection

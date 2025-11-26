@extends('layouts.admin')

@section('title', 'Activity Log')
@section('breadcrumb', 'Activity Log')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="bi bi-clock-history"></i>
            Activity Log
        </h1>
        <a href="{{ route('admin.activities.management') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-sliders"></i> Manage / Archive
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <!-- Filter form -->
        <form method="GET" class="mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label small">User</label>
                    <select name="user" class="form-select form-select-sm">
                        <option value="">-- Semua User --</option>
                        @foreach($users ?? [] as $u)
                            <option value="{{ $u->id }}" {{ request('user') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label small">Model</label>
                    <select name="model" class="form-select form-select-sm">
                        <option value="">-- Semua Model --</option>
                        @foreach($modelOptions ?? [] as $short => $full)
                            <option value="{{ $short }}" {{ request('model') == $short || request('model') == $full ? 'selected' : '' }}>{{ $short }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label small">Dari</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
                </div>
                <div class="col-auto">
                    <label class="form-label small">Sampai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">Waktu</th>
                        <th>User</th>
                        <th>Deskripsi</th>
                        <th class="text-nowrap">Model</th>
                        <th class="d-none d-lg-table-cell">Properties</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr>
                            <td class="small text-muted text-nowrap">{{ $activity->created_at->diffForHumans() }}</td>
                            <td>
                                @if($activity->causer)
                                    {{ $activity->causer->name }}<br>
                                    <small class="text-muted">{{ $activity->causer->email }}</small>
                                @else
                                    <span class="text-muted">system</span>
                                @endif
                            </td>
                            <td class="text-truncate" style="max-width: 150px;" title="{{ $activity->description }}">{{ $activity->description }}</td>
                            <td class="small text-muted text-nowrap">{{ class_basename($activity->subject_type ?? '') }}</td>
                            <td class="small text-muted d-none d-lg-table-cell">@if($activity->properties->isNotEmpty())<pre style="margin:0; max-width:250px; white-space:pre-wrap; font-size:0.75rem">{{ $activity->properties->toJson() }}</pre>@endif</td>
                            <td class="text-center">
                                <a href="{{ route('admin.activities.show', $activity) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada aktivitas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div class="text-muted small">Menampilkan {{ $activities->count() }} dari {{ $activities->total() }} aktivitas</div>
            <div>
                {{ $activities->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

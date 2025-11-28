@extends('layouts.admin')

@section('title', 'Activity Log')
@section('breadcrumb', 'Activity Log')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <h1>
            <i class="bi bi-clock-history"></i>
            Activity Log
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.activities.management') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-sliders"></i> Manage / Archive
            </a>
            <a href="{{ route('admin.activities.export') }}" class="btn btn-sm btn-success">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3" id="filters">
            <div class="col-md-3">
                <label class="form-label small">User</label>
                <select id="filterUser" class="form-select form-select-sm">
                    <option value="">-- Semua User --</option>
                    @foreach($users ?? [] as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Model</label>
                <select id="filterModel" class="form-select form-select-sm">
                    <option value="">-- Semua Model --</option>
                    @foreach($models ?? [] as $m)
                        <option value="{{ $m }}">{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Dari</label>
                <input type="date" id="filterDateFrom" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Sampai</label>
                <input type="date" id="filterDateTo" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button id="applyFilter" class="btn btn-primary btn-sm me-2">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <button id="resetFilter" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Activities Table with Grid.js -->
<div class="card">
    <div class="card-body">
        <div id="activities-grid"></div>
    </div>
</div>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
<style>
    .gridjs-wrapper {
        border-radius: 8px;
        overflow: hidden;
    }
    .gridjs-table {
        font-size: 0.9rem;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentFilters = {
        user: '',
        model: '',
        date_from: '',
        date_to: ''
    };
    
    function buildUrl() {
        let url = '{{ route("admin.activities.data") }}';
        const params = new URLSearchParams();
        
        if (currentFilters.user) params.append('user', currentFilters.user);
        if (currentFilters.model) params.append('model', currentFilters.model);
        if (currentFilters.date_from) params.append('date_from', currentFilters.date_from);
        if (currentFilters.date_to) params.append('date_to', currentFilters.date_to);
        
        return url + (params.toString() ? '?' + params.toString() : '');
    }
    
    const grid = new gridjs.Grid({
        columns: [
            { name: 'ID', hidden: true },
            { id: 'time', name: 'Waktu', width: '150px' },
            { id: 'user', name: 'User', width: '150px' },
            { id: 'description', name: 'Deskripsi' },
            { id: 'model', name: 'Model', width: '120px' },
            { id: 'event', name: 'Event', width: '100px', formatter: (cell) => gridjs.html(cell) },
            {
                name: 'Aksi',
                width: '80px',
                formatter: (cell, row) => {
                    const activityId = row.cells[0].data;
                    return gridjs.html(`
                        <a href="/admin/activities/${activityId}" class="btn btn-sm btn-outline-primary" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                    `);
                }
            }
        ],
        server: {
            url: buildUrl(),
            then: data => data.data,
            total: data => data.total
        },
        search: {
            server: {
                url: (prev, search) => {
                    const url = new URL(prev, window.location.origin);
                    url.searchParams.set('search', search);
                    url.searchParams.set('page', '0');
                    url.searchParams.set('limit', '20');
                    return url.toString();
                }
            }
        },
        pagination: {
            enabled: true,
            limit: 20,
            server: {
                url: (prev, page, limit) => {
                    const url = new URL(prev, window.location.origin);
                    url.searchParams.set('page', page);
                    url.searchParams.set('limit', limit);
                    return url.toString();
                }
            }
        },
        sort: false,
        fixedHeader: true,
        height: '600px',
        language: {
            'search': {
                'placeholder': 'Cari aktivitas...'
            },
            'pagination': {
                'previous': 'Sebelumnya',
                'next': 'Selanjutnya',
                'showing': 'Menampilkan',
                'results': () => 'hasil',
                'of': 'dari',
                'to': 'hingga'
            },
            'loading': 'Memuat...',
            'noRecordsFound': 'Tidak ada data',
            'error': 'Terjadi kesalahan'
        }
    }).render(document.getElementById('activities-grid'));
    
    // Apply filters
    document.getElementById('applyFilter').addEventListener('click', function() {
        currentFilters.user = document.getElementById('filterUser').value;
        currentFilters.model = document.getElementById('filterModel').value;
        currentFilters.date_from = document.getElementById('filterDateFrom').value;
        currentFilters.date_to = document.getElementById('filterDateTo').value;
        
        grid.updateConfig({
            server: {
                url: buildUrl(),
                then: data => data.data,
                total: data => data.total
            }
        }).forceRender();
    });
    
    // Reset filters
    document.getElementById('resetFilter').addEventListener('click', function() {
        currentFilters = { user: '', model: '', date_from: '', date_to: '' };
        document.getElementById('filterUser').value = '';
        document.getElementById('filterModel').value = '';
        document.getElementById('filterDateFrom').value = '';
        document.getElementById('filterDateTo').value = '';
        
        grid.updateConfig({
            server: {
                url: buildUrl(),
                then: data => data.data,
                total: data => data.total
            }
        }).forceRender();
    });
    
    // Auto refresh setiap 30 detik
    setInterval(() => {
        grid.forceRender();
    }, 30000);
});
</script>
@endpush

@extends('layouts.admin')

@section('title', 'Kelola Users')
@section('breadcrumb', 'Users')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-people"></i>
        Kelola Users
    </h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah User
        </a>
        <a href="{{ route('admin.users.export') }}" class="btn btn-success btn-sm">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    </div>
</div>

<!-- Stats Section -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted d-block mb-1">Total Users</small>
                        <h3 style="margin: 0; font-weight: 700;">{{ \App\Models\User::count() }}</h3>
                    </div>
                    <i class="bi bi-people" style="font-size: 2rem; color: #6f42c1; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted d-block mb-1">Admin Users</small>
                        <h3 style="margin: 0; font-weight: 700;">{{ \App\Models\User::role(['admin', 'super-admin'])->count() }}</h3>
                    </div>
                    <i class="bi bi-shield-check" style="font-size: 2rem; color: #0d6efd; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted d-block mb-1">Regular Users</small>
                        <h3 style="margin: 0; font-weight: 700;">{{ \App\Models\User::role(['user', 'editor'])->count() }}</h3>
                    </div>
                    <i class="bi bi-person-check" style="font-size: 2rem; color: #198754; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table with Grid.js -->
<div class="card">
    <div class="card-body">
        <div id="users-grid"></div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Yakin ingin menghapus user ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
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
    .action-buttons {
        display: flex;
        gap: 4px;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let deleteUserId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    
    const grid = new gridjs.Grid({
        columns: [
            { id: 'id', name: 'ID', width: '60px' },
            { id: 'name', name: 'Nama' },
            { 
                id: 'username', 
                name: 'Username',
                formatter: (cell) => cell || '-'
            },
            { 
                id: 'email', 
                name: 'Email',
                formatter: (cell) => gridjs.html(`<small>${cell}</small>`)
            },
            { 
                id: 'roles', 
                name: 'Roles',
                formatter: (cell) => gridjs.html(cell || '-')
            },
            { 
                id: 'created_at', 
                name: 'Terdaftar',
                width: '120px'
            },
            {
                name: 'Aksi',
                width: '180px',
                formatter: (cell, row) => {
                    const userId = row.cells[0].data;
                    return gridjs.html(`
                        <div class="action-buttons">
                            <a href="/admin/users/${userId}" class="btn btn-sm btn-outline-primary" title="Lihat">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/admin/users/${userId}/edit" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${userId}" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `);
                }
            }
        ],
        server: {
            url: '{{ route("admin.users.data") }}',
            then: data => data.data,
            total: data => data.total
        },
        search: {
            server: {
                url: (prev, search) => {
                    const url = new URL(prev, window.location.origin);
                    url.searchParams.set('search', search);
                    url.searchParams.set('page', '0');
                    url.searchParams.set('limit', '10');
                    return url.toString();
                }
            }
        },
        pagination: {
            enabled: true,
            limit: 10,
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
                'placeholder': 'Cari user...'
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
    }).render(document.getElementById('users-grid'));
    
    // Auto refresh setiap 30 detik
    setInterval(() => {
        grid.forceRender();
    }, 30000);
    
    // Handle delete button
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            deleteUserId = e.target.closest('.delete-btn').dataset.id;
            deleteModal.show();
        }
    });
    
    // Confirm delete
    document.getElementById('confirmDelete').addEventListener('click', async function() {
        if (!deleteUserId) return;
        
        try {
            const response = await fetch(`/admin/users/${deleteUserId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                deleteModal.hide();
                grid.forceRender();
                
                // Show success toast
                const toast = document.createElement('div');
                toast.className = 'alert alert-success position-fixed top-0 end-0 m-3';
                toast.innerHTML = '<i class="bi bi-check-circle"></i> User berhasil dihapus';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            } else {
                const errorData = await response.json();
                deleteModal.hide();
                
                // Show error toast
                const toast = document.createElement('div');
                toast.className = 'alert alert-danger position-fixed top-0 end-0 m-3';
                toast.innerHTML = '<i class="bi bi-exclamation-circle"></i> ' + (errorData.error || 'Gagal menghapus user');
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 5000);
            }
        } catch (error) {
            console.error('Error deleting user:', error);
        }
    });
});
</script>
@endpush

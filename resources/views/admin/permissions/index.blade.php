@extends('layouts.admin')

@section('title', 'Permissions')
@section('breadcrumb', 'Permissions Management')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-key"></i>
        Manajemen Permissions
    </h1>
    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Permission Baru
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-table"></i> Daftar Permissions
    </div>
    <div class="card-body">
        @if($permissions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Permission</th>
                            <th>Deskripsi</th>
                            <th>Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $key => $permission)
                            <tr>
                                <td class="fw-bold">{{ $permissions->firstItem() + $key }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $permission->name }}</span>
                                </td>
                                <td class="text-truncate-2" style="max-width: 150px;" title="{{ $permission->description }}">
                                    @if($permission->description)
                                        {{ $permission->description }}
                                    @else
                                        <span class="text-muted-sm">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted-sm">
                                        {{ $permission->created_at->locale('id')->format('d M Y H:i') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('admin.permissions.show', $permission) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus permission ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted-sm py-5">
                                    <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                                    <p class="mt-2">Tidak ada data permissions</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted-sm">Menampilkan {{ $permissions->firstItem() ?? 0 }} hingga {{ $permissions->lastItem() ?? 0 }} dari {{ $permissions->total() }} data</small>
                <nav>
                    {{ $permissions->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                <p class="text-muted-sm mt-3">Belum ada data permissions. <a href="{{ route('admin.permissions.create') }}">Buat permission baru</a></p>
            </div>
        @endif
    </div>
</div>
@endsection

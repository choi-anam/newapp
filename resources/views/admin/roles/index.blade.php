@extends('layouts.admin')

@section('title', 'Roles')
@section('breadcrumb', 'Roles Management')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-person-badge"></i>
        Manajemen Roles
    </h1>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Role Baru
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-table"></i> Daftar Roles
    </div>
    <div class="card-body">
        @if($roles->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Role</th>
                            <th>Deskripsi</th>
                            <th>Permissions</th>
                            <th>Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $key => $role)
                            <tr>
                                <td class="fw-bold">{{ $roles->firstItem() + $key }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                </td>
                                <td>
                                    @if($role->description)
                                        {{ Str::limit($role->description, 50) }}
                                    @else
                                        <span class="text-muted-sm">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($role->permissions->count() > 0)
                                        <small class="badge bg-info">{{ $role->permissions->count() }} permissions</small>
                                    @else
                                        <span class="text-muted-sm">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted-sm">
                                        {{ $role->created_at->locale('id')->format('d M Y H:i') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($role->name !== 'super-admin')
                                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus role ini?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted-sm py-5">
                                    <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                                    <p class="mt-2">Tidak ada data roles</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted-sm">Menampilkan {{ $roles->firstItem() ?? 0 }} hingga {{ $roles->lastItem() ?? 0 }} dari {{ $roles->total() }} data</small>
                <nav>
                    {{ $roles->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                <p class="text-muted-sm mt-3">Belum ada data roles. <a href="{{ route('admin.roles.create') }}">Buat role baru</a></p>
            </div>
        @endif
    </div>
</div>
@endsection

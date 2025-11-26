@extends('layouts.admin')

@section('title', 'Kelola Users')
@section('breadcrumb', 'Users')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-people"></i>
        Kelola Users
    </h1>
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

<!-- Action Bar -->
<div class="card mb-4">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <span class="text-muted">Menampilkan {{ $users->count() }} dari {{ $users->total() }} users</span>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah User
        </a>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Terdaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="fw-500">
                            <i class="bi bi-person-circle" style="color: #667eea;"></i>
                            {{ $user->name }}
                        </td>
                        <td class="text-muted small text-truncate" style="max-width: 150px;" title="{{ $user->email }}">{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-{{ $role->name === 'super-admin' ? 'danger' : ($role->name === 'admin' ? 'primary' : ($role->name === 'editor' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                </span>
                            @endforeach
                        </td>
                        <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5;"></i><br>
                            Tidak ada users
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation" class="mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}
        </div>
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</nav>
@endsection

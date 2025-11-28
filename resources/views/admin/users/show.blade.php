@extends('layouts.admin')

@section('title', 'Detail User')
@section('breadcrumb', 'User: ' . $user->name)

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-person-circle"></i>
        {{ $user->name }}
    </h1>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <!-- User Info Card -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-person" style="font-size: 3rem; color: white;"></i>
                </div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted small mb-3">{{ $user->email }}</p>
                
                @if($user->roles->count())
                    <div class="mb-3">
                        @foreach($user->roles as $role)
                            <span class="badge bg-{{ $role->name === 'super-admin' ? 'danger' : ($role->name === 'admin' ? 'primary' : ($role->name === 'editor' ? 'warning' : 'secondary')) }} d-block mb-2">
                                {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <hr>
                
                <div class="text-start small">
                    <div class="mb-2">
                        <span class="text-muted">User ID:</span> <br>
                        <code>{{ $user->id }}</code>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Terdaftar:</span> <br>
                        {{ $user->created_at->format('d M Y H:i') }}
                    </div>
                    <div>
                        <span class="text-muted">Terakhir diupdate:</span> <br>
                        {{ $user->updated_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- User Permissions Card -->
        @if($user->permissions->count())
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-key"></i> Permissions
                </div>
                <div class="card-body">
                    <div style="max-height: 300px; overflow-y: auto;">
                        @foreach($user->permissions as $permission)
                            <span class="badge bg-light text-dark d-block mb-2">{{ $permission->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-8">
        <!-- Manage User -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-gear"></i> Kelola User
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="mb-2">Informasi User</h6>
                        <p class="mb-1"><strong>Nama:</strong> {{ $user->name }}</p>
                        @if($user->username)
                            <p class="mb-1"><strong>Username:</strong> {{ $user->username }}</p>
                        @endif
                        @if($user->uid)
                            <p class="mb-1"><strong>UID:</strong> {{ $user->uid }}</p>
                        @endif
                        <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                        <p class="mb-0"><strong>Status:</strong> <span class="badge bg-success">Aktif</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-2">Aksi Cepat</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit User
                            </a>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                <i class="bi bi-key"></i> Reset Password
                            </button>
                            @if($user->id !== auth()->id())
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                    <i class="bi bi-trash"></i> Hapus User
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary btn-sm" disabled title="Tidak dapat menghapus user sendiri">
                                    <i class="bi bi-trash"></i> Hapus User
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Section -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Aktivitas
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker" style="background: #667eea;"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">User Terdaftar</h6>
                            <small class="text-muted">{{ $user->created_at->format('d M Y \p\u\k\u\l H:i') }}</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker" style="background: #764ba2;"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Terakhir Diupdate</h6>
                            <small class="text-muted">{{ $user->updated_at->format('d M Y \p\u\k\u\l H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    Anda akan mereset password user <strong>{{ $user->name }}</strong>. Password baru akan dibuat secara otomatis.
                </div>
                <p class="text-muted small">User akan menerima password baru dan diminta untuk mengubahnya saat login berikutnya.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-key"></i> Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger bg-opacity-10">
                <h5 class="modal-title text-danger">Hapus User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                </div>
                <p>Anda akan menghapus user <strong>{{ $user->name }}</strong> ({{ $user->email }}) dan semua data terkait akan hilang.</p>
                <p class="text-muted small">Pastikan Anda benar-benar ingin melakukan ini sebelum melanjutkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Hapus Selamanya
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 19px;
        top: 50px;
        width: 2px;
        height: calc(100% + 20px);
        background: #e9ecef;
    }

    .timeline-marker {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        flex-shrink: 0;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #e9ecef;
    }

    .timeline-content {
        padding-top: 8px;
    }
</style>
@endsection

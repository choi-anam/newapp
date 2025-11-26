@extends('layouts.admin')

@section('title', 'Edit Role')
@section('breadcrumb', 'Edit Role')

@section('content')
<div class="page-header mb-3">
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-left-primary">
            <div class="card-header">
                <i class="bi bi-pencil-square"></i> Edit Role: <strong>{{ $role->name }}</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Role *</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $role->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted-sm">Gunakan format lowercase, contoh: manager-content</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Deskripsi role...">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block">Permissions</label>
                        <small class="text-muted-sm d-block mb-3">Pilih permissions yang akan diberikan ke role ini</small>
                        
                        @if($permissions->count() > 0)
                            <div class="row">
                                @forelse($permissions as $permission)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                   id="permission_{{ $permission->id }}" class="form-check-input"
                                                   {{ $role->permissions->contains('name', $permission->name) || in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                            <br>
                                            <small class="text-muted-sm">{{ $permission->description ?? '-' }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i>
                                            Belum ada permissions. <a href="{{ route('admin.permissions.create') }}">Buat permission baru</a>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                Belum ada permissions. <a href="{{ route('admin.permissions.create') }}">Buat permission baru</a>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Role
                        </button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informasi Role
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Nama:</strong> <br>
                        <span class="badge bg-primary">{{ $role->name }}</span>
                    </li>
                    <li class="mb-2">
                        <strong>Total Permissions:</strong> <br>
                        <span class="badge bg-info">{{ $role->permissions->count() }}</span>
                    </li>
                    <li class="mb-2">
                        <strong>Dibuat:</strong> <br>
                        <small class="text-muted-sm">{{ $role->created_at->locale('id')->format('d M Y H:i') }}</small>
                    </li>
                    <li>
                        <strong>Diubah:</strong> <br>
                        <small class="text-muted-sm">{{ $role->updated_at->locale('id')->format('d M Y H:i') }}</small>
                    </li>
                </ul>
            </div>
        </div>

        @if($role->name !== 'super-admin')
            <div class="card mt-3">
                <div class="card-header bg-danger">
                    <i class="bi bi-exclamation-triangle"></i> Zona Berbahaya
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin menghapus role ini? Tindakan ini tidak dapat dibatalkan.')">
                            <i class="bi bi-trash"></i> Hapus Role
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

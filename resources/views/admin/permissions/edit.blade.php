@extends('layouts.admin')

@section('title', 'Edit Permission')
@section('breadcrumb', 'Edit Permission')

@section('content')
<div class="page-header mb-3">
    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-left-primary">
            <div class="card-header">
                <i class="bi bi-pencil-square"></i> Edit Permission: <strong>{{ $permission->name }}</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Permission *</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $permission->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted-sm">Format: verb-noun, contoh: create-post</small>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Deskripsi permission...">{{ old('description', $permission->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Permission
                        </button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-light">
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
                <i class="bi bi-info-circle"></i> Informasi Permission
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Nama:</strong> <br>
                        <span class="badge bg-success">{{ $permission->name }}</span>
                    </li>
                    <li class="mb-2">
                        <strong>Dibuat:</strong> <br>
                        <small class="text-muted-sm">{{ $permission->created_at->locale('id')->format('d M Y H:i') }}</small>
                    </li>
                    <li>
                        <strong>Diubah:</strong> <br>
                        <small class="text-muted-sm">{{ $permission->updated_at->locale('id')->format('d M Y H:i') }}</small>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-danger">
                <i class="bi bi-exclamation-triangle"></i> Zona Berbahaya
            </div>
            <div class="card-body">
                <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin menghapus permission ini? Tindakan ini tidak dapat dibatalkan.')">
                        <i class="bi bi-trash"></i> Hapus Permission
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

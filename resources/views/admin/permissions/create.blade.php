@extends('layouts.admin')

@section('title', 'Tambah Permission')
@section('breadcrumb', 'Tambah Permission Baru')

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
                <i class="bi bi-plus-circle"></i> Buat Permission Baru
            </div>
            <div class="card-body">
                <form action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Permission *</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="Contoh: create-post, edit-user" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted-sm">Gunakan format: verb-noun, contoh: create-post, delete-comment</small>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Deskripsi permission...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Simpan Permission
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
                <i class="bi bi-lightbulb"></i> Contoh Permission
            </div>
            <div class="card-body">
                <p class="text-muted-sm"><strong>Naming Convention:</strong></p>
                <ul class="text-muted-sm">
                    <li>create-post</li>
                    <li>read-post</li>
                    <li>update-post</li>
                    <li>delete-post</li>
                    <li>manage-users</li>
                    <li>edit-settings</li>
                </ul>

                <hr>

                <p class="text-muted-sm"><strong>Tips:</strong></p>
                <ul class="text-muted-sm">
                    <li>Gunakan format verb-noun</li>
                    <li>Gunakan hyphen untuk separator</li>
                    <li>Gunakan lowercase</li>
                    <li>Singkat dan deskriptif</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

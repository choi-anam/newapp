@extends('layouts.admin')

@section('title', 'Tambah Role')
@section('breadcrumb', 'Tambah Role Baru')

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
                <i class="bi bi-plus-circle"></i> Buat Role Baru
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Role *</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="Contoh: admin, editor" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted-sm">Gunakan format lowercase, contoh: manager-content</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Deskripsi role...">{{ old('description') }}</textarea>
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
                                                   {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
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
                            <i class="bi bi-check-circle"></i> Simpan Role
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
                <i class="bi bi-lightbulb"></i> Info
            </div>
            <div class="card-body">
                <p class="text-muted-sm"><strong>Apa itu Role?</strong></p>
                <p class="text-muted-sm mb-3">Role adalah kumpulan permissions yang menentukan apa yang bisa dilakukan oleh user dengan role tersebut.</p>
                
                <p class="text-muted-sm"><strong>Tips Naming Convention:</strong></p>
                <ul class="text-muted-sm">
                    <li>Gunakan lowercase</li>
                    <li>Gunakan hyphen untuk separator: admin-user</li>
                    <li>Jangan gunakan spasi atau underscore</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

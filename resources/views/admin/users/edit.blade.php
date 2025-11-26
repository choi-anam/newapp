@extends('layouts.admin')

@section('title', 'Edit User')
@section('breadcrumb', 'Edit: ' . $user->name)

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-pencil-square"></i>
        Edit User: {{ $user->name }}
    </h1>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" placeholder="Masukkan nama lengkap" 
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" placeholder="user@example.com" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Password tidak ditampilkan di sini. Gunakan fitur "Reset Password" dari halaman detail user untuk mengubah password.
                    </div>

                    <div class="mb-4">
                        <label for="roles" class="form-label">Roles</label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @foreach($roles as $role)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="role_{{ $role->id }}" 
                                           name="roles[]" value="{{ $role->name }}"
                                           {{ $user->hasRole($role->name) || in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                        <strong>{{ ucfirst(str_replace('-', ' ', $role->name)) }}</strong>
                                        @if($role->description)
                                            <br><small class="text-muted">{{ $role->description }}</small>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Perbarui User
                        </button>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

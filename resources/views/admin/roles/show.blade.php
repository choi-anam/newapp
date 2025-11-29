@extends('layouts.admin')

@section('title', 'Detail Role')
@section('breadcrumb', 'Detail Role')

@section('content')
<div class="page-header mb-3">
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Detail Role: <strong>{{ $role->name }}</strong>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label">Nama Role</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-primary">{{ $role->name }}</span>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <p class="form-control-plaintext">
                        @if ($role->description)
                           {{ $role->description }}
                        @else
                           <span class="text-muted-sm">-</span>
                        @endif
                    </p>
                </div>

                <div class="mb-4">
                    <label class="form-label d-block">Permissions</label>
                    @if($role->permissions->count() > 0)
                        <div class="row">
                            @forelse($role->permissions as $permission)
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" disabled class="form-check-input" checked>
                                        <label class="form-check-label">
                                            {{ $permission->name }}
                                        </label>
                                        <br>
                                        <small class="text-muted-sm">{{ $permission->description ?? '-' }}</small>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted-sm">Tidak ada permissions</p>
                            @endforelse
                        </div>
                    @else
                        <p class="text-muted-sm">Tidak ada permissions yang diberikan</p>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    @can('update roles')
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    @endcan
                    @can('delete roles')
                        @if($role->name !== 'super-admin')
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus role ini?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-calendar"></i> Timeline
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <strong class="text-muted-sm">Dibuat:</strong>
                        <br>
                        <small>{{ $role->created_at->locale('id')->format('d M Y H:i') }}</small>
                    </li>
                    <li>
                        <strong class="text-muted-sm">Terakhir Diubah:</strong>
                        <br>
                        <small>{{ $role->updated_at->locale('id')->format('d M Y H:i') }}</small>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-graph-up"></i> Statistik
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <span class="text-muted-sm">Total Permissions:</span>
                        <br>
                        <strong style="font-size: 1.5rem;">{{ $role->permissions->count() }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

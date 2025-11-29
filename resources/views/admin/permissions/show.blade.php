@extends('layouts.admin')

@section('title', 'Detail Permission')
@section('breadcrumb', 'Detail Permission')

@section('content')
<div class="page-header mb-3">
    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Detail Permission: <strong>{{ $permission->name }}</strong>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label">Nama Permission</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-success">{{ $permission->name }}</span>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <p class="form-control-plaintext">
                        {{ $permission->description ?? '<span class="text-muted-sm">-</span>' }}
                    </p>
                </div>

                <div class="d-flex gap-2">
                    @can('update permissions')
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    @endcan
                    @can('delete permissions')
                        <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus permission ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
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
                        <small>{{ $permission->created_at->locale('id')->format('d M Y H:i') }}</small>
                    </li>
                    <li>
                        <strong class="text-muted-sm">Terakhir Diubah:</strong>
                        <br>
                        <small>{{ $permission->updated_at->locale('id')->format('d M Y H:i') }}</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

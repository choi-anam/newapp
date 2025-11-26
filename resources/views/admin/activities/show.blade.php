@extends('layouts.admin')

@section('title', 'Activity Detail')
@section('breadcrumb', 'Activity Detail')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-info-circle"></i>
        Activity Detail
    </h1>
</div>

<div class="card">
    <div class="card-body">
        <p><strong>Waktu:</strong> {{ $activity->created_at->format('d M Y H:i:s') }}</p>
        <p><strong>User:</strong> @if($activity->causer) {{ $activity->causer->name }} ({{ $activity->causer->email }}) @else <em>system</em> @endif</p>
        <p><strong>Deskripsi:</strong> {{ $activity->description }}</p>
        <p><strong>Model:</strong> {{ $activity->subject_type ? class_basename($activity->subject_type) : '-' }} @if($activity->subject) #{{ $activity->subject->id }} @endif</p>
        <hr>
        <h6>Properties</h6>
        <pre style="white-space: pre-wrap;">{{ $activity->properties->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

        <div class="mt-3">
            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>
</div>

@endsection

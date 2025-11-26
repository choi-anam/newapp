@extends('layouts.admin')

@section('title', 'Activity Log Settings')
@section('breadcrumb', 'Settings > Activity Log')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-sliders"></i>
        Activity Log Settings
    </h1>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-gear"></i> Tracked Models
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Toggle which models should be automatically tracked for activity logging. Optionally specify which attributes to track per model.</p>

                <form method="POST" action="{{ route('admin.settings.activity-log.update') }}">
                    @csrf

                    @if($models->count())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Model</th>
                                        <th>Class Name</th>
                                        <th>Enabled</th>
                                        <th>Tracked Attributes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($models as $model)
                                        <tr>
                                            <td>
                                                <strong>{{ $model['short_name'] }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $model['model_class'] }}</small>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input 
                                                        class="form-check-input model-toggle" 
                                                        type="checkbox" 
                                                        id="model_{{ $model['id'] }}"
                                                        name="settings[{{ $loop->index }}][enabled]"
                                                        value="1"
                                                        {{ $model['enabled'] ? 'checked' : '' }}
                                                        data-setting-id="{{ $model['id'] }}"
                                                    >
                                                    <input type="hidden" name="settings[{{ $loop->index }}][id]" value="{{ $model['id'] }}">
                                                </div>
                                            </td>
                                            <td>
                                                <input 
                                                    type="text" 
                                                    class="form-control form-control-sm"
                                                    name="settings[{{ $loop->index }}][tracked_attributes]"
                                                    placeholder="Leave blank = all attributes"
                                                    value="{{ implode(', ', $model['tracked_attributes'] ?? []) }}"
                                                    title="Comma-separated attribute names (e.g., name, email)"
                                                >
                                                <small class="text-muted d-block mt-1">Leave blank to track all attributes. Comma-separated values like: name, email</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Settings
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    @else
                        <p class="text-muted text-center">No models configured in activity-logger.php</p>
                    @endif
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> How It Works
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2">
                    <strong>Enabled models:</strong> Any changes to these models (create, update, delete, restore) will be logged in the activity log.
                </p>
                <p class="text-muted small mb-2">
                    <strong>Disabled models:</strong> Changes will not be tracked.
                </p>
                <p class="text-muted small mb-2">
                    <strong>Tracked Attributes:</strong> By default, all attributes are logged. To track only specific fields (e.g., only "name" and "email" for users), specify them as comma-separated values.
                </p>
                <p class="text-muted small mb-0">
                    <strong>Add new models:</strong> To track a new model, add its class name to <code>config/activity-logger.php</code> in the <code>models</code> array, then reload this page.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .table td {
        vertical-align: middle;
    }
</style>
@endsection

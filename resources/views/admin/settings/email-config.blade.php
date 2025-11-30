@extends('layouts.admin')

@section('title', 'Email Configuration')
@section('breadcrumb', 'Settings > Email Configuration')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-envelope"></i>
        Email Configuration
    </h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-circle"></i> Add Configuration
    </button>
</div>

<div class="row">
    <div class="col-lg-12">
        @if ($configs->count())
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-list"></i> Email Configurations
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mailer Type</th>
                                <th>From Address</th>
                                <th>Host / Port</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($configs as $config)
                                <tr>
                                    <td>
                                        <span class="badge bg-info">{{ strtoupper($config->mailer ?? 'N/A') }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $config->from_address ?? 'N/A' }}</small><br>
                                        <small class="text-muted">{{ $config->from_name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @if ($config->host && $config->port)
                                            <small class="text-muted">{{ $config->host }}:{{ $config->port }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($config->id)
                                            <div class="form-check form-switch">
                                                <input 
                                                    class="form-check-input toggle-config" 
                                                    type="checkbox" 
                                                    id="toggle_{{ $config->id }}"
                                                    data-config-id="{{ $config->id }}"
                                                    data-route="{{ route('admin.settings.email-config.toggle', $config->id) }}"
                                                    {{ $config->is_enabled ? 'checked' : '' }}
                                                >
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">Default (config)</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($config->description)
                                            <small class="text-muted">{{ Str::limit($config->description, 30, '...') }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @if ($config->id)
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal_{{ $config->id }}">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <form action="{{ route('admin.settings.email-config.destroy', $config->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-info" disabled>
                                                    <i class="bi bi-info-circle"></i> From Config
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-arrow-repeat"></i> Test Configuration
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Send a test email to verify your configuration is working correctly.</p>
                    <form method="POST" action="{{ route('admin.settings.email-config.test') }}" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label for="test_email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="test_email" name="email" placeholder="test@example.com" required>
                        </div>
                        @if ($configs->where('id', '!=', null)->count() > 1)
                        <div class="col-md-6">
                            <label for="config_id" class="form-label">Configuration</label>
                            <select class="form-select" id="config_id" name="config_id">
                                <option value="">Use Default</option>
                                @foreach ($configs->where('id', '!=', null) as $config)
                                    <option value="{{ $config->id }}">{{ $config->from_address }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Send Test Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-envelope" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <h5 class="text-muted">No Email Configuration</h5>
                    <p class="text-muted mb-3">Create your first email configuration to get started.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bi bi-plus-circle"></i> Add Configuration
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-envelope"></i> Add Email Configuration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.email-config.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mailer" class="form-label">Mailer Type *</label>
                        <select class="form-select @error('mailer') is-invalid @enderror" id="mailer" name="mailer" required onchange="updateMailerFields()">
                            <option value="">Select Mailer Type</option>
                            @foreach ($mailers as $mailer)
                                <option value="{{ $mailer }}" {{ old('mailer') === $mailer ? 'selected' : '' }}>{{ strtoupper($mailer) }}</option>
                            @endforeach
                        </select>
                        @error('mailer')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div id="smtp-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="host" class="form-label">Host</label>
                            <input type="text" class="form-control @error('host') is-invalid @enderror" id="host" name="host" placeholder="smtp.gmail.com" value="{{ old('host') }}">
                            @error('host')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="port" class="form-label">Port</label>
                            <input type="number" class="form-control @error('port') is-invalid @enderror" id="port" name="port" placeholder="587" value="{{ old('port') }}" min="1" max="65535">
                            @error('port')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="your-email@gmail.com" value="{{ old('username') }}">
                            @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="your-app-password">
                            <small class="form-text text-muted">For Gmail: Use App Passwords, not your regular password</small>
                            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="encryption" class="form-label">Encryption</label>
                            <select class="form-select @error('encryption') is-invalid @enderror" id="encryption" name="encryption">
                                <option value="">None</option>
                                @foreach ($encryptions as $encryption)
                                    @if ($encryption)
                                        <option value="{{ $encryption }}" {{ old('encryption') === $encryption ? 'selected' : '' }}>{{ strtoupper($encryption) }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('encryption')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="from_address_add" class="form-label">From Address *</label>
                        <input type="email" class="form-control @error('from_address') is-invalid @enderror" id="from_address_add" name="from_address" placeholder="noreply@example.com" value="{{ old('from_address') }}" required>
                        @error('from_address')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="from_name_add" class="form-label">From Name *</label>
                        <input type="text" class="form-control @error('from_name') is-invalid @enderror" id="from_name_add" name="from_name" placeholder="Your Application Name" value="{{ old('from_name') }}" required>
                        @error('from_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description_add" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description_add" name="description" rows="2" placeholder="Add a description for this configuration"></textarea>
                        @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_enabled_add" name="is_enabled" checked>
                        <label class="form-check-label" for="is_enabled_add">
                            Enable this configuration
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modals -->
@foreach ($configs->where('id', '!=', null) as $config)
<div class="modal fade" id="editModal_{{ $config->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-envelope"></i> Edit Email Configuration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.email-config.update', $config->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mailer_{{ $config->id }}" class="form-label">Mailer Type *</label>
                        <select class="form-select @error('mailer') is-invalid @enderror" id="mailer_{{ $config->id }}" name="mailer" required onchange="updateMailerFields()">
                            @foreach ($mailers as $mailer)
                                <option value="{{ $mailer }}" {{ $config->mailer === $mailer ? 'selected' : '' }}>{{ strtoupper($mailer) }}</option>
                            @endforeach
                        </select>
                        @error('mailer')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    @if (in_array($config->mailer, ['smtp', 'sendmail']))
                    <div id="smtp-fields-edit">
                        <div class="mb-3">
                            <label for="host_{{ $config->id }}" class="form-label">Host</label>
                            <input type="text" class="form-control @error('host') is-invalid @enderror" id="host_{{ $config->id }}" name="host" value="{{ old('host', $config->host) }}">
                            @error('host')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="port_{{ $config->id }}" class="form-label">Port</label>
                            <input type="number" class="form-control @error('port') is-invalid @enderror" id="port_{{ $config->id }}" name="port" value="{{ old('port', $config->port) }}" min="1" max="65535">
                            @error('port')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="username_{{ $config->id }}" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username_{{ $config->id }}" name="username" value="{{ old('username', $config->username) }}">
                            @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_{{ $config->id }}" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password_{{ $config->id }}" name="password" placeholder="Leave empty to keep current password">
                            <small class="form-text text-muted">For Gmail: Use App Passwords, not your regular password</small>
                            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="encryption_{{ $config->id }}" class="form-label">Encryption</label>
                            <select class="form-select @error('encryption') is-invalid @enderror" id="encryption_{{ $config->id }}" name="encryption">
                                <option value="">None</option>
                                @foreach ($encryptions as $encryption)
                                    @if ($encryption)
                                        <option value="{{ $encryption }}" {{ $config->encryption === $encryption ? 'selected' : '' }}>{{ strtoupper($encryption) }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('encryption')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="from_address_{{ $config->id }}" class="form-label">From Address *</label>
                        <input type="email" class="form-control @error('from_address') is-invalid @enderror" id="from_address_{{ $config->id }}" name="from_address" value="{{ old('from_address', $config->from_address) }}" required>
                        @error('from_address')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="from_name_{{ $config->id }}" class="form-label">From Name *</label>
                        <input type="text" class="form-control @error('from_name') is-invalid @enderror" id="from_name_{{ $config->id }}" name="from_name" value="{{ old('from_name', $config->from_name) }}" required>
                        @error('from_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description_{{ $config->id }}" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description_{{ $config->id }}" name="description" rows="2">{{ old('description', $config->description) }}</textarea>
                        @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_enabled_{{ $config->id }}" name="is_enabled" {{ $config->is_enabled ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_enabled_{{ $config->id }}">
                            Enable this configuration
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('js')
<script>
function updateMailerFields() {
    const mailer = document.getElementById('mailer')?.value || document.getElementById('mailer_1')?.value;
    const smtpFields = document.getElementById('smtp-fields');
    
    if (['smtp', 'sendmail'].includes(mailer)) {
        if (smtpFields) smtpFields.style.display = 'block';
    } else {
        if (smtpFields) smtpFields.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateMailerFields);

// Toggle configs
document.querySelectorAll('.toggle-config').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const configId = this.dataset.configId;
        const route = this.dataset.route;
        
        fetch(route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Configuration toggled successfully');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.checked = !this.checked; // Revert on error
        });
    });
});
</script>
@endpush
@endsection

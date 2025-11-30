@extends('layouts.admin')

@section('title', 'Telegram Bot Configuration')
@section('breadcrumb', 'Settings > Telegram Bot')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-telegram"></i>
        Telegram Bot Configuration
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
                    <i class="bi bi-list"></i> Bot Configurations
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Bot Name</th>
                                <th>Bot Token</th>
                                <th>Webhook URL</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($configs as $config)
                                <tr>
                                    <td>
                                        <strong>{{ $config->bot_name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        <code class="text-muted small">{{ Str::limit($config->bot_token, 20, '...') }}</code>
                                    </td>
                                    <td>
                                        @if ($config->webhook_url)
                                            <small class="text-success">{{ Str::limit($config->webhook_url, 25, '...') }}</small>
                                        @else
                                            <small class="text-muted">Not configured</small>
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
                                                    data-route="{{ route('admin.settings.telegram-bot.toggle', $config->id) }}"
                                                    {{ $config->is_enabled ? 'checked' : '' }}
                                                >
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">Default (.env)</span>
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
                                                <form action="{{ route('admin.settings.telegram-bot.destroy', $config->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-info" disabled>
                                                    <i class="bi bi-info-circle"></i> From .env
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
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-telegram" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <h5 class="text-muted">No Telegram Bot Configuration</h5>
                    <p class="text-muted mb-3">Create your first Telegram bot configuration to get started.</p>
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
                    <i class="bi bi-telegram"></i> Add Telegram Bot Configuration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.telegram-bot.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bot_name" class="form-label">Bot Name</label>
                        <input type="text" class="form-control @error('bot_name') is-invalid @enderror" id="bot_name" name="bot_name" placeholder="e.g., Production Bot" required>
                        @error('bot_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="bot_token" class="form-label">Bot Token *</label>
                        <input type="password" class="form-control @error('bot_token') is-invalid @enderror" id="bot_token" name="bot_token" placeholder="Enter your Telegram bot token" required>
                        <small class="form-text text-muted">Get this from <a href="https://t.me/botfather" target="_blank">@BotFather</a> on Telegram</small>
                        @error('bot_token')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="webhook_url" class="form-label">Webhook URL</label>
                        <input type="url" class="form-control @error('webhook_url') is-invalid @enderror" id="webhook_url" name="webhook_url" placeholder="https://example.com/webhook/telegram">
                        <small class="form-text text-muted">Optional webhook URL for receiving updates from Telegram</small>
                        @error('webhook_url')<span class="invalid-feedback">{{ $message }}</span>@enderror
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
                    <i class="bi bi-telegram"></i> Edit Telegram Bot Configuration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.telegram-bot.update', $config->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bot_name_{{ $config->id }}" class="form-label">Bot Name</label>
                        <input type="text" class="form-control @error('bot_name') is-invalid @enderror" id="bot_name_{{ $config->id }}" name="bot_name" value="{{ old('bot_name', $config->bot_name) }}" required>
                        @error('bot_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="bot_token_{{ $config->id }}" class="form-label">Bot Token *</label>
                        <input type="password" class="form-control @error('bot_token') is-invalid @enderror" id="bot_token_{{ $config->id }}" name="bot_token" value="{{ old('bot_token', $config->bot_token) }}" required>
                        <small class="form-text text-muted">Get this from <a href="https://t.me/botfather" target="_blank">@BotFather</a> on Telegram</small>
                        @error('bot_token')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="webhook_url_{{ $config->id }}" class="form-label">Webhook URL</label>
                        <input type="url" class="form-control @error('webhook_url') is-invalid @enderror" id="webhook_url_{{ $config->id }}" name="webhook_url" value="{{ old('webhook_url', $config->webhook_url) }}">
                        <small class="form-text text-muted">Optional webhook URL for receiving updates from Telegram</small>
                        @error('webhook_url')<span class="invalid-feedback">{{ $message }}</span>@enderror
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

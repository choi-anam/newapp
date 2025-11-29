<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold">
                <i class="bi bi-shield-lock"></i> Kelola Permissions - {{ $user->name }}
            </h2>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- User Info Card -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <i class="bi bi-person-circle" style="font-size: 2rem; color: #667eea;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $user->name }}</h5>
                                <small class="text-muted">{{ $user->email }}</small><br>
                                <small class="text-muted">
                                    <i class="bi bi-tag"></i> Roles: 
                                    @forelse($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-muted">Tidak ada role</span>
                                    @endforelse
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copy Permissions Section -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-files"></i> Salin Permissions dari User Lain
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <select id="sourceUserSelect" class="form-select">
                                    <option value="">-- Pilih User --</option>
                                    @foreach(\App\Models\User::where('id', '!=', $user->id)->get() as $otherUser)
                                        <option value="{{ $otherUser->id }}">
                                            {{ $otherUser->name }} ({{ count($otherUser->getDirectPermissions()) }} permissions)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-info w-100" id="copyPermissionsBtn" disabled>
                                    <i class="bi bi-files"></i> Salin Permissions
                                </button>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle"></i> Salin semua direct permissions dari user lain ke user ini
                        </small>
                    </div>
                </div>
            </div>

            <!-- Available Permissions -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="bi bi-lock"></i> Available Permissions
                            </h6>
                            <span class="badge bg-secondary">{{ $allPermissions->count() }} Permissions</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="permissions-grid">
                            @forelse($allPermissions as $permission)
                                @php
                                    $hasDirectPermission = in_array($permission->id, $userPermissions);
                                    $hasRolePermission = in_array($permission->id, $rolePermissions);
                                    $isDisabled = $hasRolePermission && !$hasDirectPermission;
                                @endphp
                                <div class="permission-card">
                                    <div class="d-flex gap-2">
                                        <input 
                                            type="checkbox" 
                                            class="form-check-input permission-checkbox"
                                            id="perm_{{ $permission->id }}"
                                            data-permission="{{ $permission->name }}"
                                            data-permission-id="{{ $permission->id }}"
                                            {{ $hasDirectPermission ? 'checked' : '' }}
                                            {{ $isDisabled ? 'disabled' : '' }}
                                        >
                                        <div class="flex-grow-1">
                                            <label for="perm_{{ $permission->id }}" class="form-check-label fw-semibold cursor-pointer">
                                                {{ str_replace('-', ' ', ucfirst($permission->name)) }}
                                            </label>
                                            <small class="d-block text-muted">
                                                @if($hasRolePermission && !$hasDirectPermission)
                                                    <i class="bi bi-info-circle"></i> Dari role
                                                @elseif($hasDirectPermission)
                                                    <i class="bi bi-check-circle"></i> Direct permission
                                                @else
                                                    <i class="bi bi-lock-fill"></i> Tidak dimiliki
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> Tidak ada permission yang tersedia
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .permission-card {
            border: 1px solid #e0e0e0;
            border-radius: 0.5rem;
            padding: 1rem;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .permission-card:hover {
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        }

        .permission-card input:checked ~ label {
            color: #667eea;
        }

        .permission-card input:disabled ~ label {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .form-check-input {
            cursor: pointer;
            width: 1.25em;
            height: 1.25em;
            accent-color: #667eea;
        }

        @media (prefers-color-scheme: dark) {
            .permission-card {
                background-color: #2d2d2d;
                border-color: #444;
            }

            .card {
                background-color: #2d2d2d;
                border-color: #444;
            }

            .card-header {
                background-color: #1a1a1a !important;
                border-bottom-color: #444;
            }

            .form-select {
                background-color: #1a1a1a;
                color: #e0e0e0;
                border-color: #444;
            }

            .form-select:focus {
                border-color: #667eea;
                background-color: #1a1a1a;
                color: #e0e0e0;
            }

            .text-muted {
                color: #999 !important;
            }

            h6 {
                color: #e0e0e0;
            }
        }
    </style>

    <script>
        // Handle copy permissions button
        document.getElementById('sourceUserSelect').addEventListener('change', function() {
            document.getElementById('copyPermissionsBtn').disabled = !this.value;
        });

        document.getElementById('copyPermissionsBtn').addEventListener('click', function() {
            const sourceUserId = document.getElementById('sourceUserSelect').value;
            
            if (!sourceUserId) {
                alert('Pilih user terlebih dahulu');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`{{ route('admin.users.permissions.copy', $user) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    source_user_id: sourceUserId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        });

        // Handle permission checkbox changes
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const permission = this.dataset.permission;
                const isChecked = this.checked;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                const url = isChecked 
                    ? `{{ route('admin.users.permissions.give', $user) }}`
                    : `{{ route('admin.users.permissions.revoke', $user) }}`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        permission: permission
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        // Revert checkbox if failed
                        this.checked = !isChecked;
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !isChecked;
                    alert('Terjadi kesalahan');
                });
            });
        });
    </script>
</x-app-layout>

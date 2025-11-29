<x-guest-layout>
    <div class="auth-header-new">
        <div class="header-icon">
            <i class="bi bi-key"></i>
        </div>
        <h2 class="fw-bold mb-2">Reset Password 🔑</h2>
        <p class="text-muted">Masukkan password baru untuk akun Anda</p>
    </div>

    <div class="card-body p-5">
        <!-- Session Status -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input 
                        id="email" 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        name="email" 
                        value="{{ old('email', $request->email) }}" 
                        placeholder="your-email@example.com"
                        required 
                        autocomplete="username"
                    />
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted d-block mt-2">
                    <i class="bi bi-info-circle"></i> Email yang terdaftar pada akun Anda
                </small>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password Baru</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input 
                        id="password" 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        name="password" 
                        placeholder="Minimal 8 karakter"
                        required 
                        autocomplete="new-password"
                    />
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted d-block mt-2">
                    <i class="bi bi-info-circle"></i> Gunakan kombinasi huruf, angka, dan simbol
                </small>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        class="form-control @error('password_confirmation') is-invalid @enderror" 
                        name="password_confirmation" 
                        placeholder="Ulangi password baru"
                        required 
                        autocomplete="new-password"
                    />
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary d-grid w-100 mb-3 py-2 fw-semibold">
                <i class="bi bi-check-lg"></i> Reset Password
            </button>
        </form>
    </div>

    <div class="auth-footer">
        <p><a href="{{ route('login') }}" class="auth-link"><i class="bi bi-arrow-left"></i> Kembali ke Login</a></p>
    </div>

    <style>
        .auth-header-new {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            display: block;
            opacity: 0.95;
        }

        .auth-header-new h2 {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }

        .auth-header-new p {
            font-size: 0.95rem;
            max-width: 500px;
            margin: 0 auto;
            opacity: 0.95;
            line-height: 1.6;
        }

        .input-group-merge .input-group-text {
            background-color: #f5f5f9;
            border: 1px solid #e0e0e0;
            border-right: none;
            color: #667eea;
        }

        .input-group-merge .form-control {
            border-left: none;
            border: 1px solid #e0e0e0;
            padding: 0.75rem 1rem;
        }

        .input-group-merge .form-control:focus {
            border-color: #667eea;
            box-shadow: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .auth-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .form-label {
            color: #495057;
            margin-bottom: 0.75rem;
        }

        @media (prefers-color-scheme: dark) {
            .input-group-merge .input-group-text {
                background-color: #1a1a1a;
                border-color: #444;
                color: #667eea;
            }

            .input-group-merge .form-control {
                background-color: #1a1a1a;
                border-color: #444;
                color: #e0e0e0;
            }

            .input-group-merge .form-control:focus {
                border-color: #667eea;
                background-color: #1a1a1a;
            }

            .form-label {
                color: #ccc;
            }
        }
    </style>
</x-guest-layout>

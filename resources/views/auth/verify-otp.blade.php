<x-guest-layout>
    <div class="auth-header-new">
        <div class="header-icon">
            <i class="bi bi-shield-check"></i>
        </div>
        <h2 class="fw-bold mb-2">Verifikasi OTP ✓</h2>
        <p class="text-muted">Masukkan kode OTP yang kami kirimkan ke email Anda</p>
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

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Email & Channel Info -->
        <div class="info-card mb-4">
            <div class="info-row">
                <span class="info-label"><i class="bi bi-envelope"></i> Email:</span>
                <span class="info-value">{{ $email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="bi bi-chat-dots"></i> Metode:</span>
                <span class="info-value"><span class="badge bg-info text-dark">{{ ucfirst($channel) }}</span></span>
            </div>
        </div>

        <form method="POST" action="{{ route('password.otp.store') }}">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="channel" value="{{ $channel }}">

            <!-- OTP Input -->
            <div class="mb-4">
                <label for="otp" class="form-label fw-semibold">Kode OTP (6 digit)</label>
                <input 
                    type="text"
                    id="otp"
                    class="form-control form-control-lg otp-input @error('otp') is-invalid @enderror"
                    name="otp"
                    value="{{ old('otp') }}"
                    placeholder="000000"
                    maxlength="6"
                    inputmode="numeric"
                    required
                    autofocus
                />
                @error('otp')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted d-block mt-2">
                    <i class="bi bi-hourglass-split"></i> Kode berlaku selama 15 menit
                </small>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password Baru</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input 
                        type="password"
                        id="password"
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
                        type="password"
                        id="password_confirmation"
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

            <div class="text-center">
                <button 
                    type="button"
                    class="btn btn-link btn-sm text-decoration-none"
                    data-bs-toggle="modal"
                    data-bs-target="#resendModal"
                >
                    <i class="bi bi-arrow-repeat"></i> Tidak terima kode? Minta ulang
                </button>
            </div>
        </form>
    </div>

    <!-- Resend Modal -->
    <div class="modal fade" id="resendModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">
                        <i class="bi bi-arrow-repeat"></i> Pengiriman Ulang OTP
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('password.otp.resend') }}">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-3">Kode OTP baru akan dikirim ke:</p>
                        <div class="alert alert-light border">
                            <div><strong><i class="bi bi-envelope"></i> Email:</strong> {{ $email }}</div>
                            <div><strong><i class="bi bi-chat-dots"></i> Via:</strong> {{ ucfirst($channel) }}</div>
                        </div>
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="channel" value="{{ $channel }}">
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Kirim Ulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="auth-footer">
        <p>Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar sekarang</a></p>
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

        .info-card {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #666;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .info-value {
            color: #333;
            font-weight: 600;
            font-size: 0.9rem;
            word-break: break-all;
        }

        .otp-input {
            font-size: 2rem;
            letter-spacing: 12px;
            text-align: center;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            padding: 1rem;
        }

        .otp-input::placeholder {
            letter-spacing: 2px;
        }

        .otp-input:focus {
            letter-spacing: 12px;
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

        .btn-link {
            color: #667eea;
            font-weight: 500;
        }

        .btn-link:hover {
            color: #764ba2;
        }

        .badge {
            padding: 0.35rem 0.65rem;
            font-size: 0.85rem;
        }

        @media (prefers-color-scheme: dark) {
            .info-card {
                background-color: #2d2d2d;
                border-color: #444;
            }

            .info-label {
                color: #999;
            }

            .info-value {
                color: #e0e0e0;
            }

            .info-row {
                border-bottom-color: #444;
            }

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

            .otp-input {
                background-color: #1a1a1a;
                border-color: #444;
                color: #e0e0e0;
            }

            .form-label {
                color: #ccc;
            }
        }
    </style>

    <script>
        // Auto-format OTP input - only numbers
        document.getElementById('otp').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });

        // Move focus to password when 6 digits entered
        document.getElementById('otp').addEventListener('input', function() {
            if (this.value.length === 6) {
                document.getElementById('password').focus();
            }
        });
    </script>
</x-guest-layout>

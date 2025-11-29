<x-guest-layout>
    <div class="auth-header">
        <h1><i class="bi bi-key"></i></h1>
        <h1>Lupa Password?</h1>
        <p>Masukkan email Anda untuk memulai proses reset password</p>
    </div>

    <div class="card-body p-4">
        <!-- Session Status -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> Terjadi kesalahan
                @foreach ($errors->all() as $error)
                    <br><small>{{ $error }}</small>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Info Box -->
        <div class="alert alert-info mb-4" style="border-left: 4px solid #0d6efd;">
            <i class="bi bi-info-circle"></i>
            <small><strong>Pilih metode reset password</strong> yang paling nyaman untuk Anda:</small>
        </div>

        <form method="POST" action="{{ route('password.forgot.store') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope"></i></span>
                    <input 
                        id="email" 
                        class="form-control border-start-0 @error('email') is-invalid @enderror" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        placeholder="nama@example.com"
                        autocomplete="email"
                    />
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted d-block mt-2">
                    <i class="bi bi-info-circle"></i> Kami akan mengirimkan opsi reset ke email ini
                </small>
            </div>

            <!-- Reset Methods Info -->
            <div class="row g-2 mb-4">
                <div class="col-6">
                    <div class="reset-method-card">
                        <i class="bi bi-lightning-charge"></i>
                        <div class="method-title">OTP</div>
                        <small>Cepat & Aman</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="reset-method-card">
                        <i class="bi bi-link-45deg"></i>
                        <div class="method-title">Link Email</div>
                        <small>Tradisional</small>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3 py-2 fw-semibold">
                <i class="bi bi-arrow-right"></i> Lanjutkan ke Pilihan Metode
            </button>

            <!-- Alternative Methods -->
            <hr class="my-3">
            
            <div class="d-grid gap-2">
                <small class="text-center text-muted">Atau pilih metode langsung:</small>
                
                <a href="{{ route('password.request') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-envelope-at"></i> Email Password Reset Link
                </a>
            </div>

            <div class="d-flex align-items-center justify-content-center gap-2 mt-4">
                <a href="{{ route('login') }}" class="btn btn-link btn-sm text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Kembali ke login
                </a>
            </div>
        </form>
    </div>

    <div class="auth-footer">
        <p>Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar sekarang</a></p>
    </div>

    <style>
        .reset-method-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .reset-method-card:hover {
            transform: translateY(-2px);
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .reset-method-card i {
            font-size: 28px;
            margin-bottom: 8px;
            display: block;
        }

        .method-title {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .reset-method-card small {
            font-size: 12px;
            opacity: 0.8;
        }

        .input-group-text {
            color: #667eea;
            border: 1px solid #e0e0e0 !important;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .btn-outline-secondary:hover {
            background-color: #667eea;
            border-color: #667eea;
            color: white;
        }

        hr {
            border-top: 1px dashed #e0e0e0;
        }
    </style>
</x-guest-layout>

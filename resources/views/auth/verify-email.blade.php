<x-guest-layout>
    <div class="auth-header">
        <h1><i class="bi bi-envelope-check"></i></h1>
        <h2>Verifikasi Email Anda</h2>
        <p>Silakan verifikasi email untuk melanjutkan</p>
    </div>

    <div class="card-body p-5">
        <!-- Status Message -->
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> Email verifikasi baru telah dikirim ke email Anda
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Verification Info -->
        <div class="verification-container">
            <div class="verification-icon">
                <i class="bi bi-envelope-exclamation"></i>
            </div>
            
            <h3 class="fw-bold mb-3">Email Verifikasi Dikirim</h3>
            
            <p class="text-muted mb-4">
                Terima kasih telah mendaftar! Kami telah mengirimkan email verifikasi ke email Anda.
            </p>

            <div class="alert alert-info border-start border-4 mb-4">
                <i class="bi bi-info-circle"></i>
                <strong>Langkah selanjutnya:</strong><br>
                <small>
                    1. Buka email Anda<br>
                    2. Cari email dari {{ config('app.name') }}<br>
                    3. Klik tombol "Verifikasi Email"<br>
                    4. Anda akan diarahkan untuk login
                </small>
            </div>

            <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                @csrf
                <button type="submit" class="btn btn-primary d-grid w-100 py-2 fw-semibold">
                    <i class="bi bi-arrow-clockwise"></i> Kirim Ulang Email Verifikasi
                </button>
            </form>

            <hr>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link btn-sm text-decoration-none">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="auth-footer">
        <p>
            <a href="{{ route('register') }}" class="auth-link"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
        </p>
    </div>

    <style>
        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .auth-header h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            opacity: 0.95;
        }

        .auth-header h2 {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            font-weight: 700;
        }

        .auth-header p {
            font-size: 0.95rem;
            opacity: 0.95;
            margin: 0;
        }

        .verification-container {
            text-align: center;
        }

        .verification-icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-link {
            color: #667eea;
            text-decoration: none;
        }

        .btn-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .alert-info {
            background-color: #e7f3ff;
            border-color: #b3d9ff;
            color: #0c5aa0;
        }

        .alert-info strong {
            color: #0c5aa0;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .auth-link {
            color: #667eea;
            text-decoration: none;
        }

        .auth-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        @media (prefers-color-scheme: dark) {
            .verification-icon {
                color: #667eea;
            }

            .alert-info {
                background-color: #1a3a52;
                border-color: #334455;
                color: #86b3d5;
            }

            .alert-info strong {
                color: #86b3d5;
            }

            .text-muted {
                color: #999 !important;
            }

            h3 {
                color: #e0e0e0;
            }
        }
    </style>
</x-guest-layout>

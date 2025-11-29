<x-guest-layout>
    <div class="auth-header-new">
        <div class="header-icon">
            <i class="bi bi-question-circle"></i>
        </div>
        <h2 class="fw-bold mb-2">Pilih Metode Reset 🔐</h2>
        <p class="text-muted">Pilih cara Anda ingin mereset password</p>
    </div>

    <div class="card-body" style="padding: 2rem;">
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

        <!-- Method Selection Grid -->
        <div class="method-grid">
            <!-- Email Method -->
            <div>
                <a href="{{ route('password.request') }}" class="method-card email-method h-100">
                    <div class="method-content">
                        <div class="method-icon">
                            <i class="bi bi-envelope-check"></i>
                        </div>
                        <h4 class="method-title">Email Link</h4>
                        <p class="method-description">
                            Dapatkan link reset password melalui email Anda
                        </p>
                        <div class="method-benefits">
                            <small>
                                <i class="bi bi-check-circle text-success"></i> Aman & terpercaya<br>
                                <i class="bi bi-clock text-success"></i> Berlaku 60 menit<br>
                                <i class="bi bi-device-type text-success"></i> Akses dari perangkat mana pun
                            </small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- OTP Method -->
            <div>
                <a href="{{ route('password.forgot') }}" class="method-card otp-method">
                    <div class="method-content">
                        <div class="method-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4 class="method-title">Kode OTP</h4>
                        <p class="method-description">
                            Terima kode OTP via Email, Telegram, atau WhatsApp
                        </p>
                        <div class="method-benefits">
                            <small>
                                <i class="bi bi-check-circle text-success"></i> Cepat & instan<br>
                                <i class="bi bi-hourglass-split text-success"></i> Berlaku 15 menit<br>
                                <i class="bi bi-chat-dots text-success"></i> Pilih saluran pengiriman
                            </small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Info Box -->
        <div class="alert alert-info mt-4 border-start border-4" role="alert">
            <i class="bi bi-info-circle-fill"></i>
            <strong>Tidak tahu pilihan mana?</strong><br>
            <small>
                <strong>Email Link:</strong> Lebih aman, cocok untuk reset password dari desktop<br>
                <strong>Kode OTP:</strong> Lebih cepat, bisa pakai Telegram/WhatsApp untuk instant notification
            </small>
        </div>
    </div>

    <div class="auth-footer">
        <p><a href="{{ route('login') }}" class="auth-link"><i class="bi bi-arrow-left"></i> Kembali ke Login</a></p>
    </div>

    <style>
        /* Override guest layout for password-method */
        .auth-container {
            max-width: 900px !important;
            padding: 1rem;
        }

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

        /* Grid layout for method cards */
        .method-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .method-card {
            display: block;
            padding: 2rem;
            border: 2px solid #e0e0e0;
            border-radius: 1rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            background-color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .method-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .method-card:hover::before {
            transform: scaleX(1);
        }

        .method-card:hover {
            border-color: #667eea;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
            transform: translateY(-4px);
        }

        .method-content {
            text-align: center;
        }

        .method-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #667eea;
        }

        .method-card.otp-method .method-icon {
            color: #764ba2;
        }

        .method-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #333;
        }

        .method-description {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .method-benefits {
            text-align: left;
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            line-height: 1.8;
        }

        .method-card:hover .method-benefits {
            background-color: #f0f3ff;
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

        .alert-info {
            background-color: #e7f3ff;
            border-color: #b3d9ff;
            color: #0c5aa0;
        }

        .alert-info strong {
            color: #0c5aa0;
        }

        @media (prefers-color-scheme: dark) {
            .method-card {
                background-color: #1a1a1a;
                border-color: #444;
            }

            .method-title {
                color: #e0e0e0;
            }

            .method-description {
                color: #999;
            }

            .method-benefits {
                background-color: #2d2d2d;
                color: #ccc;
            }

            .method-card:hover .method-benefits {
                background-color: #334455;
            }

            .method-card:hover {
                border-color: #667eea;
            }

            .alert-info {
                background-color: #1a2f4d;
                border-color: #334455;
                color: #99ccff;
            }

            .alert-info strong {
                color: #ccddff;
            }
        }

        @media (max-width: 768px) {
            .auth-container {
                max-width: 100% !important;
            }

            .method-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .method-card {
                padding: 1.5rem;
            }

            .method-icon {
                font-size: 2.5rem;
            }

            .method-title {
                font-size: 1.1rem;
            }
        }
    </style>
</x-guest-layout>

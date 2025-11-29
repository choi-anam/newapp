<x-guest-layout>
    <div class="auth-header-new">
        <div class="header-icon">
            <i class="bi bi-chat-dots"></i>
        </div>
        <h2 class="fw-bold mb-2">Pilih Metode Reset 📱</h2>
        <p class="text-muted">Pilih salah satu cara yang paling nyaman untuk Anda menerima kode OTP</p>
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
        </div>

        <form method="POST" action="{{ route('password.send-otp') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- Channel Selection -->
            <div class="channel-grid mb-4">
                @foreach($channels as $channelKey => $channel)
                    @if($channel['available'])
                        <label class="channel-card active-option">
                            <input 
                                type="radio" 
                                name="channel" 
                                value="{{ $channelKey }}" 
                                class="channel-radio"
                                {{ $loop->first ? 'checked' : '' }}
                            >
                            <div class="channel-check">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="channel-body">
                                <div class="channel-icon">{{ $channel['icon'] }}</div>
                                <div class="channel-title">{{ $channel['name'] }}</div>
                                <div class="channel-desc">{{ $channel['description'] }}</div>
                            </div>
                        </label>
                    @else
                        <div class="channel-card disabled-option">
                            <div class="channel-body">
                                <div class="channel-icon opacity-50">{{ $channel['icon'] }}</div>
                                <div class="channel-title opacity-50">{{ $channel['name'] }}</div>
                                <div class="channel-desc opacity-50">{{ $channel['description'] }}</div>
                            </div>
                            <span class="badge-disabled">Tidak tersedia</span>
                        </div>
                    @endif
                @endforeach
            </div>

            @error('channel')
                <div class="alert alert-danger alert-sm mb-3">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                </div>
            @enderror

            <button type="submit" class="btn btn-primary d-grid w-100 mb-3 py-2 fw-semibold">
                <i class="bi bi-send"></i> Kirim OTP
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center gap-2 text-decoration-none mt-3">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke login</span>
                </a>
            </div>
        </form>

        <!-- Info Box -->
        <div class="info-box mt-4">
            <i class="bi bi-info-circle"></i>
            <span><strong>Catatan:</strong> Kode OTP berlaku selama 15 menit. Jika tidak menerima, Anda bisa meminta pengiriman ulang.</span>
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

        .channel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
        }

        .channel-card {
            position: relative;
            padding: 1.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 0.75rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .channel-check {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #667eea;
            color: white;
            border-radius: 50%;
            font-size: 14px;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.3s ease;
        }

        .channel-card input:checked ~ .channel-check {
            opacity: 1;
            transform: scale(1);
        }

        .channel-card.active-option {
            cursor: pointer;
        }

        .channel-card.active-option:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.15);
        }

        .channel-card input:checked ~ .channel-body {
            color: #667eea;
        }

        .channel-card input:checked ~ .channel-body .channel-icon {
            transform: scale(1.15);
        }

        .channel-card input:checked {
            accent-color: #667eea;
        }

        .channel-card input:checked {
            border-color: #667eea !important;
            background-color: #f0f3ff !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
        }

        .channel-body {
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .channel-radio {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .channel-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            display: block;
            transition: all 0.3s ease;
        }

        .channel-title {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
            color: #333;
        }

        .channel-desc {
            font-size: 0.8rem;
            color: #666;
            line-height: 1.3;
        }

        .channel-card.disabled-option {
            opacity: 0.6;
            background-color: #f8f9fa;
            cursor: not-allowed;
            border-color: #e0e0e0;
            position: relative;
        }

        .badge-disabled {
            position: absolute;
            top: 8px;
            right: 8px;
            background-color: #e9ecef;
            color: #6c757d;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
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

        .info-box {
            background-color: #cfe2ff;
            border-left: 3px solid #0d6efd;
            padding: 1rem;
            border-radius: 0.5rem;
            color: #084298;
            font-size: 0.9rem;
        }

        .info-box i {
            margin-right: 0.5rem;
        }

        .alert-sm {
            padding: 0.75rem;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .opacity-50 {
            opacity: 0.5;
        }

        @media (prefers-color-scheme: dark) {
            .channel-card {
                background-color: #2d2d2d;
                border-color: #444;
            }

            .channel-card.active-option:hover {
                background-color: #667eea15;
                border-color: #667eea;
            }

            .channel-card input:checked {
                border-color: #667eea !important;
                background-color: #334455 !important;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2) !important;
            }

            .channel-title {
                color: #e0e0e0;
            }

            .channel-desc {
                color: #999;
            }

            .channel-card.disabled-option {
                background-color: #1a1a1a;
                border-color: #444;
            }

            .badge-disabled {
                background-color: #444;
                color: #999;
            }

            .info-box {
                background-color: #1a3a52;
                border-left-color: #0d6efd;
                color: #86b3d5;
            }

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
        }
    </style>
</x-guest-layout>

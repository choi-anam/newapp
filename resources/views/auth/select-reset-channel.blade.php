<x-guest-layout>
    <div class="auth-header">
        <h1><i class="bi bi-chat-dots"></i></h1>
        <h1>Pilih Metode</h1>
        <p>Pilih salah satu cara untuk menerima kode OTP</p>
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

        <form method="POST" action="{{ route('password.send-otp') }}" class="space-y-3">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">

            <div class="channel-options">
                @foreach($channels as $channelKey => $channel)
                    @if($channel['available'])
                        <label class="channel-option active">
                            <input 
                                type="radio" 
                                name="channel" 
                                value="{{ $channelKey }}" 
                                class="channel-radio"
                                {{ $loop->first ? 'checked' : '' }}
                            >
                            <div class="channel-content">
                                <div class="channel-icon">{{ $channel['icon'] }}</div>
                                <div class="channel-info">
                                    <div class="channel-name">{{ $channel['name'] }}</div>
                                    <div class="channel-desc">{{ $channel['description'] }}</div>
                                </div>
                            </div>
                        </label>
                    @else
                        <div class="channel-option disabled">
                            <div class="channel-content">
                                <div class="channel-icon">{{ $channel['icon'] }}</div>
                                <div class="channel-info">
                                    <div class="channel-name">{{ $channel['name'] }}</div>
                                    <div class="channel-desc">{{ $channel['description'] }}</div>
                                </div>
                            </div>
                            <span class="badge bg-secondary">Tidak tersedia</span>
                        </div>
                    @endif
                @endforeach
            </div>

            @error('channel')
                <div class="alert alert-danger alert-sm">
                    <i class="bi bi-exclamation-triangle"></i> {{ $message }}
                </div>
            @enderror

            <div class="d-flex align-items-center justify-content-between mt-4">
                <a href="{{ route('login') }}" class="btn btn-link btn-sm text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Kembali ke login
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i> Kirim OTP
                </button>
            </div>
        </form>

        <div class="alert alert-info mt-4 py-2">
            <i class="bi bi-info-circle"></i>
            <small>Kode OTP akan berlaku selama 15 menit. Jika tidak menerima, Anda bisa meminta pengiriman ulang.</small>
        </div>
    </div>

    <div class="auth-footer">
        <p>Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar sekarang</a></p>
    </div>

    <style>
        .channel-options {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .channel-option {
            display: flex;
            align-items: center;
            padding: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .channel-option.active:hover {
            border-color: #0d6efd;
            background-color: #f0f7ff;
        }

        .channel-option.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background-color: #f5f5f5;
        }

        .channel-radio {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            cursor: pointer;
            accent-color: #0d6efd;
        }

        .channel-content {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .channel-icon {
            font-size: 28px;
            margin-right: 12px;
            min-width: 40px;
            text-align: center;
        }

        .channel-info {
            flex: 1;
        }

        .channel-name {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 4px;
        }

        .channel-desc {
            font-size: 13px;
            color: #666;
        }

        .channel-option.active input:checked + .channel-content .channel-name {
            color: #0d6efd;
        }

        .alert-sm {
            padding: 8px 12px;
            font-size: 13px;
            margin-bottom: 0;
        }

        @media (prefers-color-scheme: dark) {
            .channel-option {
                border-color: #444;
                background-color: #2d2d2d;
                color: #e0e0e0;
            }

            .channel-option.active:hover {
                border-color: #0d6efd;
                background-color: #0d6efd20;
            }

            .channel-option.disabled {
                background-color: #1a1a1a;
            }

            .channel-name {
                color: #e0e0e0;
            }

            .channel-desc {
                color: #999;
            }
        }
    </style>
</x-guest-layout>

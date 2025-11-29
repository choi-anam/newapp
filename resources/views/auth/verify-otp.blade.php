<x-guest-layout>
    <div class="auth-header">
        <h1><i class="bi bi-shield-check"></i></h1>
        <h1>Verifikasi OTP</h1>
        <p>Masukkan kode OTP yang kami kirimkan</p>
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

        <div class="alert alert-info mb-4">
            <small>
                <i class="bi bi-info-circle"></i>
                Kami telah mengirimkan kode OTP ke <strong>{{ $email }}</strong><br>
                melalui <strong class="text-uppercase">{{ $channel }}</strong>
            </small>
        </div>

        <form method="POST" action="{{ route('password.otp.store') }}">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="channel" value="{{ $channel }}">

            <!-- OTP -->
            <div class="mb-4">
                <label for="otp" class="form-label">Kode OTP (6 digit)</label>
                <input 
                    id="otp" 
                    class="form-control form-control-lg @error('otp') is-invalid @enderror text-center fw-bold" 
                    type="text" 
                    name="otp" 
                    value="{{ old('otp') }}" 
                    required 
                    autofocus 
                    maxlength="6"
                    inputmode="numeric"
                    placeholder="000000"
                    style="letter-spacing: 8px; font-size: 28px;"
                />
                @error('otp')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted d-block mt-2">
                    <i class="bi bi-hourglass"></i> Kode berlaku selama 15 menit
                </small>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input 
                    id="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                />
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted d-block mt-2">
                    <i class="bi bi-shield-lock"></i> Password harus kuat (huruf, angka, simbol)
                </small>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input 
                    id="password_confirmation" 
                    class="form-control @error('password_confirmation') is-invalid @enderror" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    placeholder="Ulangi password baru"
                />
                @error('password_confirmation')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex align-items-center justify-content-between mt-4">
                <button 
                    type="button" 
                    class="btn btn-link btn-sm text-decoration-none"
                    data-bs-toggle="modal"
                    data-bs-target="#resendModal"
                >
                    <i class="bi bi-arrow-repeat"></i> Minta ulang OTP
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Reset Password
                </button>
            </div>
        </form>
    </div>

    <!-- Resend Modal -->
    <div class="modal fade" id="resendModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-arrow-repeat"></i> Pengiriman Ulang OTP
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('password.otp.resend') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="channel" value="{{ $channel }}">
                        <p>Kode OTP baru akan dikirim ke <strong>{{ $email }}</strong> via <strong class="text-uppercase">{{ $channel }}</strong>.</p>
                        <p class="text-muted small">Pastikan Anda menerima sebelum menutup jendela ini.</p>
                    </div>
                    <div class="modal-footer">
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

    <script>
        // Auto-format OTP input
        document.getElementById('otp').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });

        // Auto-submit jika sudah 6 digit
        document.getElementById('otp').addEventListener('input', function() {
            if (this.value.length === 6) {
                // Optional: uncomment untuk auto-focus ke password field
                // document.getElementById('password').focus();
            }
        });
    </script>
</x-guest-layout>

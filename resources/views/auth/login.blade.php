<x-guest-layout>
    <div class="auth-header">
        <h1><i class="bi bi-box-arrow-in-right"></i></h1>
        <h1>Masuk</h1>
        <p>Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <div class="card-body p-4">
        <!-- Session Status -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> Gagal masuk
                @foreach ($errors->all() as $error)
                    <br><small>{{ $error }}</small>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" class="form-control @error('email') is-invalid @enderror" 
                       type="email" name="email" value="{{ old('email') }}" 
                       required autofocus autocomplete="username" placeholder="nama@contoh.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" class="form-control @error('password') is-invalid @enderror"
                       type="password" name="password" required 
                       autocomplete="current-password" placeholder="••••••••">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-check mb-3">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label class="form-check-label" for="remember_me">
                    Ingat saya
                </label>
            </div>

            <!-- Button -->
            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
            </button>

            @if (Route::has('password.request'))
                <div class="text-center mb-3">
                    <a href="{{ route('password.request') }}" class="auth-link small">
                        Lupa password?
                    </a>
                </div>
            @endif
        </form>
    </div>

    @if (Route::has('register'))
        <div class="auth-footer">
            <p>Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar sekarang</a></p>
        </div>
    @endif
</x-guest-layout>

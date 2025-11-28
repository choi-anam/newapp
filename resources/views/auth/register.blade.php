<x-guest-layout>
    <div class="auth-header">
        <h1><i class="bi bi-person-plus"></i></h1>
        <h1>Daftar</h1>
        <p>Buat akun baru untuk memulai</p>
    </div>

    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> Gagal mendaftar
                @foreach ($errors->all() as $error)
                    <br><small>{{ $error }}</small>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input id="name" class="form-control @error('name') is-invalid @enderror" 
                       type="text" name="name" value="{{ old('name') }}" 
                       required autofocus autocomplete="name" placeholder="Nama Anda">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label">Username <small class="text-muted">(Opsional)</small></label>
                <input id="username" class="form-control @error('username') is-invalid @enderror" 
                       type="text" name="username" value="{{ old('username') }}" 
                       autocomplete="username" placeholder="username_anda">
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- UID -->
            <div class="mb-3">
                <label for="uid" class="form-label">UID <small class="text-muted">(Opsional)</small></label>
                <input id="uid" class="form-control @error('uid') is-invalid @enderror" 
                       type="text" name="uid" value="{{ old('uid') }}" 
                       autocomplete="off" placeholder="ID unik Anda">
                @error('uid')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" class="form-control @error('email') is-invalid @enderror" 
                       type="email" name="email" value="{{ old('email') }}" 
                       required autocomplete="email" placeholder="nama@contoh.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" class="form-control @error('password') is-invalid @enderror"
                       type="password" name="password" required 
                       autocomplete="new-password" placeholder="••••••••">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted-sm">Minimal 8 karakter</small>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                       type="password" name="password_confirmation" required 
                       autocomplete="new-password" placeholder="••••••••">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Button -->
            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-person-plus"></i> Daftar
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="auth-link small">
                    Sudah punya akun? Masuk sekarang
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>

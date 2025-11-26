<form method="post" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf
    @method('patch')

    <div class="mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" 
               value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" placeholder="Nama Anda">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" 
               value="{{ old('email', $user->email) }}" required autocomplete="username" placeholder="email@contoh.com">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert alert-info mt-2 mb-0">
                <p class="mb-2">
                    <i class="bi bi-info-circle"></i> Email Anda belum terverifikasi.
                </p>
                <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link p-0">
                        Kirim ulang email verifikasi
                    </button>
                </form>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 mb-0 text-success small">
                        <i class="bi bi-check-circle"></i> Email verifikasi telah dikirim ke alamat email Anda.
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Simpan Perubahan
        </button>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                <i class="bi bi-check-circle me-2"></i> Profil berhasil diperbarui
            </div>
        @endif
    </div>
</form>

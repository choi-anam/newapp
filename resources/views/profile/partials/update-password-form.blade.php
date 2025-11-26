<form method="post" action="{{ route('password.update') }}" class="space-y-4">
    @csrf
    @method('put')

    <p class="text-muted-sm mb-3">
        <i class="bi bi-info-circle"></i> Pastikan akun Anda menggunakan password yang kuat dan acak untuk tetap aman.
    </p>

    <div class="mb-3">
        <label for="update_password_current_password" class="form-label">Password Saat Ini</label>
        <input id="update_password_current_password" name="current_password" type="password" class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif" 
               autocomplete="current-password" placeholder="Masukkan password saat ini">
        @if($errors->updatePassword->has('current_password'))
            <div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>
        @endif
    </div>

    <div class="mb-3">
        <label for="update_password_password" class="form-label">Password Baru</label>
        <input id="update_password_password" name="password" type="password" class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif" 
               autocomplete="new-password" placeholder="Masukkan password baru">
        @if($errors->updatePassword->has('password'))
            <div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>
        @endif
        <small class="text-muted-sm">Minimal 8 karakter</small>
    </div>

    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label">Konfirmasi Password</label>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif" 
               autocomplete="new-password" placeholder="Konfirmasi password baru">
        @if($errors->updatePassword->has('password_confirmation'))
            <div class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</div>
        @endif
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Ubah Password
        </button>

        @if (session('status') === 'password-updated')
            <div class="alert alert-success mb-0 d-inline-flex align-items-center">
                <i class="bi bi-check-circle me-2"></i> Password berhasil diperbarui
            </div>
        @endif
    </div>
</form>

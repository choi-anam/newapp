<div>
    <p class="text-muted-sm mb-3">
        <i class="bi bi-exclamation-triangle"></i>
        Setelah akun Anda dihapus, semua data akan dihapus secara permanen. Pastikan Anda telah mendownload data penting terlebih dahulu.
    </p>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
        <i class="bi bi-trash"></i> Hapus Akun
    </button>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="confirmUserDeletionLabel">
                            <i class="bi bi-exclamation-triangle"></i> Hapus Akun
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-2">
                            <strong>Apakah Anda yakin ingin menghapus akun Anda?</strong>
                        </p>
                        <p class="text-muted-sm small mb-3">
                            Tindakan ini tidak dapat dibatalkan. Semua data akun Anda akan dihapus secara permanen.
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label">Masukkan Password Anda untuk Konfirmasi</label>
                            <input id="password" name="password" type="password" 
                                   class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif" 
                                   placeholder="••••••••">
                            @if($errors->userDeletion->has('password'))
                                <div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Ya, Hapus Akun Saya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

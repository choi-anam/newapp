<x-app-layout>
    <x-slot name="header">
        <h1>
            <i class="bi bi-person-circle"></i>
            Profil Saya
        </h1>
    </x-slot>

    <div class="row mb-4">
        <div class="col-lg-8">
            <!-- Update Profile Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="bi bi-person"></i> Informasi Profil
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="bi bi-lock"></i> Perbarui Password
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="card">
                <div class="card-header bg-danger">
                    <i class="bi bi-exclamation-triangle"></i> Zona Berbahaya
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- User Info Summary -->
            <div class="card sticky-top" style="top: 1rem;">
                <div class="card-header">
                    <i class="bi bi-info-circle"></i> Info Akun
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted-sm">Nama</small>
                        <p class="mb-0">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted-sm">Email</small>
                        <p class="mb-0">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted-sm">Roles</small>
                        <p class="mb-0">
                            @forelse(auth()->user()->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @empty
                                <span class="text-muted-sm">Tidak ada</span>
                            @endforelse
                        </p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted-sm">Member Sejak</small>
                        <p class="mb-0">{{ auth()->user()->created_at->locale('id')->format('d M Y') }}</p>
                    </div>
                    <hr>
                    <p class="small text-muted-sm mb-0">
                        <i class="bi bi-info-circle"></i>
                        Perbarui informasi profil dan password Anda untuk menjaga keamanan akun.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

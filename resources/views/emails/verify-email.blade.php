@component('mail::message')
# Verifikasi Email Anda

Halo {{ $user->name }},

Terima kasih telah mendaftar di **{{ config('app.name') }}**!

Untuk menyelesaikan pendaftaran, silakan verifikasi email Anda dengan mengklik tombol di bawah:

@component('mail::button', ['url' => $verifyUrl, 'color' => 'success'])
Verifikasi Email
@endcomponent

**Link ini berlaku selama 1 jam** (hingga {{ $expiresAt->format('H:i d/m/Y') }})

Jika Anda tidak membuat akun ini, abaikan email ini.

Salam,<br>
{{ config('app.name') }}

---

*Jika tombol di atas tidak berfungsi, salin dan buka link ini di browser Anda:*  
{{ $verifyUrl }}
@endcomponent

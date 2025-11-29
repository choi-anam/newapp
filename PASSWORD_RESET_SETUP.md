# 📱 Password Reset dengan OTP & Multi-Channel - Setup Guide

## 📋 Overview

Fitur password reset telah diperbarui dengan:
- ✅ **OTP 6-digit** yang berlaku 15 menit
- ✅ **Multi-channel delivery**: Email, Telegram, WhatsApp
- ✅ **User pilih channel**: Fleksibel sesuai preferensi user
- ✅ **Kolom baru di User**: `phone` dan `telegram_id`
- ✅ **Auto-cleanup**: OTP expired otomatis dihapus

---

## 🚀 Langkah Setup

### 1. Database Migration (Sudah Dijalankan)
```bash
php artisan migrate
```

✅ Migrations yang dijalankan:
- `2025_11_29_000001_add_phone_and_telegram_to_users_table.php` - Tambah kolom phone & telegram_id ke users
- `2025_11_29_000002_create_password_reset_otps_table.php` - Buat tabel password_reset_otps
- `2025_11_29_000003_update_password_reset_tokens_table.php` - Tambah kolom channel ke password_reset_tokens

---

### 2. Environment Configuration

Tambahkan ke `.env` file:

#### Email Configuration (WAJIB)
```env
# Laravel Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io        # atau mailgun, sendgrid, dll
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@yourapp.com
MAIL_FROM_NAME="Your App Name"
```

#### Telegram Configuration (OPSIONAL - untuk OTP via Telegram)
```env
# Telegram Bot
SERVICES_TELEGRAM_BOT_TOKEN=your_bot_token_here
```

**Cara dapatkan token:**
1. Buka Telegram, cari @BotFather
2. Ketik `/newbot`
3. Ikuti instruksi
4. Copy token yang diberikan ke SERVICES_TELEGRAM_BOT_TOKEN

#### WhatsApp Configuration via Twilio (OPSIONAL - untuk OTP via WhatsApp)
```env
# Twilio WhatsApp
SERVICES_TWILIO_ACCOUNT_SID=your_account_sid
SERVICES_TWILIO_AUTH_TOKEN=your_auth_token
SERVICES_TWILIO_PHONE_NUMBER=+1234567890
```

**Cara setup:**
1. Sign up di https://www.twilio.com
2. Verifikasi nomor Anda
3. Dapatkan Account SID & Auth Token dari dashboard
4. Setup WhatsApp sandbox atau business account
5. Copy credentials ke ENV

---

### 3. User Table - Tambah Data Telefon (OPSIONAL)

Untuk mengaktifkan Telegram & WhatsApp OTP, user harus punya data:

```php
// Update user dengan nomor dan telegram ID
$user = User::find(1);
$user->update([
    'phone' => '+6281234567890',           // Format: +62XXXXXXXXXX
    'telegram_id' => '123456789',          // Numeric ID
]);
```

**Cara dapatkan Telegram ID:**
- Kirim pesan ke bot Anda
- Check di webhook/logs untuk `chat_id`
- Atau gunakan bot @userinfobot

---

## 🔐 Alur Penggunaan

### User Melupakan Password

1. **Halaman Login** → Klik "Lupa password?"
2. **Enter Email** → Form input email
3. **Pilih Channel** → Pilih Email / Telegram / WhatsApp
4. **Terima OTP** → OTP dikirim ke channel pilihan (berlaku 15 menit)
5. **Input OTP + Password Baru** → Verifikasi & reset
6. **Success** → Redirect ke login, login dengan password baru

### Routes User

```
GET    /forgot-password-otp
POST   /forgot-password-otp               (password.forgot.store)
POST   /send-reset-otp                    (password.send-otp)
GET    /verify-reset-otp?email=...&channel=...
POST   /verify-reset-otp                  (password.otp.store)
POST   /resend-reset-otp                  (password.otp.resend)
```

---

## 🛠️ File Yang Ditambah/Diubah

### Models
- ✅ `app/Models/PasswordResetOtp.php` - Model untuk OTP
- ✅ `app/Models/User.php` - Updated dengan relationship & fields

### Controllers
- ✅ `app/Http/Controllers/Auth/ForgotPasswordController.php` - Handle lupa password
- ✅ `app/Http/Controllers/Auth/VerifyPasswordResetOtpController.php` - Handle OTP verifikasi

### Services
- ✅ `app/Services/PasswordResetService.php` - Logic OTP generation, sending, verification

### Notifications
- ✅ `app/Notifications/SendPasswordResetOtpNotification.php` - Email notification

### Views
- ✅ `resources/views/auth/forgot-password-otp.blade.php` - Form lupa password
- ✅ `resources/views/auth/select-reset-channel.blade.php` - Pilih channel
- ✅ `resources/views/auth/verify-otp.blade.php` - Verifikasi OTP & reset
- ✅ `resources/views/auth/login.blade.php` - Updated link

### Migrations
- ✅ `database/migrations/2025_11_29_000001_add_phone_and_telegram_to_users_table.php`
- ✅ `database/migrations/2025_11_29_000002_create_password_reset_otps_table.php`
- ✅ `database/migrations/2025_11_29_000003_update_password_reset_tokens_table.php`

### Config
- ✅ `config/services.php` - Tambah telegram, twilio, whatsapp config

---

## 🧪 Testing

### Test Email OTP
```bash
# Di Terminal:
php artisan tinker

# Di tinker:
$user = User::first();
$user->update(['email' => 'test@example.com']);

# Buka halaman
GET /forgot-password-otp
POST /forgot-password-otp dengan email=test@example.com
```

### Test Telegram OTP
```bash
# Set telegram_id di user:
$user = User::first();
$user->update(['telegram_id' => '123456789']); // Ganti dengan ID Anda

# Halaman akan show Telegram option
# Klik Telegram → OTP dikirim ke Telegram
```

### Test WhatsApp OTP
```bash
# Set phone & telegram_id:
$user = User::first();
$user->update(['phone' => '+6281234567890']);

# Jika Twilio configured, WhatsApp option akan muncul
```

---

## 🔒 Security Features

✅ **OTP Expiration** - 15 menit auto-expire  
✅ **One-time Use** - OTP hanya bisa digunakan sekali (is_used flag)  
✅ **Auto Cleanup** - OTP expired otomatis dihapus  
✅ **CSRF Protection** - Semua form punya @csrf  
✅ **Password Hashing** - Password di-hash sebelum disimpan  
✅ **Validation** - Semua input di-validate  
✅ **Rate Limiting** - (Bisa ditambahkan di middleware jika perlu)  

---

## 📊 Database Schema

### password_reset_otps table
```sql
id              - Primary Key
user_id         - Foreign Key ke users.id
email           - Email user saat reset
otp             - 6 digit OTP code
channel         - enum: 'email', 'telegram', 'whatsapp'
expires_at      - Expiration timestamp (now + 15 min)
is_used         - Boolean flag (used once)
created_at      - Created timestamp
updated_at      - Updated timestamp
```

### users table (updated columns)
```sql
phone           - Phone number (nullable)
telegram_id     - Telegram user ID (nullable)
```

### password_reset_tokens table (updated)
```sql
channel         - enum: 'email', 'telegram', 'whatsapp' (default: 'email')
```

---

## 🐛 Troubleshooting

### "Email tidak ditemukan"
→ User belum register atau email salah

### "OTP tidak valid atau sudah kadaluarsa"
→ OTP sudah expired (15 menit) atau salah input
→ Gunakan tombol "Minta ulang OTP"

### Telegram OTP tidak diterima
→ Check SERVICES_TELEGRAM_BOT_TOKEN di .env
→ Pastikan bot sudah di-invite ke chat dengan user
→ User harus punya telegram_id terisi

### WhatsApp OTP tidak diterima
→ Check Twilio credentials di .env
→ Pastikan WhatsApp sandbox sudah setup
→ User harus punya phone field terisi

### Email tidak terkirim
→ Check MAIL_* configuration di .env
→ Test mail dengan: `php artisan tinker` → `Mail::raw('test', fn($m) => $m->to('email@test.com'));`
→ Check log di `storage/logs/`

---

## 🚀 Future Enhancements (Optional)

- [ ] Add rate limiting (max 5 OTP requests per 15 min)
- [ ] Add IP logging untuk audit trail
- [ ] SMS via Amazon SNS atau Vonage (Nexmo)
- [ ] 2FA dengan Google Authenticator
- [ ] Backup codes untuk emergency access
- [ ] Email notification after successful password reset
- [ ] Admin panel untuk view & manage OTPs
- [ ] OTP resend schedule (cegah spam)

---

## 📝 Notes

- Legacy email-based password reset (dengan token link) tetap tersedia di `/forgot-password`
- OTP-based reset baru tersedia di `/forgot-password-otp`
- User bisa pilih channel saat ada multiple options
- Channel tidak tersedia akan di-disable (opacity 50%) tapi tetap ditampilkan
- OTP validation case-sensitive (6 digit numeric)

---

## 📞 Support

Jika ada pertanyaan atau issue, silakan:
1. Check dokumentasi di `FITUR_DAN_DOKUMENTASI.md`
2. Check log di `storage/logs/laravel.log`
3. Check database `password_reset_otps` table
4. Check email/Telegram/WhatsApp settings di `.env`

---

**Last Updated:** November 29, 2025  
**Version:** 1.0.0

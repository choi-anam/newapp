# 🔐 Email Verification & OTP Testing Guide

## ✅ Tahapan Setup Sebelum Testing

### 1. Konfigurasi Gmail (SUDAH DILAKUKAN ✓)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=koleksianam@gmail.com
MAIL_PASSWORD='grtv laqp znku wkrm'  # App-specific password
MAIL_FROM_ADDRESS=koleksianam@gmail.com
```

### 2. Database Requirements
- ✅ `phone` column di users table (nullable)
- ✅ `telegram_id` column di users table (nullable)
- ✅ `password_reset_otps` table dengan OTP logic

### 3. Code Changes (SELESAI ✓)
- ✅ Removed `implements ShouldQueue` dari Notification (email now sent instantly)
- ✅ Added email validation API endpoint at `/api/check-email`
- ✅ Updated all auth views dengan modern Sneat design
- ✅ Added form validation dan email checking di frontend

---

## 🧪 Testing Procedure

### Test 1: Email Validation API
```bash
# Cek email yang registered
curl -X POST http://localhost:8000/api/check-email \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{"email":"user@example.com"}'

# Expected response untuk registered email:
# {"exists":true,"email":"user@example.com"}

# Expected response untuk unregistered email:
# {"exists":false,"email":"notexist@example.com"}
```

### Test 2: Forgot Password Flow (Traditional)
1. Go to: http://localhost:8000/forgot-password
2. Enter registered email
3. Wait for "Email terdaftar ✓" message
4. Click "Kirim Link Reset Password"
5. Check email inbox untuk reset link
6. Click link dan set password baru

### Test 3: Forgot Password OTP Flow
1. Go to: http://localhost:8000/forgot-password-otp
2. Enter registered email
3. Wait for "Email terdaftar ✓" message
4. Click "Kirim Link Reset Password"
5. Select channel (Email/Telegram/WhatsApp)
6. Check email untuk 6-digit OTP
7. Enter OTP pada halaman verification
8. Set password baru
9. Login dengan password baru

---

## 📊 Database Verification

### Check Users
```sql
-- List semua users
SELECT id, name, email, phone, telegram_id, created_at FROM users;

-- Find specific user
SELECT * FROM users WHERE email = 'your-email@example.com';
```

### Check OTP Records
```sql
-- Lihat OTP yang dikirim
SELECT * FROM password_reset_otps 
WHERE email = 'your-email@example.com' 
ORDER BY created_at DESC;

-- Cek expired OTP
SELECT * FROM password_reset_otps 
WHERE expires_at < NOW() 
LIMIT 5;
```

### Check Password Reset Tokens (Legacy)
```sql
-- Lihat reset tokens
SELECT * FROM password_reset_tokens 
ORDER BY created_at DESC LIMIT 5;
```

---

## 🎯 Expected Results

### ✅ Successful Email Sending
1. Form submitted
2. Notification sent instantly (no queue wait)
3. Email diterima dalam 1-2 detik
4. Email berisi OTP code dan instructions

### ✅ Valid OTP Entry
1. OTP accepted jika:
   - Format 6 digits
   - Belum expired (< 15 menit)
   - Belum digunakan sebelumnya
2. Password berhasil direset
3. Login dengan password baru berhasil

### ❌ Invalid OTP Entry
1. OTP rejected jika:
   - Format bukan 6 digits
   - Sudah expired
   - Sudah digunakan sebelumnya
   - OTP salah

---

## 🔍 Debugging Commands

### Clear Cache & Config
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Check Mail Configuration
```bash
php artisan config:show mail
```

### View Recent Logs
```bash
tail -50 storage/logs/laravel.log
```

### Test Notification Directly (Tinker)
```bash
php artisan tinker
```
Kemudian di tinker:
```php
$user = App\Models\User::find(1);
$user->notify(new App\Notifications\SendPasswordResetOtpNotification('123456', 'email', $user->email));
```

---

## 📝 Checklist Before Commit

- [ ] Email config di `.env` menggunakan SMTP
- [ ] Gmail App-specific password digunakan
- [ ] Email client terbuka dan siap menerima
- [ ] Test email validation API
- [ ] Test full password reset flow
- [ ] Test OTP verification flow
- [ ] Check database untuk OTP records
- [ ] Verify dark mode works
- [ ] Test on mobile view
- [ ] All Laravel artisan commands pass

---

## 📞 Troubleshooting

Jika email tidak diterima:
1. Periksa `.env` → `MAIL_MAILER=smtp`
2. Periksa Gmail settings → Enable 2-Step Verification
3. Periksa Gmail App passwords → Generate & use 16-char password
4. Clear config: `php artisan config:clear`
5. Check logs: `tail storage/logs/laravel.log | grep -i mail`

---

**Created:** November 29, 2025
**Version:** 1.0
**Status:** Ready for testing

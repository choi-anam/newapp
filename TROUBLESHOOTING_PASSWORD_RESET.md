# Troubleshooting: Password Reset Dengan OTP

## 📋 Checklist Konfigurasi

### 1. Mail Configuration
✅ `MAIL_MAILER=smtp` - Harus SMTP, bukan `log`
✅ `MAIL_HOST=smtp.gmail.com` - Server SMTP Gmail
✅ `MAIL_PORT=587` - Port TLS Gmail
✅ `MAIL_USERNAME=koleksianam@gmail.com` - Username email
✅ `MAIL_PASSWORD='grtv laqp znku wkrm'` - App-specific password (bukan password akun biasa)
✅ `MAIL_FROM_ADDRESS=koleksianam@gmail.com` - Dari address harus sama dengan username

### 2. Notification Configuration
✅ Notification **TIDAK di-queue** lagi (removed `implements ShouldQueue`)
✅ Email dikirim langsung saat form disubmit
✅ Tidak perlu menjalankan queue worker untuk email

### 3. API Email Check
✅ Route `api.check-email` tersedia di `/api/check-email`
✅ Validasi email terdaftar sebelum submit form
✅ Response JSON: `{"exists": true/false, "email": "..."}`

---

## 🔧 Testing Email

### Metode 1: Via Web Browser
1. Buka http://localhost:8000/forgot-password
2. Masukkan email terdaftar (cek di database users)
3. Seharusnya muncul "Email terdaftar ✓" setelah 500ms
4. Submit form
5. Cek email yang terdaftar untuk menerima OTP

### Metode 2: Check Database
```sql
-- Cek apakah email user ada
SELECT id, email, phone, telegram_id FROM users WHERE email = 'your-email@example.com';

-- Cek OTP yang dikirim
SELECT * FROM password_reset_otps WHERE email = 'your-email@example.com' ORDER BY created_at DESC;

-- Cek queue jobs (jika masih ada yang tertunda)
SELECT * FROM jobs ORDER BY created_at DESC;
```

### Metode 3: Check Mail Logs
```bash
# Lihat recent logs
tail -100 "storage/logs/laravel.log"

# Search for mail-related errors
grep -i "mail\|email\|smtp" "storage/logs/laravel.log" | tail -20
```

---

## ❌ Troubleshooting Common Issues

### Issue 1: "Email tidak terdaftar di sistem"
**Penyebab:** Email benar-benar tidak ada di database users

**Solusi:**
- Cek di database: `SELECT email FROM users;`
- Pastikan user sudah terdaftar/verified
- Gunakan email yang sama dengan saat registrasi

---

### Issue 2: "Email diterima tapi tanpa OTP"
**Penyebab:** Notification tidak mengirim OTP ke email

**Solusi:**
1. Periksa `.env` bahwa `MAIL_MAILER=smtp`
2. Verifikasi Gmail password adalah **App-specific password**, bukan password akun
3. Cek logs untuk SMTP errors: `tail storage/logs/laravel.log`

---

### Issue 3: "Tidak ada email masuk sama sekali"
**Penyebab:** SMTP tidak bisa terhubung ke Gmail

**Solusi:**
1. **Update .env:**
   ```env
   MAIL_MAILER=smtp
   MAIL_SCHEME=tls
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   ```

2. **Gunakan App-specific Password:**
   - Go to Google Account: https://myaccount.google.com
   - Enable 2-Step Verification if not already enabled
   - Go to App passwords: https://myaccount.google.com/apppasswords
   - Select Mail → Windows Computer
   - Copy the 16-character password
   - Put it in `.env` MAIL_PASSWORD (remove spaces)

3. **Test dengan artisan:**
   ```bash
   php artisan mail:send
   ```

4. **Check firewall/antivirus:**
   - XAMPP mungkin diblokir untuk mengirim email
   - Whitelist port 587 atau gunakan port 465 (SSL)

---

### Issue 4: "Form button tetap disabled"
**Penyebab:** Email validation gagal atau API endpoint tidak accessible

**Solusi:**
1. Periksa browser console (F12) untuk errors JavaScript
2. Verifikasi route `api.check-email` ada di `routes/web.php`
3. Cek CSRF token valid di form
4. Ping route: `curl -X POST http://localhost:8000/api/check-email -H "Content-Type: application/json" -d '{"email":"test@example.com"}'`

---

## 🧪 Manual Testing Flow

### Test 1: Email Verification
```bash
# 1. Register user
# 2. Note email: test@example.com

# 3. Forgot password
# 4. Check email verification in browser console
#    Should see: "Email terdaftar ✓"

# 5. Submit form
# 6. Check actual email inbox for OTP code
```

### Test 2: OTP Verification
```bash
# 1. After OTP email received, copy 6-digit OTP
# 2. Go to OTP verification page
# 3. Enter OTP (should auto-format)
# 4. Enter new password
# 5. Confirm password
# 6. Submit
# 7. Should see success message
# 8. Try login with new password
```

### Test 3: Database Verification
```bash
# After successful reset:
SELECT id, email, created_at FROM password_reset_otps 
WHERE email = 'test@example.com' 
ORDER BY created_at DESC LIMIT 1;

# Should show is_used = 1 (true)
```

---

## 📞 Contact Support

Jika masih ada masalah:

1. **Check .env file:**
   - `MAIL_MAILER` harus `smtp`
   - `MAIL_HOST` harus `smtp.gmail.com`
   - `MAIL_PORT` harus `587`

2. **Check Gmail settings:**
   - 2-Step Verification enabled
   - App-specific password digunakan

3. **Check database:**
   - User dengan email tersedia
   - `phone` dan `telegram_id` nullable untuk test

4. **Clear cache:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

---

**Last Updated:** November 29, 2025
**Version:** 1.0

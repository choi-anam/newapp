# Setup Telegram OTP untuk Password Reset

## 📱 Langkah-Langkah Setup Telegram

### 1. Dapatkan Bot Token Telegram

1. **Buka Telegram** dan cari `@BotFather`
2. **Klik tombol START** atau ketik `/start`
3. **Ketik `/newbot`** untuk membuat bot baru
4. **Isi nama bot** (contoh: "AppBot Reset Password")
5. **Isi username bot** (contoh: "appbot_reset_password") - harus unik
6. **Copy bot token** yang diberikan (format: `6916480326:AAFS-k_...`)

### 2. Masukkan Bot Token ke `.env`

Tambahkan ke file `.env`:
```env
SERVICES_TELEGRAM_BOT_TOKEN=6916480326:AAFS-k_GTzNTUJiXoyyQkJDnon7YS3aWyfc
```

### 3. Dapatkan Telegram Chat ID (telegram_id)

User perlu mengetahui chat ID mereka sendiri. Ada 2 cara:

#### Cara 1: Via Bot (Recommended)
1. **Start bot Anda** di Telegram (cari username bot yang dibuat)
2. **Ketik `/start` atau message apapun**
3. Bot akan merespon dengan chat ID
4. **Copy chat ID** (angka panjang, contoh: `-987654321`)

#### Cara 2: Via URL
1. **Buka di browser:**
   ```
   https://api.telegram.org/bot[TOKEN]/getMe
   ```
   Ganti `[TOKEN]` dengan bot token Anda
   
2. Jika berhasil, Anda akan lihat JSON response

### 4. Simpan telegram_id di Database

User perlu memasukkan telegram_id mereka:

```sql
-- Update user dengan telegram_id
UPDATE users 
SET telegram_id = '987654321' 
WHERE email = 'user@example.com';

-- Verifikasi
SELECT id, email, telegram_id FROM users WHERE email = 'user@example.com';
```

Atau di aplikasi, tambahkan form untuk user update profile:

```blade
<label for="telegram_id">Telegram ID</label>
<input type="text" name="telegram_id" id="telegram_id" placeholder="Cari @BotFather di Telegram">
<small>Cari @BotFather di Telegram, start bot, dan dapatkan chat ID Anda</small>
```

---

## 🧪 Testing Telegram OTP

### Test 1: Manual API Test
```bash
# Test telegram API langsung
curl -X POST https://api.telegram.org/bot[TOKEN]/sendMessage \
  -d chat_id=987654321 \
  -d text="Test message" \
  -d parse_mode=Markdown
```

### Test 2: Via Application
1. Go to http://localhost:8000/password-method
2. Click "Kode OTP"
3. Enter registered email
4. Select "Telegram" channel
5. Check Telegram for OTP message

### Test 3: Check Database
```sql
-- Verify OTP record
SELECT * FROM password_reset_otps 
WHERE email = 'user@example.com' 
ORDER BY created_at DESC LIMIT 1;

-- Should show:
-- - email: user email
-- - otp: 6-digit code
-- - channel: 'telegram'
-- - expires_at: 15 minutes from now
-- - is_used: 0 (false)
```

### Test 4: Check Logs
```bash
# See Telegram API errors
tail -50 storage/logs/laravel.log | grep -i "telegram\|telegram_id"
```

---

## ❌ Troubleshooting

### Issue 1: "Telegram tidak tersedia"
**Penyebab:** User tidak memiliki telegram_id atau channel tidak tersedia

**Solusi:**
1. Periksa database: `SELECT telegram_id FROM users WHERE email = 'your@email.com';`
2. Jika NULL atau kosong, update dengan telegram ID yang benar
3. Verifikasi bot token di `.env`

---

### Issue 2: "Telegram message tidak terkirim"
**Penyebab:** Bot token salah, chat ID salah, atau tidak ada koneksi

**Solusi:**
1. Verifikasi bot token:
   ```bash
   php artisan tinker
   >>> config('services.telegram.bot_token')
   // Should output your token, not null
   ```

2. Test chat ID langsung:
   ```bash
   curl -X POST https://api.telegram.org/bot[YOUR_TOKEN]/sendMessage \
     -d chat_id=[USER_CHAT_ID] \
     -d text="Test message"
   ```

3. Check logs untuk error detail:
   ```bash
   tail -100 storage/logs/laravel.log | grep -i "telegram"
   ```

---

### Issue 3: "Chat ID tidak valid"
**Penyebab:** User masukkan chat ID yang salah

**Solusi:**
1. Buka Telegram, cari `@BotFather`
2. Buat bot baru atau gunakan yang sudah ada
3. Start bot dengan `/start`
4. Bot akan kirim chat ID yang benar
5. Update database dengan chat ID yang benar

---

## 📊 Database Schema

```sql
-- telegram_id column di users table
ALTER TABLE users ADD COLUMN telegram_id VARCHAR(255) NULL;

-- password_reset_otps akan menyimpan channel yang digunakan
SELECT * FROM password_reset_otps WHERE channel = 'telegram';
```

---

## 🔐 Security Tips

1. **Jangan share bot token** publicly
2. **telegram_id tidak sensitive** (bisa di-expose)
3. **OTP berlaku 15 menit saja** - cukup aman
4. **Log semua Telegram send attempts** untuk audit

---

## 📚 Referensi

- **Telegram Bot API:** https://core.telegram.org/bots/api
- **BotFather:** https://t.me/BotFather
- **Get Chat ID Tool:** https://t.me/userinfobot (alternative)

---

**Created:** November 29, 2025
**Status:** Ready to Use

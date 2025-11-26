# Activity Log Management System

## Fitur yang Telah Ditambahkan

Sistem lengkap untuk mengelola dan membersihkan activity logs agar tidak membebani server.

### 1. **Admin Dashboard**

**URL:** `/admin/activities/management`

Fitur utama:
- 📊 Statistik log (Total, Hari ini, Bulan ini, Terarsipkan)
- 📅 Info log tertua
- 📦 **Arsipkan Log** - Pindahkan log lama ke arsip (data tetap aman)
- 🗑️ **Hapus Log** - Hapus permanen log lama (tidak bisa dibatalkan)
- ⚠️ **Hapus Semua** - Operasi berbahaya untuk menghapus semua log

### 2. **Models & Database**

**ActivityLogArchive Model** (`app/Models/ActivityLogArchive.php`)
- Menyimpan backup JSON dari activity logs yang diarsipkan
- Schema: `id`, `activity_id`, `log_type`, `activity_data`, `archived_at`, `timestamps`
- Kolom `log_type`: `manual` (manual arsip) atau `scheduled` (via command)

**Tabel:** `activity_log_archives`

### 3. **Controller Methods**

**ActivityController** (`app/Http/Controllers/Admin/ActivityController.php`)

Metode baru:

| Method | Deskripsi |
|--------|-----------|
| `management()` | Tampilkan halaman manajemen log |
| `archive(Request)` | Arsipkan log lebih lama dari X hari |
| `cleanup(Request)` | Hapus permanen log lebih lama dari X hari |
| `truncate(Request)` | Hapus SEMUA log (berbahaya) |
| `archives(Request)` | Tampilkan daftar log terarsipkan |
| `restoreArchive(Archive)` | Pulihkan log dari arsip ke tabel activities |

### 4. **Views**

#### Management Page
**File:** `resources/views/admin/activities/management.blade.php`

Fitur:
- Card statistik dengan angka-angka penting
- Form untuk arsipkan dengan input jumlah hari
- Form untuk hapus dengan input jumlah hari
- Tombol berbahaya untuk hapus semua (dengan konfirmasi berlapis)
- Rekomendasi best practices

#### Archives Page
**File:** `resources/views/admin/activities/archives.blade.php`

Fitur:
- Daftar semua log terarsipkan
- Filter berdasarkan tipe arsip (manual/scheduled)
- Pagination
- Detail modal dengan informasi lengkap arsip
- Tombol restore untuk mengembalikan dari arsip

### 5. **Routes**

```php
GET    /admin/activities/management          → admin.activities.management
POST   /admin/activities/archive             → admin.activities.archive
POST   /admin/activities/cleanup             → admin.activities.cleanup
POST   /admin/activities/truncate            → admin.activities.truncate
GET    /admin/activities-archives            → admin.activities.archives
POST   /admin/activities-archives/{id}/restore → admin.activities.restore-archive
```

### 6. **Artisan Commands**

#### Archive Command
```bash
php artisan logs:archive --days=90
```
- Arsipkan logs lebih lama dari 90 hari
- Opsi: `--days=N` (default 90)
- Output: Progress bar dan ringkasan

#### Cleanup Command
```bash
php artisan logs:cleanup --days=365
```
- Hapus permanen logs lebih lama dari 365 hari
- Opsi: `--days=N` (default 365), `--force` (skip konfirmasi)
- Output: Konfirmasi interaktif (kecuali `--force`)

### 7. **User Interface Flow**

1. **Akses Manajemen**
   - Klik tombol "Manage / Archive" di Activity Log index
   - Atau navigasi ke `/admin/activities/management`

2. **Arsipkan Log Lama**
   - Input jumlah hari (default: 90)
   - Klik "Arsipkan Sekarang"
   - Sistem akan:
     - Query logs lebih lama dari X hari
     - Buat entry di `activity_log_archives` dengan backup JSON
     - Hapus dari tabel `activities`

3. **Lihat Arsip**
   - Klik link "Lihat Arsip" di card statistik
   - Atau akses `/admin/activities-archives`
   - Filter berdasarkan tipe (manual/scheduled)
   - Klik eye icon untuk detail lengkap
   - Klik restore untuk mengembalikan ke activities

4. **Hapus Permanen**
   - Input jumlah hari (default: 180)
   - Klik "Hapus Sekarang" (need confirmation)
   - Log akan dihapus PERMANEN

### 8. **Scheduling (Optional)**

Untuk otomasi arsipkan setiap bulan, tambahkan ke `app/Console/Kernel.php`:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Archive logs older than 90 days monthly
    $schedule->command('logs:archive --days=90')->monthlyOn(1, '02:00');
    
    // Cleanup archived logs yearly (keep 1 year of archives)
    $schedule->command('logs:cleanup --days=365 --force')->yearlyOn(1, 1, '03:00');
}
```

### 9. **Data Safety**

✅ **Sebelum Hapus:**
1. Arsipkan terlebih dahulu (backup JSON tersimpan)
2. Pastikan memiliki backup database
3. Verifikasi data sebelum permanen delete

✅ **Restore:**
- Log terarsipkan dapat dipulihkan kapan saja
- Klik restore di halaman archives

### 10. **Performance Tips**

🚀 **Untuk Database yang Besar:**

1. **Rutin Arsipkan** - Jalankan `php artisan logs:archive --days=90` setiap minggu
2. **Monitor Size** - Check ukuran tabel dengan:
   ```sql
   SELECT table_name, ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
   FROM information_schema.TABLES
   WHERE table_name = 'activities';
   ```
3. **Index** - Tabel activities sudah terindeks oleh Spatie
4. **Archive Regularly** - Jangan biarkan activities table tumbuh terlalu besar

### 11. **Security**

🔐 **Proteksi:**
- Semua endpoint dilindungi middleware `IsAdmin`
- Operasi berbahaya meminta konfirmasi
- Log type mencatat sumber (manual vs scheduled)
- Restore hanya ke archive table, tidak otomatis restore

### 12. **API Summary**

| Operasi | Manual | Command | Auto |
|---------|--------|---------|------|
| Archive | ✅ | ✅ | ⚙️ (via scheduler) |
| Cleanup | ✅ | ✅ | ⚙️ (via scheduler) |
| Restore | ✅ | ❌ | ❌ |
| Delete All | ✅ | ❌ | ❌ |

---

## Contoh Workflow

### Scenario 1: Arsipkan Setiap Bulan
```bash
# Jalankan command pada tanggal 1 setiap bulan
php artisan logs:archive --days=90

# Output:
# 🔄 Archiving activity logs older than 90 days...
# Found 5,432 logs to archive.
# ▓▓▓▓▓▓▓▓▓▓ 5,432/5,432
# ✓ Successfully archived 5,432 activity logs!
```

### Scenario 2: Hapus Log Sangat Lama Setiap Tahun
```bash
# Jalankan di akhir tahun
php artisan logs:cleanup --days=365 --force

# Output:
# ⚠️  This will PERMANENTLY DELETE logs older than 365 days!
# Cutoff date: 2024-11-26
# Found 1,200 logs to delete.
# 🗑️  Deleting activity logs older than 365 days...
# ✓ Successfully deleted 1,200 activity logs!
```

### Scenario 3: Emergency Cleanup
1. Admin buka `/admin/activities/management`
2. Lihat statistik: "Total Log: 50,000"
3. Klik "Arsipkan Sekarang" dengan 180 hari
4. 30,000 logs terarsipkan
5. Database lebih ringan, performa meningkat
6. Log masih tersimpan di arsip jika diperlukan

---

## Next Steps

1. ✅ Implementasi selesai
2. ⏳ Test fitur di staging
3. 🔄 Setup scheduler untuk auto-archive
4. 📊 Monitor database size dengan rutin
5. 🚀 Deploy ke production

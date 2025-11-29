# 📋 Dokumentasi Fitur Admin Panel & Activity Logging System

**Last Updated:** November 29, 2025 (WebSocket Real-time Online Users)  
**Laravel Version:** 12.40.1  
**PHP Version:** 8.2.12

---

## 📚 Daftar Isi

1. [Overview Sistem](#overview-sistem)
2. [Fitur Admin Panel](#fitur-admin-panel)
3. [Fitur Activity Logging](#fitur-activity-logging)
4. [Fitur Archive & Cleanup](#fitur-archive--cleanup)
5. [Fitur Export Excel](#fitur-export-excel)
6. [Grid.js Integration](#gridjs-integration)
7. [File & Struktur](#file--struktur)
8. [Routes](#routes)
9. [Database Schema](#database-schema)
10. [Artisan Commands](#artisan-commands)

---

## Overview Sistem

Aplikasi Laravel 12 dengan sistem manajemen **Role & Permission** (menggunakan Spatie), **Activity Logging** lengkap, **Export Excel**, dan **Grid.js** untuk tracking semua aktivitas user di admin panel.

### Penambahan Terbaru:
- 🌐 Landing page baru (Bootstrap 5) di `/` dengan hero, fitur, statistik, dan tautan GitHub.
- 🟢 Fitur "Online Users" dengan tracking `last_seen_at` otomatis via middleware global.
- 🔄 Dashboard menampilkan daftar user aktif (terlihat dalam 5 menit terakhir) auto-refresh.

### Teknologi Utama:
- **Laravel 12** - Framework
- **Spatie Laravel Permission** - RBAC (Role-Based Access Control)
- **Spatie Activity Log** - Activity Logging
- **Maatwebsite Excel** - Export to Excel
- **Grid.js** - Advanced Data Table with AJAX
- **Bootstrap 5.3** - UI Framework
- **SQLite** - Database
- **PWA** - Progressive Web App Support

---

## Fitur Admin Panel

### 1. 👥 Manajemen Roles

**URL:** `http://127.0.0.1:8000/admin/roles`

**Fitur:**
- ✅ Lihat daftar semua roles (dengan pagination)
- ✅ Tambah role baru
- ✅ Edit role (nama, deskripsi, permissions)
- ✅ Hapus role (proteksi super-admin tidak bisa dihapus)
- ✅ Assign permissions ke role
- ✅ Activity logging otomatis untuk semua operasi

**File Terkait:**
- Controller: `app/Http/Controllers/Admin/RoleController.php`
- Views: `resources/views/admin/roles/` (index, create, edit, show)
- Model: `Spatie\Permission\Models\Role`

**Catatan:** 
- Form menggunakan permission **names** (bukan IDs)
- Manual logging dihapus (sudah auto via observer)

---

### 2. 🔐 Manajemen Permissions

**URL:** `http://127.0.0.1:8000/admin/permissions`

**Fitur:**
- ✅ Lihat daftar semua permissions
- ✅ Tambah permission baru
- ✅ Edit permission
- ✅ Hapus permission
- ✅ Setiap permission punya deskripsi

**File Terkait:**
- Controller: `app/Http/Controllers/Admin/PermissionController.php`
- Views: `resources/views/admin/permissions/` (index, create, edit, show)
- Model: `Spatie\Permission\Models\Permission`

**Catatan:**
- Manual logging dihapus (sudah auto via observer)

---

### 3. 👤 Manajemen Users

**URL:** `http://127.0.0.1:8000/admin/users`

**Fitur:**
- ✅ Lihat daftar semua users (dengan pagination)
- ✅ Tambah user baru (dengan assign roles)
- ✅ Edit user (nama, email, roles)
- ✅ Hapus user (proteksi current user tidak bisa dihapus)
- ✅ Reset password user dengan generate password baru
- ✅ Activity logging untuk semua operasi

**File Terkait:**
- Controller: `app/Http/Controllers/Admin/UserController.php`
- Views: `resources/views/admin/users/` (index, create, edit, show)
- Model: `app/Models/User.php`
- Middleware: `app/Http/Middleware/IsAdmin.php`

**Catatan:**
- Form menggunakan role **names** (bukan IDs)
- Manual logging dihapus (sudah auto via observer)

---

## Fitur Activity Logging

### 1. 📊 Activity Log Viewer

**URL:** `http://127.0.0.1:8000/admin/activities`

**Fitur:**
- ✅ Lihat semua activity logs (dengan pagination 20 per halaman)
- ✅ Filter berdasarkan:
  - User (siapa yang melakukan aksi)
  - Model (Role, Permission, User, dll)
  - Tanggal range (from-to)
- ✅ Lihat detail aktivitas lengkap
- ✅ Tampilkan data dalam format readable
- ✅ Tombol "Manage / Archive" untuk mengelola logs

**File Terkait:**
- Controller: `app/Http/Controllers/Admin/ActivityController.php`
- Views: `resources/views/admin/activities/index.blade.php`
- Views: `resources/views/admin/activities/show.blade.php`

**Catatan:**
- Menggunakan short model names untuk UX lebih baik (User vs App\Models\User)
- Model dropdown auto-populate dari data existing

---

### 2. 🔍 Automatic Activity Logging

Activity otomatis direkam untuk:

| Model | Events | Status |
|-------|--------|--------|
| Role | created, updated, deleted | ✅ Auto |
| Permission | created, updated, deleted | ✅ Auto |
| User | created, updated, deleted | ✅ Auto |

**Implementasi:**
- Observer: `app/Observers/ModelActivityObserver.php`
- Registered in: `app/Providers/AppServiceProvider.php`
- Config: `config/activity-logger.php`

**Data Yang Dicatat:**
```
- Description: "created Role", "updated User", dll
- Subject: Model yang diubah (dengan ID)
- Causer: User yang melakukan aksi
- Properties: old values, changes, attributes
- Event: created/updated/deleted/restored
- Timestamp: created_at (waktu aksi)
```

### 3. 🟢 Online Users Tracking (Polling + Real-time)

Menampilkan user yang aktif/terhubung baru-baru ini (default: 5 menit terakhir) dengan 2 mekanisme:

1. Fallback polling (HTTP fetch setiap 30 detik)
2. Real-time push via WebSocket (Laravel Reverb + Laravel Echo)

**Implementasi (Polling Dasar):**
- Kolom baru `users.last_seen_at` (nullable timestamp, indexed)
- Middleware global: `app/Http/Middleware/TrackUserActivity.php` → update `last_seen_at` setiap ≥60 detik per user untuk efisiensi.
- Endpoint data JSON: `GET /admin/users-online-data` (method `UserController@onlineData`).
- Dashboard card: menampilkan list user online dengan roles + waktu terakhir aktif.

**Implementasi Real-time (WebSocket):**
- Paket server: Laravel Reverb (`composer require laravel/reverb -W`)
- Konfigurasi broadcasting ditambahkan / diperbarui di `config/broadcasting.php` dengan koneksi `reverb`:
  ```php
  'reverb' => [
    'driver' => 'reverb',
    'key' => env('REVERB_APP_KEY', 'local'),
    'secret' => env('REVERB_APP_SECRET'),
    'host' => env('REVERB_HOST', '127.0.0.1'),
    'port' => env('REVERB_PORT', 8080),
    'scheme' => env('REVERB_SCHEME', 'http'),
    'apps' => [
      [
        'key' => env('REVERB_APP_KEY', 'local'),
        'secret' => env('REVERB_APP_SECRET', 'secret'),
        'id' => env('REVERB_APP_ID', 'app-id'),
      ],
    ],
  ],
  ```
- Event broadcast: `app/Events/OnlineUsersUpdated.php` (implements `ShouldBroadcast`, channel publik `online-users`, nama event: `.OnlineUsersUpdated`).
- Middleware memanggil event setelah update `last_seen_at` dan membangun snapshot user aktif: `User::where('last_seen_at', '>=', now()->subMinutes(5))`.
- Frontend: inisialisasi Laravel Echo di `resources/js/bootstrap.js` menggunakan `laravel-echo` + dynamic load client Reverb dari CDN (karena paket npm `@laravel/reverb` belum tersedia).
- Script inline lama di `dashboard.blade.php` dihapus agar tidak terjadi duplikasi subscription.
- Fallback polling tetap ada jika WebSocket gagal (console akan menampilkan peringatan dan daftar tetap disegarkan tiap 30 detik).

**File Tambahan/Diubah (Real-time):**
- Event: `app/Events/OnlineUsersUpdated.php`
- Middleware: `app/Http/Middleware/TrackUserActivity.php` (menambahkan broadcast)
- JS Echo setup: `resources/js/bootstrap.js`
- Config: `config/broadcasting.php` (menambahkan koneksi reverb jika belum ada)
- View cleanup: `resources/views/admin/dashboard.blade.php` (hapus script Echo inline duplikat).

**ENV yang Direkomendasikan:**
```
BROADCAST_DRIVER=reverb
REVERB_APP_KEY=local
REVERB_APP_SECRET=secret
REVERB_APP_ID=app-id
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
```

**Perintah Setup & Jalankan (Development):**
```bash
# Install server komponen Reverb
composer require laravel/reverb -W
php artisan reverb:install

# Install client Echo
npm install laravel-echo --save

# Jalankan asset bundler
npm run dev

# Jalankan WebSocket server
php artisan reverb:start
```

**Alur Real-time:**
```
User request (misal navigasi halaman) → Middleware update last_seen_at (throttle ≥60 detik) → Event OnlineUsersUpdated dibroadcast → Echo client menerima payload → DOM list online users diperbarui instan
```

**Fallback Strategy:**
- Jika koneksi WebSocket gagal (Echo tidak terinisialisasi), polling HTTP tetap jalan setiap 30 detik.
- Memastikan data tetap akurat sambil menunggu perbaikan koneksi real-time.

**Logika Waktu Aktif:**
```php
User::where('last_seen_at', '>=', now()->subMinutes(5))
```

**Throttle Update:**
```php
if ($user->last_seen_at === null || $user->last_seen_at->diffInSeconds(now()) >= 60) {
  $user->forceFill(['last_seen_at' => now()])->save();
}
```

**File Terkait:**
- Middleware: `app/Http/Middleware/TrackUserActivity.php`
- Model: `app/Models/User.php` (cast `last_seen_at`)
- Route: `routes/web.php` (admin group: `admin/users-online-data`)
- View: `resources/views/admin/dashboard.blade.php`

**Keamanan & Optimasi:**
- Write dibatasi minimal 60 detik sekali per user.
- Hanya user terautentikasi yang di-track.
- Tidak menggunakan sesi tambahan.

**Pengembangan Lanjutan (Opsional):**
- Presence channel (autentikasi & daftar user otomatis)
- Private channel untuk keamanan lebih tinggi
- Status idle/away (menggunakan aktivitas DOM / timer)
- Optimasi payload (hanya kirim delta perubahan, bukan snapshot penuh)
- Server scaling (jalankan Reverb dengan supervisor / PM2 + reverse proxy)

---

### 3. ⚙️ Activity Log Settings

**URL:** `http://127.0.0.1:8000/admin/settings/activity-log`

**Fitur:**
- ✅ Lihat model mana saja yang di-track
- ✅ Enable/disable tracking per model via toggle switch
- ✅ Pilih attribute tertentu untuk di-log (comma-separated)
- ✅ Konfigurasi disimpan di database (bisa berubah tanpa deploy)

**File Terkait:**
- Controller: `app/Http/Controllers/Admin/ActivityLogSettingController.php`
- Views: `resources/views/admin/settings/activity-log.blade.php`
- Model: `app/Models/ActivityLogSetting.php`
- Migration: `database/migrations/2025_11_26_224323_create_activity_log_settings_table.php`

**Database Schema:**
```
activity_log_settings:
  - id (PK)
  - model_class (string, unique) - Full class name
  - enabled (boolean) - Track atau tidak
  - tracked_attributes (JSON) - Array attribute yang di-log
  - created_at, updated_at
```

---

## Fitur Archive & Cleanup

### 1. 📦 Activity Log Management Dashboard

**URL:** `http://127.0.0.1:8000/admin/activities/management`

**Fitur:**
- ✅ Statistik:
  - Total Log
  - Log Hari Ini
  - Log Bulan Ini
  - Log Terarsipkan
  - Info Log Tertua
- ✅ Arsipkan log lama (configurable hari)
- ✅ Hapus log lama permanen (configurable hari)
- ✅ Hapus semua logs (dangerous operation)
- ✅ Rekomendasi best practices

**File Terkait:**
- Method: `ActivityController@management()`
- Views: `resources/views/admin/activities/management.blade.php`

---

### 2. 🗂️ Archives Viewer

**URL:** `http://127.0.0.1:8000/admin/activities-archives`

**Fitur:**
- ✅ Lihat semua log yang sudah diarsipkan
- ✅ Filter berdasarkan log type (manual/scheduled)
- ✅ Filter berdasarkan tanggal arsip (range: dari-sampai)
- ✅ Pagination 20 per halaman
- ✅ Detail modal dengan JSON lengkap
- ✅ Restore single log dari arsip
- ✅ Lihat info: waktu arsip, deskripsi, model, user, tipe
- ✅ Export ke Excel (mengikuti filter aktif)
- ✅ Hapus arsip massal sesuai filter (dengan konfirmasi)
- ✅ Pulihkan semua arsip sesuai filter (dengan konfirmasi)

**File Terkait:**
- Method: `ActivityController@archives()`
- Method: `ActivityController@restoreArchive()`
- Method: `ActivityController@exportArchives()`
- Views: `resources/views/admin/activities/archives.blade.php`
- Model: `app/Models/ActivityLogArchive.php`
- Export: `app/Exports/ActivityLogArchivesExport.php`

**Database Schema:**
```
activity_log_archives:
  - id (PK)
  - activity_id (unique) - Reference ke activity yang diarsipkan
  - log_type (string) - 'manual' atau 'scheduled'
  - activity_data (JSON) - Complete backup dari activity record
  - archived_at (timestamp)
  - created_at, updated_at
```

---

### 3. 📨 Archive Logs

**Method:** `ActivityController@archive(POST)`  
**URL:** `POST /admin/activities/archive`

**Fitur:**
- ✅ Arsipkan logs lebih lama dari X hari
- ✅ Creates JSON backup di activity_log_archives
- ✅ Hapus dari activities table
- ✅ Reversible (bisa di-restore)
- ✅ Mark as 'manual' type

**Form Input:**
```
days: 1-365 (default: 90)
```

**Konfirmasi:**
```javascript
"Arsipkan log? Data akan dipindahkan ke tabel arsip."
```

---

### 4. 🗑️ Cleanup Logs

**Method:** `ActivityController@cleanup(POST)`  
**URL:** `POST /admin/activities/cleanup`

**Fitur:**
- ✅ Hapus logs lebih lama dari X hari secara permanen
- ✅ Requires confirmation
- ✅ **TIDAK BISA DIBATALKAN** - gunakan archive dulu!

**Form Input:**
```
days: 1-365 (default: 180)
```

**Konfirmasi:**
```javascript
"⚠️ PERHATIAN! Ini akan menghapus log secara permanen 
dan tidak dapat dibatalkan. Lanjutkan?"
```

---

### 5. ☢️ Truncate All Logs

**Method:** `ActivityController@truncate(POST)`  
**URL:** `POST /admin/activities/truncate`

**Fitur:**
- ✅ Hapus **SEMUA** logs sekaligus
- ✅ Requires 2x confirmation
- ✅ **SANGAT BERBAHAYA** - gunakan hanya saat emergency!

**Form Input:**
```
confirm: 'yes' (required)
```

---

## Artisan Commands

### 1. Archive Command

```bash
php artisan logs:archive [--days=90]
```

**Options:**
- `--days=N` - Archive logs older than N days (default: 90)

**Features:**
- Progress bar untuk dataset besar
- Batch processing
- JSON backup otomatis
- Output summary

**Contoh:**
```bash
# Archive logs lebih lama dari 90 hari
php artisan logs:archive

# Archive logs lebih lama dari 180 hari
php artisan logs:archive --days=180
```

**Output:**
```
🔄 Archiving activity logs older than 90 days (before 2025-08-27)...
Found 5,432 logs to archive.
▓▓▓▓▓▓▓▓▓▓ 5,432/5,432
✓ Successfully archived 5,432 activity logs!
```

**File:** `app/Console/Commands/ArchiveActivityLogs.php`

---

### 2. Cleanup Command

```bash
php artisan logs:cleanup [--days=365] [--force]
```

**Options:**
- `--days=N` - Delete logs older than N days (default: 365)
- `--force` - Skip confirmation dialog

**Features:**
- Interactive confirmation (skip dengan --force)
- Permanent deletion
- Configurable cutoff date
- Output summary

**Contoh:**
```bash
# Cleanup dengan confirmation
php artisan logs:cleanup --days=365

# Cleanup skip confirmation (untuk automation)
php artisan logs:cleanup --days=365 --force
```

**File:** `app/Console/Commands/CleanupActivityLogs.php`


### 1. Users Export

**URL:** `GET /admin/users-export`

- Men-download semua user sebagai `.xlsx`
- Kolom: ID, Name, Username, UID, Email, Roles, Created At
- File: `app/Exports/UsersExport.php`

### 2. Activities Export

**URL:** `GET /admin/activities-export`

- Men-download activity logs (mengikuti filter pada halaman index jika diterapkan via query)
- Kolom: Time, User, Description, Model, Event, Properties
- File: `app/Exports/ActivityLogsExport.php`

### 3. Activity Archives Export

**URL:** `GET /admin/activities-archives-export`

- Men-download log arsip (mengikuti filter query):
  - `log_type=manual|scheduled`
  - `date_from=YYYY-MM-DD`
  - `date_to=YYYY-MM-DD`
- Kolom: Archived At, Description, Model, Model ID, Event, Causer Name, Causer ID, Log Type, Original Created At, Batch UUID
- File: `app/Exports/ActivityLogArchivesExport.php`

---

## Grid.js Integration

### Halaman yang menggunakan Grid.js

- Users: `GET /admin/users`
- Activities: `GET /admin/activities`

### Fitur

- Server-side pagination (0-based index)
- Server-side search (parameter `search`)
- Auto-refresh setiap 30 detik (`grid.forceRender()`) 
- Aksi CRUD via tombol (Users: view/edit/delete)
- Render HTML aman via `gridjs.html(...)` untuk badges/aksi

### API Endpoints

- `GET /admin/users-data?search=&page=&limit=`
- `GET /admin/activities-data?search=&page=&limit=&user=&model=&date_from=&date_to=`

---

## File & Struktur

### Models Baru:
```
app/Models/
├── ActivityLogArchive.php (Activity archive)
└── User.php (dengan LogsActivity trait)
```

### Controllers:
```
app/Http/Controllers/Admin/
├── RoleController.php
├── PermissionController.php
├── UserController.php
├── ActivityController.php
└── ActivityLogSettingController.php
```

### Commands:
```
app/Console/Commands/
├── ArchiveActivityLogs.php
└── CleanupActivityLogs.php
```

### Observers:
```
app/Observers/
└── ModelActivityObserver.php (auto-log create/update/delete)
```

### Views:
```
resources/views/admin/
├── roles/ (index, create, edit, show)
├── permissions/ (index, create, edit, show)
├── users/ (index, create, edit, show)
├── activities/
│   ├── index.blade.php (activity log listing)
│   ├── index-gridjs.blade.php (activity log listing dengan Grid.js)
│   ├── show.blade.php (activity detail)
│   ├── management.blade.php (archive/cleanup dashboard)
│   └── archives.blade.php (view archived logs)
└── settings/
    └── activity-log.blade.php (tracking settings)
```

### Config:
```
config/
├── activity-logger.php (models to track)
├── permission.php (Spatie config)
└── auth.php
```

### Migrations:
```
database/migrations/
├── 2025_11_26_223630_create_permission_tables.php (Spatie)
├── 2025_11_26_221846_create_activity_log_table.php (Spatie)
├── 2025_11_26_224323_create_activity_log_settings_table.php
└── 2025_11_26_225837_create_activity_log_archives_table.php
```

---

## Routes

### Role Routes:
```
GET    /admin/roles                 - List roles (admin.roles.index)
GET    /admin/roles/create          - Create form (admin.roles.create)
POST   /admin/roles                 - Store (admin.roles.store)
GET    /admin/roles/{role}          - Show detail (admin.roles.show)
GET    /admin/roles/{role}/edit     - Edit form (admin.roles.edit)
PUT    /admin/roles/{role}          - Update (admin.roles.update)
DELETE /admin/roles/{role}          - Delete (admin.roles.destroy)
```

### Permission Routes:
```
GET    /admin/permissions                  - List (admin.permissions.index)
GET    /admin/permissions/create           - Create form (admin.permissions.create)
POST   /admin/permissions                  - Store (admin.permissions.store)
GET    /admin/permissions/{permission}     - Show (admin.permissions.show)
GET    /admin/permissions/{permission}/edit- Edit form (admin.permissions.edit)
PUT    /admin/permissions/{permission}     - Update (admin.permissions.update)
DELETE /admin/permissions/{permission}     - Delete (admin.permissions.destroy)
```

### User Routes:
```
GET    /admin/users                 - List (admin.users.index)
GET    /admin/users/create          - Create form (admin.users.create)
POST   /admin/users                 - Store (admin.users.store)
GET    /admin/users/{user}          - Show (admin.users.show)
GET    /admin/users/{user}/edit     - Edit form (admin.users.edit)
PUT    /admin/users/{user}          - Update (admin.users.update)
DELETE /admin/users/{user}          - Delete (admin.users.destroy)
POST   /admin/users/{user}/reset-password - Reset password (admin.users.reset-password)
GET    /admin/users-online-data     - Online users JSON (admin.users.online-data)
```

### Activity Routes:
```
GET    /admin/activities                          - List (admin.activities.index)
GET    /admin/activities-data                     - Data API (admin.activities.data)
GET    /admin/activities/{activity}               - Detail (admin.activities.show)
GET    /admin/activities/management               - Archive dashboard (admin.activities.management)
POST   /admin/activities/archive                  - Archive action (admin.activities.archive)
POST   /admin/activities/cleanup                  - Cleanup action (admin.activities.cleanup)
POST   /admin/activities/truncate                 - Truncate action (admin.activities.truncate)
GET    /admin/activities-archives                 - View archives (admin.activities.archives)
DELETE /admin/activities-archives/{archive}       - Delete archive (admin.activities.archives-destroy)
POST   /admin/activities-archives/bulk-delete     - Bulk delete archives (admin.activities.archives-bulk-delete)
POST   /admin/activities-archives/bulk-restore    - Bulk restore archives (admin.activities.archives-bulk-restore)
GET    /admin/activities-archives-export          - Export archives (admin.activities.archives-export)
GET    /admin/activities-export                   - Export activities (admin.activities.export)
POST   /admin/activities-archives/{archive}/restore - Restore (admin.activities.restore-archive)
```

### Settings Routes:
GET    /admin/users-data            - Data API (admin.users.data)
```
GET    /admin/settings/activity-log          - View settings (admin.settings.activity-log.index)
POST   /admin/settings/activity-log          - Update settings (admin.settings.activity-log.update)
POST   /admin/settings/activity-log/{id}/toggle - Toggle (admin.settings.activity-log.toggle)
```

GET    /admin/users-export          - Export users (admin.users.export)
---

## Database Schema

### Tables Utama:

#### users
```sql
- id
- name
- email
- email_verified_at
- password
- password_reset_token
- last_seen_at (timestamp, nullable, indexed) -- tracking user activity presence
- created_at, updated_at
```

#### roles (Spatie)
```sql
- id
- name (unique)
- description
- created_at, updated_at
```

#### permissions (Spatie)
```sql
- id
- name (unique)
- description
- created_at, updated_at
```

#### model_has_roles (Spatie)
```sql
- role_id
- model_type
- model_id
```

#### model_has_permissions (Spatie)
```sql
- permission_id
- model_type
- model_id
```

#### role_has_permissions (Spatie)
```sql
- permission_id
- role_id
```

#### activities (Spatie Activity Log)
```sql
- id
- log_name
- description
- subject_type
- subject_id
- event
- causer_type
- causer_id
- properties (JSON)
- batch_uuid
- created_at
- updated_at
```

#### activity_log_settings (Custom)
```sql
- id
- model_class (unique)
- enabled (boolean)
- tracked_attributes (JSON)
- created_at, updated_at
```

#### activity_log_archives (Custom)
```sql
- id
- activity_id (unique)
- log_type (manual/scheduled)
- activity_data (JSON)
- archived_at (timestamp)
- created_at, updated_at
```

---

## Activity Logging Flow

### 1. Manual Create/Update/Delete:
```
User Action (UI) 
  ↓
Controller (store/update/destroy)
  ↓
Model::create() / update() / delete()
  ↓
ModelActivityObserver catches event
  ↓
activity()->log() automatically
  ↓
Stored in activities table
```

### 2. Profile Updates (Non-Admin):
```
User Updates Profile
  ↓
ProfileController@update
  ↓
User::update()
  ↓
LogsActivity trait + Observer
  ↓
Logged to activities table
```

### 3. Archive Flow:
```
Admin archives logs (>90 days)
  ↓
ActivityController@archive()
  ↓
Loop through old activities
  ↓
Create entry in activity_log_archives
  ↓
Delete from activities table
  ↓
Mark as 'manual' type
```

### 4. Scheduled Archive (Optional):
```
Scheduler runs: php artisan logs:archive
  ↓
ArchiveActivityLogs command
  ↓
Same as manual but marked as 'scheduled'
  ↓
Can be automated in Kernel
```

---

## Bug Fixes & Improvements

### ✅ Fixed Issues:

1. **Route Ordering** - `activities/management` now comes before `{activity}` wildcard
2. **View Layout** - Changed from `layouts.app` to `layouts.admin` for consistency
3. **Permission Sync** - Changed from ID-based to name-based sync
4. **Duplicate Logging** - Removed manual logging from store/update/destroy (observer handles it)
5. **Role Check** - Changed from `$role->permissions->contains($permission->id)` to `contains('name', $permission->name)`

### 📝 Notes:
- All admin routes protected by `IsAdmin` middleware
- Super-admin role cannot be deleted
- Current user cannot delete themselves
- Password resets are logged
- All datetime displayed in Indonesian locale

---

## Testing Checklist

- [x] Syntax validation (PHP -l)
- [x] Database migrations (executed)
- [x] Routes registered
- [x] Create role with permissions
- [x] Update role and permissions
- [x] Delete role (except super-admin)
- [x] Create permission
- [x] Update permission
- [x] Delete permission
- [x] Create user with roles
- [x] Update user roles
- [x] Delete user
- [x] Reset user password
- [x] Activity logs created automatically
- [x] Archive logs
- [x] View archived logs
- [x] Restore from archive
- [x] Cleanup old logs
- [x] No duplicate logs on CRUD

---

## How to Update This Document

Setiap kali ada perubahan fitur:

1. Edit file ini: `FITUR_DAN_DOKUMENTASI.md`
2. Update bagian yang relevan
3. Update tanggal di bagian atas
4. Commit dengan clear message

**Format Commit Message:**
```
feat: [Bagian yang diubah]
- Perubahan detail
- Perubahan detail

docs: update FITUR_DAN_DOKUMENTASI.md
```

---

## Kesimpulan

Sistem admin panel ini menyediakan:
- ✅ Complete RBAC dengan Spatie
- ✅ Full activity logging & audit trail
- ✅ Archive & cleanup untuk performance
- ✅ DB-driven configuration
- ✅ Automatic observer-based logging
- ✅ Responsive Bootstrap UI
- ✅ Online users presence tracking
- ✅ Modern landing page publik
- ✅ Indonesian language support

Siap untuk production! 🚀

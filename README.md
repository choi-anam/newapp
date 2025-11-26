# Panduan Instalasi Aplikasi Laravel

Dokumentasi ini menjelaskan langkah-langkah untuk menginstal dan menjalankan aplikasi Laravel ini setelah melakukan clone dari repository.

## Prasyarat

Sebelum memulai instalasi, pastikan sistem Anda sudah memiliki:

- **PHP** versi 8.2 atau lebih tinggi
- **Composer** (PHP Package Manager)
- **Node.js** dan **npm** (untuk asset frontend)
- **SQLite** atau database lainnya (sudah termasuk di PHP)
- **Git** (untuk clone repository)

### Instalasi Prasyarat

#### Windows (XAMPP)
1. Download dan install [XAMPP](https://www.apachefriends.org/) dengan PHP 8.2 atau lebih tinggi
2. Download dan install [Composer](https://getcomposer.org/download/)
3. Download dan install [Node.js](https://nodejs.org/) (LTS recommended)
4. Download dan install [Git](https://git-scm.com/download/win)

#### Mac/Linux
```bash
# Gunakan package manager sesuai OS Anda
# Contoh untuk Mac (menggunakan Homebrew):
brew install php composer node git

# Untuk Linux (Ubuntu/Debian):
sudo apt-get install php php-cli php-mbstring php-xml php-curl composer nodejs git
```

## Langkah-Langkah Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd newapp
```

### 2. Copy File Environment

```bash
cp .env.example .env
```

Atau jika menggunakan Windows Command Prompt:
```bash
copy .env.example .env
```

### 3. Install Dependency PHP dengan Composer

```bash
composer install
```

Proses ini akan mengunduh dan menginstal semua package PHP yang diperlukan (Laravel, Spatie Permission, dll).

### 4. Generate Application Key

```bash
php artisan key:generate
```

Perintah ini akan membuat `APP_KEY` di file `.env` yang digunakan untuk enkripsi.

### 5. Setup Database

#### Menggunakan SQLite (Default)

Database SQLite sudah dikonfigurasi sebagai default. Buat file database:

```bash
touch database/database.sqlite
```

Atau untuk Windows:
```bash
type nul > database/database.sqlite
```

#### Menggunakan MySQL

Jika ingin menggunakan MySQL, edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=password_anda
```

Buat database di MySQL:
```sql
CREATE DATABASE nama_database;
```

### 6. Migration Database

Jalankan migration untuk membuat tabel-tabel yang diperlukan:

```bash
php artisan migrate
```

Jika ingin melakukan migrate dengan seed data:

```bash
php artisan migrate --seed
```

### 7. Install Node Dependencies

```bash
npm install
```

### 8. Build Assets Frontend

```bash
npm run build
```

Atau untuk mode development dengan hot reload:

```bash
npm run dev
```

## Quick Setup (Otomatis)

Anda bisa menjalankan semua setup sekaligus menggunakan composer script:

```bash
composer run-script setup
```

Script ini akan melakukan:
- Install PHP dependencies
- Copy `.env.example` ke `.env` (jika belum ada)
- Generate application key
- Run migrations
- Install Node dependencies
- Build frontend assets

## Menjalankan Aplikasi

### Development Mode (Recommended)

Untuk menjalankan aplikasi dalam mode development dengan fitur lengkap:

```bash
composer run-script dev
```

Perintah ini akan menjalankan:
- PHP development server (`php artisan serve`)
- Queue listener
- Real-time logs (`php artisan pail`)
- Vite dev server dengan hot reload

Aplikasi akan dapat diakses di: **http://localhost:8000**

### Akses Manual

Jika ingin menjalankan server secara terpisah:

```bash
# Terminal 1 - PHP Server
php artisan serve

# Terminal 2 - Frontend Assets (Hot Reload)
npm run dev

# Terminal 3 (Optional) - Queue Listener
php artisan queue:listen

# Terminal 4 (Optional) - Real-time Logs
php artisan pail
```

## File Konfigurasi Penting

- `.env` - Konfigurasi aplikasi (database, mail, api key, dll)
- `config/app.php` - Konfigurasi aplikasi
- `config/auth.php` - Konfigurasi autentikasi
- `config/database.php` - Konfigurasi database
- `config/permission.php` - Konfigurasi permission (Spatie)

## Struktur Folder

```
├── app/                    # Kode aplikasi
│   ├── Http/              # Controllers, Requests
│   ├── Models/            # Model Eloquent
│   └── Providers/         # Service Providers
├── database/              # Migration & Seeder
├── public/                # File publik (assets, index.php)
├── resources/             # Views, CSS, JavaScript
├── routes/                # Route definitions
├── storage/               # File storage & logs
├── tests/                 # Testing
└── vendor/                # Composer dependencies
```

## Troubleshooting

### Error: "No application encryption key has been specified"

Jalankan:
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000]: General error: 1 disk image is malformed"

Database SQLite corrupt. Buat ulang:
```bash
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate
```

### Error: "Class not found"

Clear cache dan autoloader:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Port 8000 Sudah Digunakan

Gunakan port berbeda:
```bash
php artisan serve --port=8001
```

### Node modules tidak terinstall

Jalankan ulang:
```bash
npm install
npm run build
```

## Testing

Untuk menjalankan test:

```bash
composer test
```

Atau menggunakan PHPUnit langsung:

```bash
php artisan test
```

## Debugging

### Mengaktifkan Debug Mode

Edit `.env`:
```env
APP_DEBUG=true
```

### Menggunakan Laravel Tinker

Akses interactive shell:
```bash
php artisan tinker
```

### Melihat Real-time Logs

```bash
php artisan pail
```

## Fitur Aplikasi

- ✅ **Laravel 12** - Framework PHP modern
- ✅ **Tailwind CSS** - Styling dengan utility-first CSS framework
- ✅ **Alpine.js** - Lightweight JavaScript framework
- ✅ **Spatie Permission** - Role-based access control (RBAC)
- ✅ **Laravel Breeze** - Authentication scaffolding
- ✅ **Vite** - Modern build tool untuk frontend assets

## Development Tools

Aplikasi ini menggunakan beberapa development tools:

- **Laravel Pint** - PHP code style fixer
- **PHPUnit** - Unit testing framework
- **Laravel Sail** - Docker-based development environment
- **Laravel Pail** - Real-time log viewer

### Format Code dengan Pint

```bash
composer pint
```

## Production Deployment

Untuk deployment ke production:

1. Set `.env` mode:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize aplikasi:
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Build frontend assets:
```bash
npm run build
```

4. Jalankan migrations (jika ada update):
```bash
php artisan migrate --force
```

## Support & Dokumentasi

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Breeze](https://laravel.com/docs/authentication#laravel-breeze)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)
- [Tailwind CSS](https://tailwindcss.com/docs)

## License

Aplikasi ini menggunakan lisensi MIT. Lihat file LICENSE untuk detail lebih lanjut.

---

**Terakhir diupdate:** November 2025

Jika ada pertanyaan atau masalah, silakan buat issue di repository ini.

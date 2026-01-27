# Panduan Setup IT Help Desk Application

## Langkah-langkah Instalasi

## Jalankan Dengan Docker Compose (MySQL + phpMyAdmin)

1. Salin file environment Docker:

```powershell
Copy-Item .env.docker.example .env.docker
```

2. (Opsional) Isi `APP_KEY` di `.env.docker`. Jika dibiarkan kosong, container akan generate otomatis saat start.

3. Jalankan semua service:

```powershell
docker compose --env-file .env.docker up -d --build
```

4. Akses layanan:
- Aplikasi: `http://localhost:8010` (ubah lewat `APP_PORT` di `.env.docker`)
- phpMyAdmin: `http://localhost:8081` (ubah lewat `PHPMYADMIN_PORT` di `.env.docker`)

5. Login phpMyAdmin:
- Secara default server sudah dikunci ke `db` (bukan `localhost`), jadi cukup login dengan:
  - Username: sesuai `MYSQL_USER`
  - Password: sesuai `MYSQL_PASSWORD`

Jika sebelumnya Anda mengisi Server = `localhost` dan muncul error `HY000/2002 (No such file or directory)`, itu karena phpMyAdmin mencoba konek via MySQL socket (bukan TCP). Gunakan host `db` (service name di Docker network) atau biarkan konfigurasi default.

### 1. Install Dependencies

Buka terminal PowerShell di folder project, lalu jalankan:

```powershell
composer install
npm install
```

### 2. Setup Database

Aplikasi ini menggunakan **SQLite** yang tidak memerlukan setup database terpisah. File database akan otomatis dibuat pada saat migrasi.

### 3. Konfigurasi Environment

File `.env` sudah dikonfigurasi dengan SQLite sebagai default database:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database.sqlite
```

Jika perlu mengubah kembali ke MySQL, ubah konfigurasi menjadi:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=it_helpdesk
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan `APP_KEY` sudah terisi. Jika belum, generate dengan:

```powershell
php artisan key:generate
```

### 4. Jalankan Migrasi dan Seeder

```powershell
php artisan migrate --seed
```

Perintah ini akan:
- Membuat file database SQLite (`database.sqlite`)
- Membuat semua tabel yang diperlukan
- Mengisi data awal (users dan categories)

Jika Anda ingin reset database (menghapus semua data):

```powershell
php artisan migrate:fresh --seed
```

### 5. Build Assets Frontend

```powershell
npm run build
```

Untuk development dengan auto-reload:

```powershell
npm run dev
```

### 6. Set Permission Storage (Jika diperlukan di Linux/Mac)

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 7. Jalankan Aplikasi

```powershell
php artisan serve
```

Aplikasi akan berjalan di: **http://localhost:8000**

## Akun Login Default

### Admin
- Email: `admin@helpdesk.com`
- Password: `password`
- Akses: Full access ke semua fitur

### Teknisi
- Email: `tech1@helpdesk.com` atau `tech2@helpdesk.com`
- Password: `password`
- Akses: Dapat mengelola tiket yang ditugaskan

### User
- Email: `user@helpdesk.com`
- Password: `password`
- Akses: Dapat membuat dan melihat tiket sendiri

## Troubleshooting

### Error: Database file not found
- File `database.sqlite` akan dibuat otomatis saat migrasi pertama kali
- Pastikan folder `database/` memiliki write permission
- Jalankan: `php artisan migrate --seed`

### Error: Class not found
- Jalankan: `composer dump-autoload`
- Jalankan: `php artisan config:clear`

### Error: npm command not found
- Install Node.js dari https://nodejs.org/
- Restart terminal setelah instalasi

### Assets tidak muncul
- Jalankan: `npm run build`
- Clear browser cache

### Error 500 Internal Server Error
- Cek file `storage/logs/laravel.log` untuk detail error
- Pastikan folder storage dan bootstrap/cache writable
- Jalankan: `php artisan config:clear` dan `php artisan cache:clear`

## Fitur Aplikasi

### User (Pengguna Biasa)
✅ Registrasi akun baru
✅ Login/Logout
✅ Lihat dashboard pribadi
✅ Buat tiket baru
✅ Lihat daftar tiket sendiri
✅ Lihat detail tiket
✅ Tambah response/tanggapan pada tiket

### Technician (Teknisi)
✅ Semua fitur User
✅ Lihat semua tiket yang belum ditugaskan
✅ Ambil tiket (assign to self)
✅ Update status tiket (open, in progress, resolved, closed)
✅ Update prioritas tiket
✅ Tambah catatan internal (tidak terlihat user)

### Admin (Administrator)
✅ Semua fitur Technician
✅ Lihat statistik lengkap sistem
✅ Kelola pengguna (tambah, edit, hapus)
✅ Kelola kategori tiket
✅ Tugaskan tiket ke teknisi tertentu
✅ Akses ke semua tiket

## Struktur Database

File database SQLite disimpan di: `database/database.sqlite`

### Tables
- `users` - Data pengguna (admin, technician, user)
- `categories` - Kategori tiket (Hardware, Software, Network, dll)
- `tickets` - Data tiket
- `ticket_responses` - Tanggapan/komentar pada tiket
- `ticket_attachments` - Lampiran file (prepared for future)
- `sessions` - Session management
- `cache` - Cache management
- `password_reset_tokens` - Reset password tokens

## Kategori Default

Setelah seeding, kategori berikut akan tersedia:
- 💻 Hardware - Masalah perangkat keras
- 📱 Software - Masalah aplikasi/software
- 🌐 Network - Masalah jaringan/internet
- 📧 Email - Masalah email
- 🗄️ Database - Masalah database
- 🔒 Security - Masalah keamanan
- 👤 Account - Masalah akun pengguna
- 📋 Other - Lainnya

## Workflow Tiket

1. **User** membuat tiket baru dengan kategori dan prioritas
2. Tiket berstatus "Open" dan belum ditugaskan
3. **Teknisi** atau **Admin** dapat mengambil tiket atau Admin menugaskan ke teknisi tertentu
4. **Teknisi** mengubah status menjadi "In Progress" dan mulai menangani
5. **Teknisi** dan **User** dapat berkomunikasi melalui responses
6. Setelah selesai, **Teknisi** mengubah status menjadi "Resolved"
7. **Admin** atau **Teknisi** dapat menutup tiket dengan status "Closed"

## Perintah Artisan Berguna

```powershell
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recreate database (HATI-HATI: Menghapus semua data!)
php artisan migrate:fresh --seed

# Jalankan specific seeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=CategorySeeder

# Lihat routes
php artisan route:list

# Generate APP_KEY baru
php artisan key:generate

# Check database connection
php artisan tinker
```

## Tips Penggunaan

1. **Gunakan prioritas dengan bijak:**
   - Low: Masalah minor, tidak urgent
   - Medium: Masalah standar, perlu perhatian
   - High: Masalah serius, perlu segera ditangani
   - Critical: Sistem down, butuh penanganan immediate

2. **Komunikasi efektif:**
   - Berikan deskripsi lengkap saat membuat tiket
   - Gunakan responses untuk update progress
   - Teknisi dapat membuat catatan internal untuk koordinasi tim

3. **Manajemen kategori:**
   - Admin dapat menambah kategori sesuai kebutuhan
   - Gunakan emoji/icon untuk visual yang lebih baik

## Support

Jika ada pertanyaan atau masalah:
1. Cek file `storage/logs/laravel.log` untuk error details
2. Pastikan semua requirement terpenuhi
3. Ikuti troubleshooting guide di atas

## Update & Maintenance

```powershell
# Update dependencies
composer update
npm update

# Backup database SQLite
Copy-Item -Path "database/database.sqlite" -Destination "database/database.sqlite.backup"
```

---

**Selamat menggunakan IT Help Desk Application!** 🎫✨

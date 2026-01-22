# Pengelolaan Notifikasi

## Kebijakan Retention Notifikasi

Aplikasi IT Help Desk memiliki kebijakan retention otomatis untuk notifikasi:

### Dropdown Notifikasi (Header)
- **Periode tampil**: 7 hari terakhir
- **Jumlah maksimal**: 5 notifikasi terbaru
- **Tujuan**: Menampilkan notifikasi yang paling relevan dan terkini

### Halaman Notifikasi
- **Periode tampil**: 30 hari terakhir (sebelum auto-delete)
- **Jumlah**: Semua notifikasi dengan pagination
- **Tujuan**: History lengkap notifikasi user

### Auto-Delete
- **Periode**: Notifikasi > 30 hari otomatis dihapus
- **Jadwal**: Setiap hari jam 02:00 pagi
- **Command manual**: `php artisan notifications:clean`

## Command Manual

### Menghapus Notifikasi Lama

Hapus notifikasi yang lebih tua dari 30 hari (default):
```bash
php artisan notifications:clean
```

Hapus notifikasi dengan periode custom (contoh: 60 hari):
```bash
php artisan notifications:clean --days=60
```

Hapus notifikasi yang lebih tua dari 7 hari:
```bash
php artisan notifications:clean --days=7
```

## Mengaktifkan Task Scheduler

Untuk menjalankan auto-delete otomatis, tambahkan cron job berikut:

**Linux/Mac**:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

**Windows (Task Scheduler)**:
- Program: `php`
- Arguments: `artisan schedule:run`
- Start in: `E:\PRibadi\Mufid\my Project\IT Help Desk`
- Trigger: Setiap 1 menit

## Kustomisasi

### Mengubah Periode Dropdown

Edit `resources/views/layouts/app.blade.php` baris ~133:
```php
// Ubah dari 7 hari ke periode lain
->where('created_at', '>=', now()->subDays(7))
```

### Mengubah Periode Auto-Delete

Edit `routes/console.php` baris ~15:
```php
// Ubah dari 30 hari ke periode lain
->where('created_at', '<', now()->subDays(30))
```

### Mengubah Jumlah Notifikasi di Dropdown

Edit `resources/views/layouts/app.blade.php` baris ~137:
```php
// Ubah dari 5 ke jumlah lain
->take(5)
```

## Best Practices

1. **Jangan hapus notifikasi terlalu cepat** - Minimal 7 hari untuk user bisa tracking
2. **Auto-delete harus lebih lama dari periode dropdown** - Misal dropdown 7 hari, auto-delete 30 hari
3. **Jalankan manual clean sebelum production** - Test dulu dengan `--days=` yang sesuai
4. **Monitor storage** - Jika notifikasi banyak, pertimbangkan kurangi periode retention

# Cara Menjalankan Dummy Data

## Quick Start

Untuk membuat dummy data lengkap (users, categories, tickets, dan notifications), jalankan:

```bash
php artisan migrate:fresh --seed
```

✅ **Hasilnya:**
- 🗑️ Database kosongkan dan dimulai dari 0
- 📊 Semua migrations dijalankan (users, categories, tickets, notifications table)
- 👥 8 users dibuat dengan berbagai roles
- 📁 Categories dibuat
- 🎫 20 tickets dibuat dengan data yang bervariasi
- 🔔 50+ notifications dibuat otomatis

⚠️ **PERHATIAN**: Command ini akan **menghapus semua data** yang ada dan membuat ulang dari awal!

## Dummy Data yang Dibuat

### 👥 Users (8 users)

1. **Administrator**
   - Email: `admin@helpdesk.com`
   - Password: `password`
   - Role: Admin
   - Department: IT

2. **General Manager**
   - Email: `gm@helpdesk.com`
   - Password: `password`
   - Role: General Manager
   - Department: Management

3. **Teknisi 1**
   - Email: `tech1@helpdesk.com`
   - Password: `password`
   - Role: Technician
   - Department: IT Support

4. **Teknisi 2**
   - Email: `tech2@helpdesk.com`
   - Password: `password`
   - Role: Technician
   - Department: IT Support

5. **User Demo**
   - Email: `user@helpdesk.com`
   - Password: `password`
   - Role: User
   - Department: Finance

6. **John Doe**
   - Email: `john@helpdesk.com`
   - Password: `password`
   - Role: User
   - Department: Marketing

7. **Jane Smith**
   - Email: `jane@helpdesk.com`
   - Password: `password`
   - Role: User
   - Department: HR

8. **Bob Wilson**
   - Email: `bob@helpdesk.com`
   - Password: `password`
   - Role: User
   - Department: Sales

### 📋 Categories

Kategori standar IT Help Desk (dari CategorySeeder):
- Hardware
- Software
- Network
- Email
- dll.

### 🎫 Tickets (20 tickets)

Dengan variasi:
- ✅ Status: Open, In Progress, Resolved, Closed
- ⚡ Priority: Low, Medium, High, Critical
- 👤 Assigned: Beberapa ticket sudah assigned ke technician
- 💬 Responses: Beberapa ticket memiliki responses
- 📅 Dates: Random dates dalam 20 hari terakhir

Contoh tickets:
1. Laptop tidak bisa nyala
2. Printer tidak bisa print
3. Internet lemot
4. Email tidak bisa dikirim
5. Komputer restart sendiri
... dan 15 lainnya

### 🔔 Notifications (~50-70 notifications)

Notifikasi otomatis dibuat untuk:
- ✨ Tiket baru (untuk admin, GM, dan technicians)
- 📌 Assignment tiket (untuk technician dan ticket owner)
- 🔄 Status update (untuk ticket owner)
- 💬 Response baru (untuk ticket owner)

## Seeder Terpisah

### Jalankan hanya user seeder:
```bash
php artisan db:seed --class=UserSeeder
```

### Jalankan hanya category seeder:
```bash
php artisan db:seed --class=CategorySeeder
```

### Jalankan hanya ticket seeder:
```bash
php artisan db:seed --class=TicketSeeder
```

## Reset Data Tanpa Migrasi

Jika ingin reset data tanpa mengubah struktur database:

```bash
# Truncate semua table
php artisan db:wipe

# Jalankan seeder
php artisan db:seed
```

## Testing Akun

Untuk testing, gunakan akun-akun berikut:

### Admin Testing
```
Email: admin@helpdesk.com
Password: password
```

### Technician Testing
```
Email: tech1@helpdesk.com
Password: password
```

### User Testing
```
Email: user@helpdesk.com
Password: password
```

## Tambah Data Custom

Jika ingin menambah lebih banyak tickets, edit file:
```
database/seeders/TicketSeeder.php
```

Ubah loop counter dari `20` ke jumlah yang diinginkan:
```php
for ($i = 1; $i <= 50; $i++) {  // Ubah 20 ke 50
    // ...
}
```

## Tips

1. **Fresh Install**: Gunakan `migrate:fresh --seed` untuk development
2. **Production**: JANGAN gunakan `migrate:fresh` di production!
3. **Testing**: Buat seeder terpisah untuk test data
4. **Backup**: Selalu backup database sebelum reset

## Troubleshooting

### Error: "Route [tickets.show] not defined"
Pastikan web.php sudah ada route untuk tickets:
```bash
php artisan route:list | grep tickets
```

### Error: "Class TicketSeeder not found"
Jalankan autoload dump:
```bash
composer dump-autoload
```

### Notifikasi tidak muncul
Cek apakah migration notifications sudah dijalankan:
```bash
php artisan migrate:status
```

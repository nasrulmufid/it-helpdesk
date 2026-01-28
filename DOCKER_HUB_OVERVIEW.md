# IT Help Desk System - Docker Image (v2.2.0)

Image resmi untuk IT Help Desk System, sebuah aplikasi manajemen tiket berbasis Laravel 11. Image ini dioptimalkan untuk performa dan keamanan dalam lingkungan kontainer.

## 🚀 Quick Start (Docker Compose)

Cara termudah untuk menjalankan aplikasi ini adalah menggunakan Docker Compose dengan tiga layanan terintegrasi:

```yaml
version: '3.8'
services:
  app:
    image: nasrulmufid/it-help-desk:v2.2.0
    ports:
      - "8000:80"
    environment:
      - DB_HOST=mysql
      - DB_DATABASE=it_helpdesk
      - DB_USERNAME=helpdesk_user
      - DB_PASSWORD=helpdesk_pass_2024
    depends_on:
      - mysql

  mysql:
    image: mysql:8.0.35
    environment:
      - MYSQL_DATABASE=it_helpdesk
      - MYSQL_USER=helpdesk_user
      - MYSQL_PASSWORD=helpdesk_pass_2024
      - MYSQL_ROOT_PASSWORD=root_pass_secure

  phpmyadmin:
    image: phpmyadmin:5.2.1
    ports:
      - "8080:80"
    environment:
      - PMA_HOST=mysql
```

## ⚙️ Environment Variables

### Konfigurasi Database
- `DB_CONNECTION`: Default `mysql`
- `DB_HOST`: Alamat host database
- `DB_PORT`: Default `3306`
- `DB_DATABASE`: Nama database
- `DB_USERNAME`: Username database
- `DB_PASSWORD`: Password database

### Konfigurasi Aplikasi
- `APP_NAME`: Nama aplikasi
- `APP_ENV`: `production` atau `local`
- `APP_KEY`: Kunci enkripsi Laravel
- `APP_DEBUG`: `true` atau `false`

## 📊 Layanan Terintegrasi

1. **Aplikasi (Port 8000)**: Interface utama IT Help Desk.
2. **phpMyAdmin (Port 8080)**: Manajemen database MySQL melalui web browser.
3. **MySQL (Port 3306)**: Penyimpanan data relasional dengan volume persisten.

## 💾 Persistensi Data

Pastikan untuk menggunakan volume untuk menjaga data tetap aman:
- Database: `/var/lib/mysql`
- Application Storage: `/var/www/html/storage`

## 🛠️ Maintenance

Untuk menjalankan migrasi database di dalam container:
```bash
docker exec -it it-helpdesk-app php artisan migrate --force
```

---
**Maintained by [Nasrul Mufid](https://github.com/nasrulmufid)**

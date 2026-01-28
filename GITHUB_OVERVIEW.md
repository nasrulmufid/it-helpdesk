# IT Help Desk System v2.2.0

![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)

Sistem manajemen tiket dukungan IT yang modern, efisien, dan mudah digunakan. Dibangun dengan Laravel 11 dan sekarang mendukung deployment penuh dengan Docker (Multi-Service).

## 🌟 Fitur Utama

- **Dashboard Multi-Role**: Admin, Teknisi, dan User.
- **Manajemen Tiket**: Pembuatan, pelacakan, dan penyelesaian tiket secara real-time.
- **Notifikasi**: Sistem notifikasi aktivitas tiket.
- **Laporan & Statistik**: Visualisasi data performa dan status tiket.
- **Docker Ready**: Deployment instan dengan MySQL dan phpMyAdmin terintegrasi.

## 🚀 Rilis v2.2.0: Docker Multi-Service

Versi ini memperkenalkan arsitektur kontainer yang lebih tangguh:
1. **Aplikasi Utama**: PHP 8.2-Apache dengan optimasi Laravel.
2. **Database**: MySQL 8.0 dengan volume persisten.
3. **Manajemen DB**: phpMyAdmin 5.2.1 untuk akses database yang mudah.

## 🛠️ Cara Memulai

### Prasyarat
- Docker dan Docker Compose terinstal di sistem Anda.

### Instalasi Cepat
1. Clone repository:
   ```bash
   git clone https://github.com/nasrulmufid/it-help-desk.git
   cd it-help-desk
   ```
2. Setup environment:
   ```bash
   cp .env.docker .env
   ```
3. Jalankan dengan Docker Compose:
   ```bash
   docker-compose up -d
   ```
4. Akses aplikasi di: `http://localhost:8000`

## 📁 Struktur Proyek

- `app/`: Logika inti aplikasi (Models, Controllers, Middleware).
- `docker/`: Konfigurasi spesifik untuk layanan Docker.
- `resources/views/`: Template antarmuka (Blade).
- `routes/`: Definisi endpoint aplikasi.
- `scripts/`: Skrip pembantu untuk deployment dan maintenance.

## 🤝 Kontribusi

Kami menerima kontribusi dalam bentuk Pull Request atau pelaporan Issue. Silakan lihat panduan kontribusi untuk informasi lebih lanjut.

---
**Dibuat dengan ❤️ oleh [Nasrul Mufid](https://github.com/nasrulmufid)**

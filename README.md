# 🚀 IT Help Desk - Ticketing System

**Version**: 2.2.0 (January 2026)  
**Status**: Production Ready  
**Architecture**: Docker Multi-Service (App, MySQL, phpMyAdmin)  
**Framework**: Laravel 11  
**PHP Version**: 8.2+  

---

## 📦 Project Overview

IT Help Desk adalah sistem ticketing dukungan IT yang modern dan efisien. Versi v2.2.0 menghadirkan peningkatan signifikan dengan arsitektur **Multi-Service Docker**, migrasi ke **MySQL 8.0**, dan integrasi **phpMyAdmin** untuk manajemen database yang lebih baik.

---

## ✨ Fitur Baru v2.2.0

- **🐳 Multi-Service Architecture**: Orchestration menggunakan Docker Compose dengan 3 layanan terpisah.
- **🗄️ MySQL 8.0 Integration**: Performa database yang lebih tangguh menggantikan SQLite.
- **🛠️ phpMyAdmin Integrated**: Interface web untuk manajemen database langsung dari container.
- **🏥 Service Health Checks**: Monitoring otomatis kesehatan layanan aplikasi dan database.
- **💾 Persistent Volumes**: Data database dan storage tetap aman meskipun container di-restart.
- **🚀 Optimized Dockerfile**: Image yang lebih kecil dan proses build yang lebih cepat.

---

## 🔐 Core Features

### 🔐 Authentication & Role Management
```
3 User Roles:
├── Admin → Full system access & configurations
├── Technician → Ticket management & support responses
└── User → Create, track & manage own tickets
```

### 🎫 Ticket Management System
```
Workflow:
CREATE → ASSIGN → IN PROGRESS → RESOLVED → CLOSED

Attributes:
├── Categories: Hardware, Software, Network, Email, Database, Security, Account, Other
├── Priority: Low, Medium, High, Critical
├── Status: Open, In Progress, Resolved, Closed
├── Attachments: Support file uploads (Images, Docs)
└── Responses: Real-time communication channel
```

---

## � Quick Start (Recommended)

### 🐳 Docker Deployment
Ini adalah cara termudah dan paling stabil untuk menjalankan IT Help Desk v2.2.0.

1. **Persiapkan Environment**:
   ```bash
   cp .env.docker .env
   ```

2. **Jalankan Layanan**:
   ```bash
   docker-compose up -d
   ```

3. **Akses Layanan**:
   - **Aplikasi**: [http://localhost:8000](http://localhost:8000)
   - **phpMyAdmin**: [http://localhost:8080](http://localhost:8080)
   - **MySQL Host**: `localhost:3306` (Internal: `mysql`)

---

## 🐳 Docker Deployment

### Opsi 1: Multi-Service (Recommended)
Menggunakan Docker Compose untuk memisahkan aplikasi, database, dan phpMyAdmin.
```bash
docker-compose up -d
```

### Opsi 2: Monolithic Image (All-in-One)
Satu container yang berisi Apache, PHP, MySQL, dan phpMyAdmin. Cocok untuk testing cepat.
```bash
# Build monolithic image
docker build -t it-helpdesk-monolithic -f Dockerfile.monolithic .

# Jalankan container
docker run -d -p 8000:80 -p 3306:3306 --name it-helpdesk-monolithic it-helpdesk-monolithic
```

---

## 🏗️ CI/CD Pipeline

Proyek ini menggunakan **GitHub Actions** untuk otomatisasi build dan deployment:
1. **Trigger**: Setiap `push` ke branch `main` atau `master`, serta pembuatan `tag` rilis (v*).
2. **Stages**:
   - **Test**: Menjalankan unit testing Laravel.
   - **Build**: Membangun Docker image monolithic berbasis Ubuntu 22.04.
   - **Push**: Mengunggah image ke Docker Hub (`nasrulmufid/it-help-desk-monolithic`).
3. **Caching**: Menggunakan GitHub Actions cache untuk mempercepat build berulang.
4. **Tagging**: Otomatis menggunakan Semantic Versioning (SemVer) dan commit hash.

---

## 📊 Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| **Backend Framework** | Laravel | 11.x |
| **Language** | PHP | 8.2-apache |
| **Database** | MySQL | 8.0.35 |
| **DB Management** | phpMyAdmin | 5.2.1 |
| **Frontend** | Blade + Tailwind CSS | Latest |
| **Build Tool** | Vite | Latest |
| **Container** | Docker | 20.10+ |

---

## 📁 Project Structure

```
.
├── app/                    # Application Logic (MVC)
├── docker/                 # Docker Specific Configs (MySQL, logs)
├── database/               # Database Layer (Migrations, Seeders)
├── resources/              # Frontend Assets (Views, CSS, JS)
├── routes/                 # Route Definitions (Web, Console)
├── scripts/                # Deployment & Utility Scripts
│   └── deploy.sh           # Main Deployment Script ⭐
├── .env.docker             # Environment Template for Docker ⭐
├── docker-compose.v2.2.yml # Multi-service Orchestration ⭐
├── Dockerfile.v2.2         # Optimized App Image ⭐
└── 📚 Documentation
    ├── README.md           # This file
    ├── GITHUB_OVERVIEW.md  # GitHub Repository Overview
    └── DOCKER_HUB_OVERVIEW.md # Docker Hub Guide
```

---

## 🔧 Maintenance Commands

### Deployment Script
Gunakan skrip deploy untuk manajemen yang lebih mudah:
```bash
./scripts/deploy.sh deploy    # Build dan jalankan semua
./scripts/deploy.sh status    # Cek status layanan
./scripts/deploy.sh backup    # Backup database dan storage
./scripts/deploy.sh logs      # Lihat log real-time
```

### Manual Docker
```bash
docker-compose ps      # Status
docker-compose down    # Stop
```

---

## 📄 License

MIT License

---

**Version**: 2.2.0 | **Updated**: January 2026  
**Made with ❤️ by [Nasrul Mufid](https://github.com/nasrulmufid)**

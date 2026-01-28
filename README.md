# 🚀 IT Help Desk - Ticketing System

**Version**: 5.0 (January 2026)  
**Status**: Production Ready  
**Framework**: Laravel 11  
**PHP Version**: 8.2+  

---

## 📦 Project Overview

IT Help Desk adalah sistem ticketing dukungan IT yang dibangun dengan Laravel 11. Aplikasi ini dirancang untuk mengelola permintaan dukungan teknis dengan fitur manajemen tiket lengkap, komunikasi real-time, dan dashboard analitik.

---

## ✨ Core Features

### 🔐 Authentication & Role Management
```
3 User Roles:
├── Admin → Full system access
├── Technician → Ticket management & support
└── User → Create & manage own tickets
```

### 🎫 Ticket Management System
```
Workflow:
CREATE → ASSIGN → IN PROGRESS → RESOLVED → CLOSED

Attributes:
├── Categories: Hardware, Software, Network, Email, Database, Security, Account, Other
├── Priority: Low, Medium, High, Critical
├── Status: Open, In Progress, Resolved, Closed
├── Attachments: Support file uploads
└── Responses: Communication channel
```

### 📊 Reporting & Analytics
- User dashboard
- Technician workload
- Admin statistics
- Ticket metrics

---

## 🚀 Quick Start Options

### 🐳 NEW: Multi-Service Docker (Recommended - Single Container)

**All-in-one container dengan Laravel, MySQL, dan phpMyAdmin!**

```bash
# Gunakan image dari GitHub Container Registry
docker run -d -p 80:80 --name it-help-desk-multi \
  -e APP_KEY=base64:$(openssl rand -base64 32) \
  -e MYSQL_ROOT_PASSWORD=your_secure_root_password \
  -e DB_PASSWORD=your_secure_db_password \
  ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0

# Akses aplikasi
# - Aplikasi: http://localhost
# - phpMyAdmin: http://localhost/phpmyadmin
```

**Dengan Docker Compose:**
```bash
# Clone repository
git clone https://github.com/nasrulmufid/it-help-desk.git
cd it-help-desk

# Jalankan dengan docker-compose
docker-compose -f docker-compose.multi.yml up -d

# Akses aplikasi di http://localhost:8080
```

### 🐳 Docker Standar (Multi-Container)
```bash
# Start application
docker-compose up -d

# Access at: http://localhost:8000
```

### 💻 Local Development
```bash
# Install dependencies
composer install && npm install

# Setup database
php artisan migrate:fresh --seed

# Build & run
npm run build
php artisan serve
```

---

## � Multi-Service Container Configuration

### Environment Variables
```bash
# Application
APP_NAME="IT Help Desk"
APP_KEY=base64:YOUR_APP_KEY_HERE  # Wajib diisi!
APP_ENV=production
APP_DEBUG=false

# Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=it_helpdesk
DB_USERNAME=it_helpdesk_user
DB_PASSWORD=your_secure_password

# MySQL Root (untuk inisialisasi)
MYSQL_ROOT_PASSWORD=your_secure_root_password
```

### Default Access
- **Aplikasi**: http://localhost
- **phpMyAdmin**: http://localhost/phpmyadmin
- **MySQL Port**: 3306 (opsional)

### Default Credentials
- **MySQL Root**: `root_password` (ubah via environment variable)
- **Application DB**: `it_helpdesk_user` / `it_helpdesk_pass`
- **phpMyAdmin**: Gunakan kredensial MySQL di atas

---

## �👥 Default Test Accounts

| Email | Password | Role |
|-------|----------|------|
| admin@helpdesk.com | password | Admin |
| tech1@helpdesk.com | password | Technician |
| user@helpdesk.com | password | User |

> ⚠️ Change in production!

---

## 📊 Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| **Backend Framework** | Laravel | 11 |
| **Language** | PHP | 8.2+ |
| **Database** | MySQL | 8.0 |
| **Frontend** | Blade + Tailwind CSS | Latest |
| **Build Tool** | Vite | Latest |
| **Container** | Docker | 20.10+ |
| **Web Server** | Apache 2.4 | Latest |
| **Process Manager** | Supervisor | Latest |
| **Database Management** | phpMyAdmin | 5.2.1 |

---

## 📁 Project Structure

```
.
├── app/                    # Application Logic
│   ├── Http/Controllers/   # Request Handlers
│   ├── Models/             # Database Models
│   ├── Notifications/      # Notification Classes
│   └── Providers/          # Service Providers
│
├── database/               # Database Layer
│   ├── database.sqlite     # SQLite Database (opsional)
│   ├── migrations/         # Migrations
│   └── seeders/            # Sample Data
│
├── resources/              # Frontend Assets
│   ├── views/              # Blade Templates
│   ├── css/                # Tailwind CSS
│   └── js/                 # JavaScript
│
├── routes/                 # Route Definitions
├── storage/                # Runtime Storage
├── config/                 # Configuration
│
├── docker/                 # Docker Configuration
│   ├── config/             # Configuration files
│   └── scripts/            # Setup scripts
│
├── .env                    # Environment Config ⭐
├── docker-compose.yml      # Docker Config ⭐
├── docker-compose.multi.yml # Multi-service Config ⭐
├── Dockerfile              # Standard Docker Image ⭐
├── Dockerfile.multi         # Multi-service Image ⭐
├── composer.json           # PHP Dependencies
├── package.json            # Node Dependencies
│
└── 📚 Documentation
    ├── README.md           # This file
    └── SETUP.md            # Setup Guide
```

---

## 🗄️ Database Models

### 1. **User**
- id, name, email, password, role, timestamps

### 2. **Category**
- id, name, slug, description, icon, is_active, timestamps

### 3. **Ticket**
- id, user_id, category_id, assigned_to, title, description, status, priority, timestamps

### 4. **TicketResponse**
- id, ticket_id, user_id, response, is_internal, timestamps

### 5. **TicketAttachment**
- id, ticket_id, file_path, file_name, file_size, mime_type, timestamps

---

## 🔧 Common Commands

### Development
```bash
npm run dev          # Hot reload
npm run build        # Build for production
php artisan serve    # Start dev server
```

### Database
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Reset with data
```

### Docker
```bash
# Multi-service container
docker-compose -f docker-compose.multi.yml up -d
docker-compose -f docker-compose.multi.yml down

# Standard multi-container
docker-compose up -d
docker-compose down
docker-compose logs -f
```

---

## 🔐 Security Features

✅ CSRF Protection  
✅ SQL Injection Prevention  
✅ XSS Protection  
✅ Password Hashing (bcrypt)  
✅ Role-Based Access Control  
✅ Authentication Middleware  
✅ Session Management  

---

## 🐳 Docker Info

### Multi-Service Image Details
- **Base**: `ubuntu:22.04`
- **Services**: Laravel + MySQL + phpMyAdmin + Supervisor
- **Size**: ~2.5 GB
- **Port**: 80 (Laravel & phpMyAdmin)
- **Database Port**: 3306 (opsional)

### GitHub Container Registry
**Repository**: `ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi`

```bash
# Pull the latest version
docker pull ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:latest

# Pull specific version
docker pull ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0
```

---

## 📝 Key Files

| File | Purpose |
|------|---------|
| `.env` | Environment configuration |
| `Dockerfile.multi` | Multi-service Docker image definition |
| `docker-compose.multi.yml` | Multi-service container orchestration |
| `composer.json` | PHP dependencies |
| `package.json` | JavaScript dependencies |

---

## 📚 Documentation

- **SETUP.md** - Local development setup guide
- **DOCKER_HUB_README.md** - Docker deployment guide

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Database connection failed | Check environment variables, pastikan MySQL sudah running |
| Permission denied on storage | `chmod -R 775 storage` di dalam container |
| Assets not loading | `npm run build` atau restart container |
| Port already in use | Ubah port di docker-compose.yml |
| MySQL tidak bisa diakses | Cek port 3306 dan firewall |
| phpMyAdmin error login | Gunakan kredensial MySQL yang benar |

---

## 📊 Project Stats

- **Database Tables**: 8
- **User Roles**: 3
- **Ticket Categories**: 8
- **Default Accounts**: 3 (for testing)
- **Container Size**: ~2.5 GB (multi-service)
- **Build Time**: ~15-20 menit

---

## 🎯 Features at a Glance

✅ User authentication & roles  
✅ Ticket lifecycle management  
✅ Categorization & prioritization  
✅ Real-time communication  
✅ Admin dashboard  
✅ Technician assignment  
✅ File attachments  
✅ Notifications  
✅ Multi-service container  
✅ Built-in phpMyAdmin  
✅ GitHub Actions CI/CD  

---

## 📄 License

MIT License

---

**Version**: 5.0 | **Updated**: January 2026 | **Docker**: Multi-Service Available

Happy coding! 🎉
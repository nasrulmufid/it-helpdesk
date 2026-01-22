# 🚀 IT Help Desk - Ticketing System

**Version**: 2.0 (January 2026)  
**Status**: Production Ready  
**Database**: SQLite 3  
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

## 🚀 Quick Start

### 🐳 Docker (Recommended - Easiest)
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

## 👥 Default Test Accounts

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
| **Database** | SQLite | 3 |
| **Frontend** | Blade + Tailwind CSS | Latest |
| **Build Tool** | Vite | Latest |
| **Container** | Docker | 20.10+ |
| **Web Server** | Apache 2.4 | Latest |

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
│   ├── database.sqlite     # SQLite Database ⭐
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
├── .env                    # Environment Config ⭐
├── docker-compose.yml      # Docker Config ⭐
├── Dockerfile              # Docker Image ⭐
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
docker-compose up -d             # Start
docker-compose down              # Stop
docker-compose logs -f           # View logs
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

### Image Details
- **Base**: `php:8.2-apache`
- **Size**: ~1.4 GB
- **Port**: 8000 (host) → 80 (container)
- **Database**: SQLite (file-based, no separate container)

### Docker Hub
**Repository**: `nasrulmufid/it-help-desk`

```bash
docker pull nasrulmufid/it-help-desk:latest
```

---

## 📝 Key Files

| File | Purpose |
|------|---------|
| `.env` | Environment configuration |
| `Dockerfile` | Docker image definition |
| `docker-compose.yml` | Container orchestration |
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
| Database connection failed | Check `.env` config |
| Permission denied on storage | `chmod -R 775 storage` |
| Assets not loading | `npm run build` |
| Port already in use | Change port in `docker-compose.yml` |

---

## 📊 Project Stats

- **Database Tables**: 8
- **User Roles**: 3
- **Ticket Categories**: 8
- **Default Accounts**: 3 (for testing)

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

---

## 📄 License

MIT License

---

**Version**: 2.0 | **Updated**: January 2026

Happy coding! 🎉

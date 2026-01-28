# GitHub Repository Overview - IT Help Desk v5.0

## 🚀 One-Command Deployment

**All-in-one Docker container dengan Laravel 11, MySQL 8.0, dan phpMyAdmin!**

```bash
# Deploy dalam satu command
docker run -d -p 80:80 --name it-help-desk \
  -e APP_KEY=base64:$(openssl rand -base64 32) \
  ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0

# Akses: http://localhost
# phpMyAdmin: http://localhost/phpmyadmin
```

## 📋 Quick Start

### 🐳 Docker (Recommended)
```bash
# Gunakan image siap pakai
docker run -d -p 80:80 --name it-help-desk \
  -e APP_KEY=base64:$(openssl rand -base64 32) \
  -e MYSQL_ROOT_PASSWORD=your_secure_password \
  ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0

# Atau dengan docker-compose
git clone https://github.com/nasrulmufid/it-help-desk.git
cd it-help-desk
docker-compose -f docker-compose.multi.yml up -d
```

### 💻 Local Development
```bash
composer install && npm install
php artisan migrate:fresh --seed
npm run build && php artisan serve
```

## 🎯 Features

### Core Functionality
- ✅ **Ticket Management**: Create → Assign → Progress → Resolve → Close
- ✅ **Multi-Role System**: Admin, Technician, User
- ✅ **File Attachments**: Upload and manage ticket attachments
- ✅ **Real-time Communication**: Ticket responses and notifications
- ✅ **Analytics Dashboard**: Performance metrics and reports

### Technical Features
- ✅ **Single Container**: Laravel + MySQL + phpMyAdmin
- ✅ **GitHub Actions**: Automated build and deployment
- ✅ **Multi-Platform**: linux/amd64, linux/arm64
- ✅ **Production Ready**: Supervisor, health checks, logging
- ✅ **Easy Configuration**: Environment variables

## 🔧 Configuration

### Environment Variables
```bash
# Required
APP_KEY=base64:YOUR_APP_KEY_HERE
MYSQL_ROOT_PASSWORD=your_secure_password

# Optional (with defaults)
APP_NAME="IT Help Desk"
DB_DATABASE=it_helpdesk
DB_USERNAME=it_helpdesk_user
DB_PASSWORD=it_helpdesk_pass
```

### Default Access
- **Application**: http://localhost
- **phpMyAdmin**: http://localhost/phpmyadmin
- **MySQL**: localhost:3306

### Test Accounts
| Email | Password | Role |
|-------|----------|------|
| admin@helpdesk.com | password | Admin |
| tech1@helpdesk.com | password | Technician |
| user@helpdesk.com | password | User |

## 📊 Technology Stack

| Component | Technology | Version |
|-----------|------------|---------|
| Framework | Laravel | 11.x |
| Language | PHP | 8.2+ |
| Database | MySQL | 8.0 |
| Web Server | Apache | 2.4 |
| Process Manager | Supervisor | Latest |
| Database Admin | phpMyAdmin | 5.2.1 |
| Frontend | Tailwind CSS + Blade | Latest |

## 🐳 Docker Details

### Image Information
- **Registry**: `ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi`
- **Size**: ~2.5GB
- **Base**: Ubuntu 22.04 LTS
- **Build**: GitHub Actions CI/CD

### Available Tags
- `latest` - Stable version
- `v5.0` - Version 5.0
- `v5` - Major version 5

### Docker Commands
```bash
# Pull image
docker pull ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0

# Run container
docker run -d -p 80:80 --name it-help-desk \
  -e APP_KEY=base64:$(openssl rand -base64 32) \
  ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0

# View logs
docker logs -f it-help-desk
```

## 🔗 Links

- **GitHub Repository**: https://github.com/nasrulmufid/it-help-desk
- **Docker Image**: https://github.com/nasrulmufid/it-help-desk/pkgs/container/it-help-desk%2Fit-help-desk-multi
- **Full Documentation**: [README.md](README.md)
- **Issues**: https://github.com/nasrulmufid/it-help-desk/issues

## 🚀 GitHub Actions

Automated CI/CD pipeline:
- ✅ Multi-platform builds (amd64, arm64)
- ✅ Automatic tagging and releases
- ✅ Container registry publishing
- ✅ Security scanning
- ✅ Health checks

## 📄 License

MIT License - See [LICENSE](LICENSE) for details.

---

**Version**: 5.0 | **Build**: GitHub Actions | **Status**: Production Ready 🎉
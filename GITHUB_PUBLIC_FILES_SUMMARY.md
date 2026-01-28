# 🚀 IT Help Desk v5.0 - GitHub Public Repository Files

## 📋 Summary of Files to Push

### ✅ Docker & Configuration Files
- `Dockerfile.multi` - Multi-service Dockerfile (Laravel + MySQL + phpMyAdmin)
- `docker-compose.multi.yml` - Docker Compose for development
- `.github/workflows/docker-multi-build.yml` - GitHub Actions workflow
- `docker/config/mysql.cnf` - MySQL configuration
- `.env.multi.example` - Environment template for multi-service

### ✅ Laravel Application Files
- `app/` - All Laravel application logic
- `bootstrap/` - Bootstrap framework files
- `config/` - Laravel configuration files
- `database/` - Migrations and seeders
- `public/` - Public assets and index.php
- `resources/` - Views, CSS, JavaScript files
- `routes/` - Route definitions

### ✅ Dependencies & Build Files
- `composer.json` - PHP dependencies
- `composer.lock` - Locked PHP dependencies
- `package.json` - Node.js dependencies
- `package-lock.json` - Locked Node.js dependencies
- `artisan` - Laravel CLI tool
- `phpunit.xml` - PHPUnit testing configuration

### ✅ Documentation
- `README.md` - Updated with multi-service documentation
- `LICENSE` - MIT License
- `.env.example` - Environment template

## 🎯 Key Features in This Release

### Multi-Service Container
- **Single Container**: Laravel 11 + MySQL 8.0 + phpMyAdmin 5.2.1
- **GitHub Actions**: Automated build and deployment
- **Multi-Platform**: linux/amd64, linux/arm64
- **One-Command Deployment**: `docker run -d -p 80:80 ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0`

### Access Points
- **Application**: http://localhost
- **phpMyAdmin**: http://localhost/phpmyadmin
- **MySQL Port**: 3306 (optional)

### Default Credentials
- **MySQL Root**: `root_password` (change via environment)
- **Application DB**: `it_helpdesk_user` / `it_helpdesk_pass`
- **Test Accounts**: admin@helpdesk.com / password

## 🚀 Quick Start Commands

### Production Deployment
```bash
# Generate app key
APP_KEY=base64:$(openssl rand -base64 32)

# Deploy single container
docker run -d -p 80:80 --name it-help-desk-multi \
  -e APP_KEY=$APP_KEY \
  -e MYSQL_ROOT_PASSWORD=your_secure_password \
  ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0
```

### Development with Docker Compose
```bash
git clone https://github.com/nasrulmufid/it-help-desk.git
cd it-help-desk
docker-compose -f docker-compose.multi.yml up -d
```

## 📊 Technology Stack
- **Framework**: Laravel 11
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0
- **Web Server**: Apache 2.4
- **Process Manager**: Supervisor
- **Database Admin**: phpMyAdmin 5.2.1
- **Frontend**: Tailwind CSS + Blade
- **Container**: Docker
- **CI/CD**: GitHub Actions

## 🔗 Links
- **GitHub Repository**: https://github.com/nasrulmufid/it-help-desk
- **Docker Image**: ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0
- **Documentation**: See README.md for full documentation

## 📄 License
MIT License - See LICENSE file for details.

**Version**: 5.0 | **Status**: Production Ready 🎉
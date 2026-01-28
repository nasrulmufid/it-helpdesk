# IT Help Desk Multi-Service Docker Image v5.0

## 🚀 Quick Start (Single Container - All Services)

**All-in-one container dengan Laravel 11, MySQL 8.0, dan phpMyAdmin 5.2.1!**

### Docker Run (Production)
```bash
# Generate app key
APP_KEY=base64:$(openssl rand -base64 32)

# Jalankan container
docker run -d -p 80:80 --name it-help-desk-multi \
  -e APP_KEY=$APP_KEY \
  -e MYSQL_ROOT_PASSWORD=your_secure_root_password \
  -e DB_PASSWORD=your_secure_db_password \
  ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0

# Akses aplikasi
# - Aplikasi: http://localhost
# - phpMyAdmin: http://localhost/phpmyadmin
```

### Docker Compose (Development)
```bash
# Clone repository
git clone https://github.com/nasrulmufid/it-help-desk.git
cd it-help-desk

# Jalankan dengan docker-compose
docker-compose -f docker-compose.multi.yml up -d

# Akses aplikasi di http://localhost:8080
```

## 📋 Konfigurasi Environment

### Wajib Diisi
```bash
APP_KEY=base64:YOUR_APP_KEY_HERE  # Generate dengan: openssl rand -base64 32
MYSQL_ROOT_PASSWORD=your_secure_root_password
```

### Opsional (Dengan Default)
```bash
# Application
APP_NAME="IT Help Desk"
APP_ENV=production
APP_DEBUG=false

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=it_helpdesk
DB_USERNAME=it_helpdesk_user
DB_PASSWORD=it_helpdesk_pass

# Mail (opsional)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

## 🔑 Default Access & Credentials

### Akses Aplikasi
- **Aplikasi**: http://localhost
- **phpMyAdmin**: http://localhost/phpmyadmin
- **MySQL Port**: 3306 (opsional)

### Default Credentials
- **MySQL Root**: `root_password` (ubah via MYSQL_ROOT_PASSWORD)
- **Application DB**: `it_helpdesk_user` / `it_helpdesk_pass`
- **phpMyAdmin**: Gunakan kredensial MySQL di atas

### Test Accounts
| Email | Password | Role |
|-------|----------|------|
| admin@helpdesk.com | password | Admin |
| tech1@helpdesk.com | password | Technician |
| user@helpdesk.com | password | User |

## 🐳 Docker Commands

### Production Deployment
```bash
# Pull latest image
docker pull ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0

# Run container
docker run -d -p 80:80 --name it-help-desk-multi \
  -e APP_KEY=base64:$(openssl rand -base64 32) \
  -e MYSQL_ROOT_PASSWORD=your_secure_password \
  ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0

# View logs
docker logs -f it-help-desk-multi

# Stop container
docker stop it-help-desk-multi
docker rm it-help-desk-multi
```

### Development
```bash
# Build from source
docker build -f Dockerfile.multi -t it-help-desk-multi:dev .

# Run with docker-compose
docker-compose -f docker-compose.multi.yml up -d

# View logs
docker-compose -f docker-compose.multi.yml logs -f

# Stop
docker-compose -f docker-compose.multi.yml down
```

## 📊 Container Specifications

### Image Details
- **Base**: Ubuntu 22.04 LTS
- **Size**: ~2.5 GB
- **Services**: Laravel 11 + MySQL 8.0 + phpMyAdmin 5.2.1 + Supervisor
- **Port**: 80 (HTTP)
- **Database Port**: 3306 (opsional)

### System Requirements
- **RAM**: Minimum 1GB, Recommended 2GB
- **Storage**: 5GB free space
- **CPU**: 1 core minimum, 2 cores recommended
- **Docker**: 20.10+

## 🔧 Troubleshooting

### Container tidak bisa diakses
```bash
# Cek status container
docker ps

# Cek logs
docker logs it-help-desk-multi

# Test koneksi
curl -f http://localhost || echo "Container belum siap"
```

### MySQL connection failed
```bash
# Cek MySQL service
docker exec it-help-desk-multi mysqladmin ping -h localhost -u root -proot_password

# Restart MySQL service
docker exec it-help-desk-multi service mysql restart
```

### Permission issues
```bash
# Fix permissions
docker exec it-help-desk-multi chown -R www-data:www-data /var/www/html/storage
docker exec it-help-desk-multi chown -R www-data:www-data /var/www/html/bootstrap/cache
```

### Generate APP_KEY
```bash
# Generate secure app key
openssl rand -base64 32
```

## 🚀 GitHub Actions CI/CD

Container ini dibangun otomatis di GitHub Actions dengan:
- Multi-platform support (linux/amd64, linux/arm64)
- Automatic tagging dengan semantic versioning
- Push ke GitHub Container Registry
- Automatic release creation

### Available Tags
- `latest` - Versi stabil terbaru
- `v5.0` - Versi 5.0 spesifik
- `v5` - Major version 5

## 📚 Links & Documentation

- **GitHub Repository**: https://github.com/nasrulmufid/it-help-desk
- **Dockerfile**: [Dockerfile.multi](https://github.com/nasrulmufid/it-help-desk/blob/main/Dockerfile.multi)
- **Docker Compose**: [docker-compose.multi.yml](https://github.com/nasrulmufid/it-help-desk/blob/main/docker-compose.multi.yml)
- **Full Documentation**: [README.md](https://github.com/nasrulmufid/it-help-desk/blob/main/README.md)

## 📄 License

MIT License - See [LICENSE](https://github.com/nasrulmufid/it-help-desk/blob/main/LICENSE) for details.

---

**Version**: 5.0 | **Build**: GitHub Actions | **Registry**: GitHub Container Registry

Happy deploying! 🎉
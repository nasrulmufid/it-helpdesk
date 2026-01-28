#!/bin/bash

# Script untuk setup Git tag v5.0 dan push ke repository
# IT Help Desk Multi-Service Docker Image

echo "🚀 IT Help Desk - Git Tag v5.0 Setup Script"
echo "=========================================="

# Cek apakah di repository Git
if [ ! -d ".git" ]; then
    echo "❌ Error: Ini bukan repository Git"
    exit 1
fi

# Cek status Git
echo "📋 Checking Git status..."
git status

# Cek apakah ada perubahan yang belum di-commit
if [[ -n $(git status -s) ]]; then
    echo "⚠️  Ada perubahan yang belum di-commit. Commit dulu atau stash perubahan."
    echo "Gunakan: git add . && git commit -m 'Your message'"
    exit 1
fi

# Cek apakah tag v5.0 sudah ada
if git rev-parse "v5.0" >/dev/null 2>&1; then
    echo "⚠️  Tag v5.0 sudah ada. Hapus dulu jika ingin membuat ulang."
    echo "Gunakan: git tag -d v5.0 && git push origin :refs/tags/v5.0"
    exit 1
fi

# Buat tag v5.0
echo "🏷️  Creating tag v5.0..."
git tag -a v5.0 -m "Release v5.0 - Multi-Service Docker Container

Features:
- Laravel 11 + MySQL 8.0 + phpMyAdmin 5.2.1 in single container
- GitHub Actions CI/CD with multi-platform support
- Supervisor process management
- Automatic database initialization
- Built-in phpMyAdmin for database management

Container Access:
- Application: http://localhost
- phpMyAdmin: http://localhost/phpmyadmin
- MySQL: localhost:3306

Docker Image: ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0"

# Push tag ke remote
echo "📤 Pushing tag v5.0 to remote..."
git push origin v5.0

# Cek apakah push berhasil
if [ $? -eq 0 ]; then
    echo "✅ Tag v5.0 berhasil dibuat dan di-push!"
    echo ""
    echo "🎉 Selanjutnya:"
    echo "1. GitHub Actions akan otomatis build Docker image"
    echo "2. Image akan di-push ke GitHub Container Registry"
    echo "3. Release otomatis akan dibuat"
    echo ""
    echo "📋 GitHub Actions Workflow:"
    echo "- File: .github/workflows/docker-multi-build.yml"
    echo "- Trigger: Push tag v5.0"
    echo "- Output: ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0"
    echo ""
    echo "🔗 Links:"
    echo "- GitHub Repository: https://github.com/nasrulmufid/it-help-desk"
    echo "- GitHub Packages: https://github.com/nasrulmufid/it-help-desk/packages"
else
    echo "❌ Error: Push tag gagal"
    exit 1
fi

echo ""
echo "✨ Proses selesai! Docker image akan tersedia di GitHub Container Registry setelah build selesai."
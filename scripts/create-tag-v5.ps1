# PowerShell Script untuk membuat Git tag v5.0
# IT Help Desk Multi-Service Docker Image

echo "🚀 IT Help Desk - Git Tag v5.0 Setup Script (PowerShell)"
echo "=========================================================="

# Cek apakah di repository Git
if (!(Test-Path ".git")) {
    echo "❌ Error: Ini bukan repository Git"
    exit 1
}

# Cek status Git
echo "📋 Checking Git status..."
git status

# Cek apakah ada perubahan yang belum di-commit
$gitStatus = git status --porcelain
if ($gitStatus) {
    echo "⚠️  Ada perubahan yang belum di-commit. Commit dulu atau stash perubahan."
    echo "Gunakan: git add . && git commit -m 'Your message'"
    exit 1
}

# Cek apakah tag v5.0 sudah ada
$tagExists = git tag -l "v5.0"
if ($tagExists) {
    echo "⚠️  Tag v5.0 sudah ada. Hapus dulu jika ingin membuat ulang."
    echo "Gunakan: git tag -d v5.0 && git push origin :refs/tags/v5.0"
    exit 1
}

# Buat tag v5.0
echo "🏷️  Creating tag v5.0..."
$tagMessage = @"
Release v5.0 - Multi-Service Docker Container

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

Docker Image: ghcr.io/nasrulmufid/it-help-desk/it-help-desk-multi:v5.0
"@

git tag -a v5.0 -m $tagMessage

# Push tag ke remote
echo "📤 Pushing tag v5.0 to remote..."
git push origin v5.0

# Cek apakah push berhasil
if ($LASTEXITCODE -eq 0) {
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
} else {
    echo "❌ Error: Push tag gagal"
    exit 1
}

echo ""
echo "✨ Proses selesai! Docker image akan tersedia di GitHub Container Registry setelah build selesai."
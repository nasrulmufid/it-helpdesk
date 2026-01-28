#!/bin/bash
set -e

echo "🚀 Starting IT Help Desk v2.2.0..."

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
timeout=60
while ! mysqladmin ping -h mysql -u root -proot_pass_2024_secure --silent; do
    timeout=$((timeout - 1))
    if [ $timeout -eq 0 ]; then
        echo "❌ MySQL is not responding after 60 seconds"
        exit 1
    fi
    echo "   Still waiting for MySQL... ($timeout seconds left)"
    sleep 1
done
echo "✅ MySQL is ready!"

# Set proper permissions (in case they were lost)
echo "🔧 Setting up permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Create necessary directories
echo "📁 Creating necessary directories..."
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/bootstrap/cache

# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache configurations
echo "⚡ Optimizing Laravel configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run database migrations (if needed)
echo "🗄️ Running database migrations..."
php artisan migrate --force --seed || true

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link || true

# Clear caches
echo "🧹 Clearing caches..."
php artisan cache:clear || true
php artisan config:clear || true

# Set proper ownership for all files
echo "👤 Setting final ownership..."
chown -R www-data:www-data /var/www/html

# Start cron service for scheduled tasks
echo "⏰ Starting cron service..."
service cron start || true

# Add cron job for Laravel scheduler
echo "* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1" | crontab -u www-data - || true

echo "✅ IT Help Desk v2.2.0 is ready!"
echo "📋 Application URL: http://localhost:8000"
echo "📊 phpMyAdmin URL: http://localhost:8080"
echo "🗄️ MySQL Host: mysql:3306"

# Start Apache in foreground
echo "🌐 Starting Apache web server..."
exec apache2-foreground
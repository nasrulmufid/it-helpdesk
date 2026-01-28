#!/bin/bash
set -e

echo "🚀 Starting Monolithic IT Help Desk Environment..."

# Ensure MySQL directories have correct permissions
chown -R mysql:mysql /var/lib/mysql
chown -R mysql:mysql /var/run/mysqld

# Start MySQL temporarily to run migrations if needed
service mariadb start

echo "🗄️ Running database migrations and seeders..."
php artisan migrate --force --seed || echo "⚠️ Migration failed, database might already be setup."

# Create storage link
php artisan storage:link --force || true

# Stop MySQL so supervisor can take over
service mariadb stop

echo "✅ Environment prepared. Starting Supervisor..."

# Start Supervisor to manage all processes
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

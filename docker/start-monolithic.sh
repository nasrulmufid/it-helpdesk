#!/bin/bash
set -e

echo "🚀 Starting Monolithic IT Help Desk Environment..."

# Ensure MySQL directories have correct permissions
mkdir -p /run/mysqld
chown -R mysql:mysql /run/mysqld
chown -R mysql:mysql /var/lib/mysql

# Initialize MySQL data directory if empty
if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "📦 Initializing MySQL data directory..."
    mariadb-install-db --user=mysql --datadir=/var/lib/mysql
fi

# Start MySQL in background to perform setup
echo "📡 Starting MariaDB for initialization..."
mysqld_safe --datadir=/var/lib/mysql --skip-networking &
PID=$!

# Wait for MySQL to be ready
timeout=30
while [ $timeout -gt 0 ] && ! mariadb-admin ping --silent; do
    sleep 1
    timeout=$((timeout - 1))
done

if [ $timeout -eq 0 ]; then
    echo "❌ MariaDB failed to start during initialization."
    exit 1
fi

echo "🔐 Configuring databases and users..."
# Try to connect without password first (initial run)
if mariadb -u root -e "status" >/dev/null 2>&1; then
    echo "🔑 Root password not set, configuring now..."
    mariadb -e "CREATE DATABASE IF NOT EXISTS it_helpdesk;"
    mariadb -e "CREATE DATABASE IF NOT EXISTS phpmyadmin;"
    mariadb -e "GRANT ALL PRIVILEGES ON phpmyadmin.* TO 'phpmyadmin'@'localhost' IDENTIFIED BY 'phpmyadmin_pass';"
    mariadb -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'root_pass_2024_secure';"
    mariadb -e "CREATE USER IF NOT EXISTS 'helpdesk_user'@'localhost' IDENTIFIED BY 'helpdesk_pass_2024';"
    mariadb -e "GRANT ALL PRIVILEGES ON it_helpdesk.* TO 'helpdesk_user'@'localhost';"
    mariadb -e "FLUSH PRIVILEGES;"
    echo "✅ Databases and users configured successfully."
elif mariadb -u root -proot_pass_2024_secure -e "status" >/dev/null 2>&1; then
    echo "✅ Root password already set and working correctly."
else
    echo "❌ Access denied for root user. Manual intervention may be required."
    exit 1
fi

echo "🗄️ Running database migrations and seeders..."
# Use the root password for migrations to ensure access
export DB_PASSWORD=helpdesk_pass_2024
php artisan migrate --force --seed || echo "⚠️ Migration failed, database might already be setup."

# Create storage link
php artisan storage:link --force || true

# Stop temporary MySQL
echo "🛑 Stopping temporary MariaDB..."
mariadb-admin -u root -proot_pass_2024_secure shutdown
wait $PID

echo "✅ Environment prepared. Starting Supervisor..."

# Start Supervisor to manage all processes
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

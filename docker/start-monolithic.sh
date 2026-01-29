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
# Prepare setup SQL
SETUP_SQL=$(cat <<EOF
CREATE DATABASE IF NOT EXISTS it_helpdesk;
CREATE DATABASE IF NOT EXISTS phpmyadmin;
GRANT ALL PRIVILEGES ON phpmyadmin.* TO 'phpmyadmin'@'localhost' IDENTIFIED BY 'phpmyadmin_pass';
CREATE USER IF NOT EXISTS 'helpdesk_user'@'localhost' IDENTIFIED BY 'helpdesk_pass_2024';
GRANT ALL PRIVILEGES ON it_helpdesk.* TO 'helpdesk_user'@'localhost';
ALTER USER 'root'@'localhost' IDENTIFIED BY 'root_pass_2024_secure';
FLUSH PRIVILEGES;
EOF
)

# Try setup without password first (initial run)
if echo "$SETUP_SQL" | mariadb -u root --protocol=socket >/dev/null 2>&1; then
    echo "✅ Databases and users configured successfully (initial setup)."
# Try setup with the target root password (re-run or volume persistent)
elif echo "$SETUP_SQL" | mariadb -u root -proot_pass_2024_secure --protocol=socket >/dev/null 2>&1; then
    echo "✅ Databases and users already configured (setup verified with password)."
else
    echo "❌ Access denied for root user during configuration. Attempting debug..."
    # If both fail, try to show the actual error for one simple command
    mariadb -u root -e "status" --protocol=socket || echo "Manual intervention required for MySQL root access."
    exit 1
fi

echo "🗄️ Running database migrations and seeders..."
# Ensure Laravel uses the correct user credentials for migrations
export DB_USERNAME=helpdesk_user
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

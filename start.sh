#!/bin/bash

# Ensure proper permissions for storage and cache directories
echo "Setting permissions on storage and cache directories..."
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/database
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/database

# Function to check database connection
check_db() {
    DB_CONNECTION=${DB_CONNECTION:-sqlite}
    
    if [ "$DB_CONNECTION" = "sqlite" ]; then
<<<<<<< HEAD
        # For SQLite, ensure the file exists
        DB_PATH=${DB_DATABASE:-database/database.sqlite}
        
        # If the path is relative, prefix it with the app path
        if [[ ! "$DB_PATH" == /* ]]; then
            DB_PATH="/var/www/html/$DB_PATH"
=======
        # For SQLite, just check if we can access the database file or create it
        DB_PATH=${DB_DATABASE:-database/database.sqlite}
        if [[ "$DB_PATH" != /* ]]; then
            DB_PATH="/var/www/html/$DB_PATH"
        fi
        export RESOLVED_SQLITE_DB_PATH="$DB_PATH"
        mkdir -p "$(dirname "$DB_PATH")"
        if [ ! -f "$DB_PATH" ]; then
            touch "$DB_PATH" 2>/dev/null || true
        fi
        chown www-data:www-data "$DB_PATH" 2>/dev/null || true
        if [ ! -f "$DB_PATH" ]; then
            echo "SQLite database file will be created during migration"
>>>>>>> 6fc926c (Fix Docker SQLite sessions and unique ticket numbers)
        fi

        if [ ! -f "$DB_PATH" ]; then
            echo "Creating SQLite database file at $DB_PATH..."
            touch "$DB_PATH"
        fi
        chown www-data:www-data "$DB_PATH"
        chmod 664 "$DB_PATH"
        echo "SQLite database ready at $DB_PATH"
        return 0
    else
        # MySQL check
        php -r "
        try {
            \$host = getenv('DB_HOST') ?: 'db';
            \$port = getenv('DB_PORT') ?: '3306';
            \$database = getenv('DB_DATABASE') ?: 'it_helpdesk';
            \$username = getenv('DB_USERNAME') ?: 'it_helpdesk_user';
            \$password = getenv('DB_PASSWORD') ?: 'it_helpdesk_pass';

            \$pdo = new PDO(
                \"mysql:host=\$host;port=\$port;dbname=\$database;charset=utf8mb4\",
                \$username,
                \$password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5]
            );

            echo \"Database connected successfully\\n\";
            exit(0);
        } catch (Exception \$e) {
            echo \"Database not ready: \" . \$e->getMessage() . \"\\n\";
            exit(1);
        }
        "
    fi
    return $?
}

# Try to connect to database (with timeout)
echo "Checking database connection..."
DB_READY=0
for i in {1..30}; do
    if check_db; then
        DB_READY=1
        break
    fi
    echo "Attempt $i/30: Database not ready, waiting..."
    sleep 2
done

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

php artisan config:clear >/dev/null 2>&1 || true

# Run database migrations if database is ready
if [ $DB_READY -eq 1 ]; then
    echo "Running database migrations..."
    if php artisan migrate --force; then
        echo "Migrations completed successfully"
    else
        echo "ERROR: Migrations failed!"
        exit 1
    fi
    if [ -n "$RESOLVED_SQLITE_DB_PATH" ] && command -v sqlite3 >/dev/null 2>&1; then
        SESSION_TABLE=$(sqlite3 "$RESOLVED_SQLITE_DB_PATH" "SELECT name FROM sqlite_master WHERE type='table' AND name='sessions';" 2>/dev/null || true)
        if [ "$SESSION_TABLE" != "sessions" ]; then
            echo "ERROR: sessions table not found in SQLite database ($RESOLVED_SQLITE_DB_PATH)"
            exit 1
        fi
    fi

    # Check if database is seeded
    USER_COUNT=$(php artisan tinker --execute="echo App\\Models\\User::count();" 2>/dev/null || echo "0")

    if [ "$USER_COUNT" = "0" ]; then
        echo "Seeding database..."
        if php artisan db:seed --force; then
            echo "Database seeding completed successfully"
        else
            echo "WARNING: Database seeding failed, but continuing..."
        fi
    else
        echo "Database already seeded, skipping..."
    fi
else
    echo "WARNING: Database not available. Application will start but may show errors."
    echo "Please ensure database is running and accessible, then restart the container."
fi

# Clear and cache config
echo "Caching configuration..."
php artisan config:cache || echo "WARNING: config:cache failed, continuing..."
php artisan route:cache || echo "WARNING: route:cache failed, continuing..."

# Cache views only when view paths exist. Some environments (or accidental mounts) may
# remove the `resources/views` directory which causes `view:cache` to fail with
# "View path not found." — handle that gracefully.
if [ -d "resources/views" ]; then
    if php artisan view:cache; then
        echo "View cache created successfully"
    else
        echo "WARNING: php artisan view:cache failed, attempting to recover..."
        # Ensure the compiled views directory exists and try again; don't fatal.
        mkdir -p storage/framework/views
        touch storage/framework/views/.keep
        echo "Retrying view:cache..."
        php artisan view:cache || echo "WARNING: view:cache retry failed, continuing..."
    fi
else
    echo "resources/views not found — skipping view:cache to avoid runtime error"
fi

# Create storage link if needed
if [ ! -L "public/storage" ]; then
    echo "Creating storage link..."
    php artisan storage:link
fi

echo "Starting Apache web server..."
# Start Apache
apache2-foreground

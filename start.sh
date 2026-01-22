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
        # For SQLite, just check if we can access the database file or create it
        DB_PATH=${DB_DATABASE:-database.sqlite}
        if [ ! -f "$DB_PATH" ]; then
            echo "SQLite database file will be created during migration"
        fi
        echo "SQLite database ready"
        return 0
    else
        # Original MySQL check
        php -r "
        try {
            \$host = getenv('DB_HOST') ?: 'db';
            \$port = getenv('DB_PORT') ?: '3306';
            \$database = getenv('DB_DATABASE') ?: 'it_helpdesk';
            \$username = getenv('DB_USERNAME') ?: 'root';
            \$password = getenv('DB_PASSWORD') ?: 'password';

            // First try to connect to mysql database to check if MySQL is running
            \$pdo = new PDO(
                \"mysql:host=\$host;port=\$port;dbname=mysql\",
                \$username,
                \$password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5]
            );

            // Check if application database exists
            \$stmt = \$pdo->query(\"SHOW DATABASES LIKE '\$database'\");
            \$exists = \$stmt->fetch();

            if (!\$exists) {
                echo \"Creating database \$database...\\n\";
                \$pdo->exec(\"CREATE DATABASE IF NOT EXISTS \$database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci\");
            }

            // Now try to connect to the application database
            \$pdo = new PDO(
                \"mysql:host=\$host;port=\$port;dbname=\$database\",
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

# Run database migrations if database is ready
if [ $DB_READY -eq 1 ]; then
    echo "Running database migrations..."

    # Check if migrations are already run
    MIGRATION_STATUS=$(php artisan migrate:status --no-ansi 2>/dev/null | grep -c "Ran" || echo "0")

    if [ "$MIGRATION_STATUS" -eq "0" ]; then
        echo "No migrations found, running fresh migrations..."
        if php artisan migrate --force; then
            echo "Migrations completed successfully"
        else
            echo "ERROR: Migrations failed!"
            exit 1
        fi
    else
        echo "Migrations already run, skipping..."
    fi

    # Check if database is seeded
    USER_COUNT=$(php artisan tinker --execute="echo App\\\Models\\\User::count();" 2>/dev/null || echo "0")

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
#!/bin/sh
set -e

# Wait for database
echo "Waiting for database..."
until php artisan db:monitor --timeout=60 2>/dev/null || php -r "
    \$host = getenv('DB_HOST') ?: 'localhost';
    \$port = getenv('DB_PORT') ?: 5432;
    \$timeout = 60;
    \$start = time();
    while (time() - \$start < \$timeout) {
        if (@fsockopen(\$host, \$port, \$errno, \$errstr, 2)) {
            exit(0);
        }
        sleep(1);
    }
    exit(1);
"; do
    echo "Database not ready, waiting..."
    sleep 2
done

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link 2>/dev/null || true

# Start PHP-FPM in background
php-fpm -D

# Start Caddy (foreground)
exec caddy run --config /etc/caddy/Caddyfile
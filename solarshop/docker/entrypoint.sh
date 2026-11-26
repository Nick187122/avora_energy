#!/bin/sh
set -e

echo "==> Starting Avora Energy deployment..."

cd /var/www/html

# Wait for the database to be reachable before proceeding
echo "==> Waiting for database connection..."
MAX_TRIES=30
COUNT=0
until php artisan db:show --no-interaction > /dev/null 2>&1; do
    COUNT=$((COUNT + 1))
    if [ $COUNT -ge $MAX_TRIES ]; then
        echo "ERROR: Could not connect to the database after $MAX_TRIES attempts. Check your DB_HOST, DB_USERNAME, DB_PASSWORD, and DB_PORT env vars in Render."
        exit 1
    fi
    echo "  DB not ready yet ($COUNT/$MAX_TRIES), retrying in 2s..."
    sleep 2
done
echo "==> Database connection established!"

# Run database migrations (force = no interactive prompts)
echo "==> Running database migrations..."
php artisan migrate --force

# Cache Laravel config, routes, and views AFTER confirming DB is up
echo "==> Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create symbolic link for storage (if not already exists)
echo "==> Linking storage..."
php artisan storage:link || true

echo "==> Starting services via Supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf

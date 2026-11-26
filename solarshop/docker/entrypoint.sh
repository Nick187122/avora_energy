#!/bin/sh
set -e

echo "==> Starting Avora Energy deployment..."
echo ""
echo "==> Current DB configuration:"
echo "    DB_CONNECTION : ${DB_CONNECTION:-not set}"
echo "    DB_HOST       : ${DB_HOST:-not set}"
echo "    DB_PORT       : ${DB_PORT:-not set}"
echo "    DB_DATABASE   : ${DB_DATABASE:-not set}"
echo "    DB_USERNAME   : ${DB_USERNAME:-not set}"
echo "    DB_PASSWORD   : ${DB_PASSWORD:+[set, hidden]}"
echo "    DB_SSLMODE    : ${DB_SSLMODE:-not set}"
echo "    DB_URL        : ${DB_URL:+[set - this overrides all DB_* vars!]}"
echo ""

cd /var/www/html

# Wait for the database to be reachable before proceeding
echo "==> Waiting for database connection (up to 60s)..."
MAX_TRIES=20
COUNT=0
until php artisan db:show --no-interaction > /dev/null 2>&1; do
    COUNT=$((COUNT + 1))
    if [ $COUNT -ge $MAX_TRIES ]; then
        echo ""
        echo "======================================================"
        echo "ERROR: Database is unreachable after $((MAX_TRIES * 3))s."
        echo "Current configuration:"
        echo "  DB_HOST     = ${DB_HOST:-EMPTY - not set in Render!}"
        echo "  DB_PORT     = ${DB_PORT:-5432}"
        echo "  DB_DATABASE = ${DB_DATABASE:-EMPTY - not set in Render!}"
        echo "  DB_USERNAME = ${DB_USERNAME:-EMPTY - not set in Render!}"
        echo "  DB_SSLMODE  = ${DB_SSLMODE:-require}"
        echo ""
        echo "Common fixes:"
        echo "  1. Verify DB_HOST in Render matches Supabase Settings > Database"
        echo "  2. Verify DB_PASSWORD is correct (no extra spaces)"
        echo "  3. Verify DB_DATABASE=postgres (Supabase default)"
        echo "  4. Verify Supabase > Settings > Network has no IP restrictions"
        echo "======================================================"
        exit 1
    fi
    echo "  Attempt $COUNT/$MAX_TRIES — retrying in 3s..."
    sleep 3
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

echo "==> All done! Starting services..."
exec /usr/bin/supervisord -c /etc/supervisord.conf

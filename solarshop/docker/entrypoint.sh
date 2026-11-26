#!/bin/sh
set -e

echo "==> Starting Avora Energy deployment..."

cd /var/www/html

# Cache Laravel config, routes, and views for production performance
echo "==> Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations (force = no interactive prompts)
echo "==> Running database migrations..."
php artisan migrate --force

# Create symbolic link for storage (if not already exists)
echo "==> Linking storage..."
php artisan storage:link || true

echo "==> Starting services via Supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf

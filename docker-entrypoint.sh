#!/bin/sh
set -e

# ---------------------------------------------------------------------------
# Render routes traffic to the port given in $PORT (10000 by default).
# Caddy is configured to listen on :80 for local use; rewrite it if needed.
# ---------------------------------------------------------------------------
PORT="${PORT:-80}"
if [ "$PORT" != "80" ]; then
    sed -i "s/^:80 {/:${PORT} {/" /etc/caddy/Caddyfile
fi

# ---------------------------------------------------------------------------
# APP_KEY: Laravel requires a 32-byte key. Render's generateValue produces a
# raw base64 string; Laravel expects the "base64:" prefix, so normalise it.
# ---------------------------------------------------------------------------
if [ -z "$APP_KEY" ]; then
    echo "WARNING: APP_KEY is not set. Generating an ephemeral key - sessions will reset on every restart."
fi
export APP_KEY="$(php -r '
    $k = (string) getenv("APP_KEY");
    if ($k === "") { echo "base64:".base64_encode(random_bytes(32)); exit; }
    if (str_starts_with($k, "base64:")) { echo $k; exit; }
    $d = base64_decode($k, true);
    if ($d !== false && strlen($d) === 32) { echo "base64:".$k; exit; }
    if (strlen($k) === 64 && ctype_xdigit($k)) { echo "base64:".base64_encode(hex2bin($k)); exit; }
    echo $k;
')"

# ---------------------------------------------------------------------------
# Wait for the database (bounded - fail loudly instead of looping forever).
# ---------------------------------------------------------------------------
echo "Waiting for database at ${DB_HOST:-127.0.0.1}:${DB_PORT:-5432} (connection: ${DB_CONNECTION:-<unset>})..."
db_ready=0
tries=0
while [ "$tries" -lt 30 ]; do
    if php -r 'if (@fsockopen(getenv("DB_HOST") ?: "127.0.0.1", (int) (getenv("DB_PORT") ?: 5432), $e, $m, 2)) { exit(0); } exit(1);'; then
        db_ready=1
        break
    fi
    tries=$((tries + 1))
    sleep 2
done

if [ "$db_ready" != "1" ]; then
    echo "ERROR: database is unreachable after 60s. Current configuration:"
    echo "  DB_CONNECTION=${DB_CONNECTION:-<unset>}"
    echo "  DB_HOST=${DB_HOST:-<unset>}"
    echo "  DB_PORT=${DB_PORT:-<unset>}"
    echo "  DB_DATABASE=${DB_DATABASE:-<unset>}"
    echo "  DB_USERNAME=${DB_USERNAME:-<unset>}"
    exit 1
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Seed on first boot only (skip once the admins table has rows)
seed_needed() {
    php -r '
        require "vendor/autoload.php";
        $app = require "bootstrap/app.php";
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        try {
            exit(Illuminate\Support\Facades\DB::table("admins")->count() > 0 ? 1 : 0);
        } catch (Throwable $e) {
            exit(0);
        }'
}

if seed_needed; then
    echo "Empty database detected - seeding initial data..."
    php artisan db:seed --force --no-interaction
else
    echo "Database already seeded - skipping."
fi

# Cache configuration, routes and views
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link 2>/dev/null || true

# Start PHP-FPM in background
echo "Starting php-fpm..."
php-fpm -D

# Start Caddy (foreground)
echo "Starting Caddy on port ${PORT}..."
exec caddy run --config /etc/caddy/Caddyfile

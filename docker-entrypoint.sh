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
# Dump all DB-related env vars for debugging (safe — no password printed).
# ---------------------------------------------------------------------------
echo "=== Environment check ==="
echo "  DB_CONNECTION = ${DB_CONNECTION:-<unset>}"
echo "  DB_HOST       = ${DB_HOST:-<unset>}"
echo "  DB_PORT       = ${DB_PORT:-5432}"
echo "  DB_DATABASE   = ${DB_DATABASE:-<unset>}"
echo "  DB_USERNAME   = ${DB_USERNAME:-<unset>}"
echo "  APP_ENV       = ${APP_ENV:-<unset>}"
echo "========================="

# ---------------------------------------------------------------------------
# DB_CONNECTION: Render's dashboard may have an old stale value of 'database'
# (set manually before render.yaml existed). If DB_HOST is already populated
# by the fromDatabase injection, we know it IS PostgreSQL — fix it silently.
# Only hard-fail if DB_HOST is also missing (injection completely failed).
# ---------------------------------------------------------------------------
if [ -z "$DB_HOST" ]; then
    echo "ERROR: DB_HOST is not set. The fromDatabase env var injection may have failed."
    echo "Ensure the Postgres service name in render.yaml exactly matches the service in the Render dashboard."
    exit 1
fi

if [ -z "$DB_CONNECTION" ] || [ "$DB_CONNECTION" != "pgsql" ]; then
    echo "WARNING: DB_CONNECTION is '${DB_CONNECTION:-<empty>}' but DB_HOST is set — auto-correcting to 'pgsql'."
    echo "To fix permanently: set DB_CONNECTION=pgsql in the Render dashboard under avora-web > Environment."
    export DB_CONNECTION=pgsql
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
# Wait for the database. Free Postgres cold starts can exceed 60s, so allow
# up to ~3 minutes before giving up.
# ---------------------------------------------------------------------------
echo "Waiting for database at ${DB_HOST}:${DB_PORT:-5432}..."
db_ready=0
tries=0
while [ "$tries" -lt 90 ]; do
    if php -r 'if (@fsockopen(getenv("DB_HOST") ?: "127.0.0.1", (int) (getenv("DB_PORT") ?: 5432), $e, $m, 2)) { exit(0); } exit(1);'; then
        db_ready=1
        break
    fi
    tries=$((tries + 1))
    sleep 2
done

if [ "$db_ready" != "1" ]; then
    echo "ERROR: database is unreachable after ~180s. Current configuration:"
    echo "  DB_CONNECTION=${DB_CONNECTION:-<unset>}"
    echo "  DB_HOST=${DB_HOST:-<unset>}"
    echo "  DB_PORT=${DB_PORT:-<unset>}"
    echo "  DB_DATABASE=${DB_DATABASE:-<unset>}"
    echo "  DB_USERNAME=${DB_USERNAME:-<unset>}"
    exit 1
fi

echo "Database is reachable. Proceeding..."

# ---------------------------------------------------------------------------
# Clear any stale config cache that might have been baked into a prior image
# layer. This MUST run before migrate so Laravel reads live env vars.
# ---------------------------------------------------------------------------
echo "Clearing config cache..."
php artisan config:clear
php artisan cache:clear 2>/dev/null || true

# Run migrations (must succeed or the app cannot serve traffic)
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Cache configuration, routes and views BEFORE seeding so the first web
# request does not pay the cache-build cost.
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link 2>/dev/null || true

# ---------------------------------------------------------------------------
# Decide whether seeding is needed. Exit 0 => seed, exit 1 => already seeded.
# ---------------------------------------------------------------------------
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

needs_seed=0
if seed_needed; then
    needs_seed=1
fi

# ---------------------------------------------------------------------------
# Start PHP-FPM + Caddy FIRST so Render's health check (/up) can pass while
# the (slow) first-boot seed runs in the background. Free tier has only
# 0.1 CPU; importing ~1400 products can take several minutes and would
# otherwise blow the health-check window and kill the deploy.
# ---------------------------------------------------------------------------
echo "Starting php-fpm..."
php-fpm -D

echo "Starting Caddy on port ${PORT}..."
caddy run --config /etc/caddy/Caddyfile &
CADDY_PID=$!

if [ "$needs_seed" = "1" ]; then
    echo "Empty database detected - seeding initial data in the background..."
    (
        php artisan db:seed --force --no-interaction \
            && echo "SEED_OK: initial data seeded." \
            || echo "SEED_FAILED: seeding errored - re-run with: php artisan db:seed --force"
    ) &
fi

# Keep the container in the foreground as long as Caddy is alive.
wait "$CADDY_PID"

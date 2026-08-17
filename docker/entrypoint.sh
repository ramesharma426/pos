#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

echo "[entrypoint] Waiting for MySQL at ${DB_HOST:-db}:${DB_PORT:-3306} ..."
until mysqladmin ping -h"${DB_HOST:-db}" -P"${DB_PORT:-3306}" -u"${DB_USERNAME:-root}" -p"${DB_PASSWORD:-root}" --skip-ssl --silent >/dev/null 2>&1; do
    sleep 2
done
echo "[entrypoint] MySQL is up."

# Install PHP dependencies if vendor is missing (mounted volume starts empty)
if [ ! -f vendor/autoload.php ]; then
    echo "[entrypoint] Installing composer dependencies..."
    composer install --no-interaction --prefer-dist --no-progress
fi

# Ensure app key exists
if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    echo "[entrypoint] Generating application key..."
    php artisan key:generate --force
fi

# Storage symlink + writable dirs
php artisan storage:link 2>/dev/null || true
mkdir -p storage/app/public/products
chmod -R ug+rw storage bootstrap/cache 2>/dev/null || true

echo "[entrypoint] Running migrations..."
php artisan migrate --force || echo "[entrypoint] WARNING: migrations failed (continuing so container stays up for debugging)"

exec "$@"

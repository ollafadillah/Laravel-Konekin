#!/bin/sh
set -eu

SQLITE_DB="${DB_SQLITE_DATABASE:-/var/www/html/storage/database.sqlite}"
SQLITE_DIR="$(dirname "$SQLITE_DB")"

mkdir -p "$SQLITE_DIR"
touch "$SQLITE_DB"

mkdir -p \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

if [ "${AUTO_STORAGE_LINK:-true}" = "true" ]; then
    php artisan storage:link --force >/dev/null 2>&1 || true
fi

if [ "${AUTO_MIGRATE:-false}" = "true" ]; then
    php artisan migrate --force --no-interaction
fi

exec "$@"

#!/bin/sh
set -eu

cd /app

mkdir -p \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    /tmp/nginx/client_temp \
    /tmp/nginx/proxy_temp \
    /tmp/nginx/fastcgi_temp \
    /tmp/nginx/uwsgi_temp \
    /tmp/nginx/scgi_temp \
    /tmp/supervisor

wait_for_tcp() {
    host="$1"
    port="$2"
    timeout_seconds="$3"

    php -r '
        $host = $argv[1];
        $port = (int) $argv[2];
        $deadline = time() + (int) $argv[3];
        do {
            $socket = @fsockopen($host, $port, $errno, $errstr, 2);
            if ($socket) {
                fclose($socket);
                exit(0);
            }
            sleep(1);
        } while (time() < $deadline);
        fwrite(STDERR, "Timeout waiting for {$host}:{$port}\n");
        exit(1);
    ' "$host" "$port" "$timeout_seconds"
}

if [ "${WAIT_FOR_DB:-true}" = "true" ] && [ -n "${DB_HOST:-}" ]; then
    echo "Waiting for database at ${DB_HOST}:${DB_PORT:-27017}..."
    wait_for_tcp "$DB_HOST" "${DB_PORT:-27017}" "${DB_WAIT_TIMEOUT:-45}"
fi

if [ "${AUTO_STORAGE_LINK:-true}" = "true" ]; then
    php artisan storage:link --force --no-interaction >/dev/null 2>&1 || true
fi

if [ "${AUTO_MIGRATE:-false}" = "true" ]; then
    echo "Running Laravel migrations..."
    php artisan migrate --force --no-interaction
fi

if [ "${AUTO_OPTIMIZE:-true}" = "true" ]; then
    echo "Refreshing Laravel runtime cache..."
    php artisan optimize:clear --no-interaction >/dev/null 2>&1 || true
    php artisan config:cache --no-interaction
    php artisan route:cache --no-interaction || true
    php artisan view:cache --no-interaction || true
fi

exec "$@"

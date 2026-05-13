#!/bin/bash

set -e

# Wait for MongoDB to be ready
if [ ! -z "$DB_HOST" ]; then
    echo "Waiting for MongoDB to be ready..."
    timeout 30 bash -c "until nc -z $DB_HOST $DB_PORT; do sleep 1; done" || true
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear cache
echo "Clearing application cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recreate cache
echo "Rebuilding cache..."
php artisan config:cache
php artisan route:cache

# Set permissions
echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache

# Execute the main command
exec "$@"

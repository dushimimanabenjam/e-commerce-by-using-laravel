#!/bin/bash
set -e

echo "==> Starting Laravel on Render..."

# Create .env from environment variables if it doesn't exist
if [ ! -f /var/www/.env ]; then
    echo "==> Creating .env file..."
    cp /var/www/.env.example /var/www/.env
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "==> Generating APP_KEY..."
    php artisan key:generate --force
else
    echo "APP_KEY=$APP_KEY" >> /var/www/.env
fi

# Write all environment variables to .env
echo "==> Writing environment variables..."
printenv | grep -E "^(APP_|DB_|MAIL_|QUEUE_|SESSION_|REDIS_|CACHE_|AWS_|MIX_)" | while IFS='=' read -r key value; do
    # Remove existing line and add updated one
    sed -i "/^${key}=/d" /var/www/.env
    echo "${key}=${value}" >> /var/www/.env
done

# Set storage permissions
echo "==> Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Create PHP-FPM socket directory
mkdir -p /var/run/php

# Run database migrations
echo "==> Running migrations..."
php artisan migrate --force

# Clear and cache config for production
echo "==> Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

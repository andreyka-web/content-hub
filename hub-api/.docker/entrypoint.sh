#!/bin/sh
set -e

echo "[*] Running composer install..."
composer install

# Volume mount overwrites the build-time .env; recreate from example if missing
if [ ! -f /var/www/html/.env ]; then
    echo "[*] .env not found, copying from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

if [ ! -f /var/www/html/.env.testing ]; then
    echo "[*] .env.testing not found, copying from .env.testing.example..."
    cp /var/www/html/.env.testing.example /var/www/html/.env.testing
fi

echo "[*] Running artisan config:clear..."
php artisan config:clear


echo "[*] Waiting for hub-db to be ready"
while ! nc -z hub-db 3306; do
    echo -n "*"
    sleep 1
done

echo "[*] Running artisan key:generate..."
php artisan key:generate

echo "[*] Running artisan key:generate for testing..."
php artisan key:generate --env=testing

echo "[*] Running database migrations and seeding..."
php artisan migrate:fresh --seed --force

echo "[+] Migrations complete. Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf

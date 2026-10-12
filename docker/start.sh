#!/bin/sh
set -eu
cd /var/www/html
: "${APP_KEY:?Configura APP_KEY en Render antes de iniciar el servicio}"
port="${PORT:-80}"
case "$port" in ''|*[!0-9]*) echo 'PORT debe ser numérico' >&2; exit 1;; esac
sed -i "s/Listen 80/Listen ${port}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${port}>/" /etc/apache2/sites-available/000-default.conf
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
# Datos de demostración de solo lectura: regenerar SQLite al iniciar es deliberado.
export DB_CONNECTION=sqlite
export DB_DATABASE=/var/www/html/database/database.sqlite
if [ ! -f "$DB_DATABASE" ]; then
    touch "$DB_DATABASE"
fi
php artisan migrate --force --no-interaction
php artisan demo:preparar --no-interaction
chown -R www-data:www-data storage bootstrap/cache database
php artisan config:cache
php artisan route:cache
php artisan view:cache
exec apache2-foreground

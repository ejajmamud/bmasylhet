#!/bin/sh
set -eu

mkdir -p \
    /var/www/html/application/cache \
    /var/www/html/application/logs \
    /var/www/html/uploads

if [ -d /opt/app-uploads ]; then
    cp -an /opt/app-uploads/. /var/www/html/uploads/ 2>/dev/null || true
fi

chown -R www-data:www-data \
    /var/www/html/application/cache \
    /var/www/html/application/logs \
    /var/www/html/uploads

exec "$@"

#!/bin/sh
set -eu

mkdir -p \
    /var/www/html/application/cache \
    /var/www/html/application/logs \
    /var/www/html/uploads \
    /var/www/html/uploads/private/cadets \
    /var/www/private

if [ -d /opt/app-uploads ]; then
    cp -an /opt/app-uploads/. /var/www/html/uploads/ 2>/dev/null || true
fi

chown -R www-data:www-data \
    /var/www/html/application/cache \
    /var/www/html/application/logs \
    /var/www/html/uploads

if [ ! -e /var/www/private/cadet-documents ]; then
    ln -s /var/www/html/uploads/private/cadets /var/www/private/cadet-documents
fi

attempt=0
until php /var/www/html/index.php cadet_migrate index >/tmp/cadet-migrate.log 2>&1; do
    attempt=$((attempt + 1))
    if [ "$attempt" -ge 20 ]; then
        cat /tmp/cadet-migrate.log >&2
        exit 1
    fi
    sleep 3
done

exec "$@"

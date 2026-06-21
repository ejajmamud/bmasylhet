FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd intl mysqli opcache pdo_mysql zip \
    && a2enmod expires headers rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY docker/apache-site.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html
COPY uploads /opt/app-uploads
COPY docker/entrypoint.sh /usr/local/bin/bma-entrypoint

RUN chmod +x /usr/local/bin/bma-entrypoint \
    && mkdir -p /var/www/html/application/cache /var/www/html/application/logs /var/www/html/uploads \
    && chown -R www-data:www-data \
        /var/www/html/application/cache \
        /var/www/html/application/logs \
        /var/www/html/uploads

ENV CI_ENV=production

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD curl --fail --silent http://127.0.0.1/ >/dev/null || exit 1

ENTRYPOINT ["bma-entrypoint"]
CMD ["apache2-foreground"]

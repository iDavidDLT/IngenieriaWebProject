FROM php:8.4-apache
RUN apt-get update && apt-get install -y --no-install-recommends libicu-dev libonig-dev libzip-dev libsqlite3-dev unzip \
    && docker-php-ext-install pdo_sqlite mbstring intl zip opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache database
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN chmod +x docker/start.sh
EXPOSE 80
CMD ["sh", "docker/start.sh"]

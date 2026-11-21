FROM php:8.4-fpm-alpine

# Install system dependencies & PHP extensions untuk Laravel + MySQL
RUN apk add --no-cache git unzip libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath zip

# Ambil Composer dari official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy seluruh source code project
COPY . .

# Install vendor production & atur permission folder storage
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]

FROM php:8.4-fpm-alpine

# 1. Install dependencies sistem & PHP Extensions
RUN apk add --no-cache git unzip libzip-dev oniguruma-dev libpng-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 2. Ambil Composer terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Set working directory
WORKDIR /var/www/html

# 4. Copy seluruh project
COPY . .

# 5. Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction

# 6. Permission folder storage & cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]

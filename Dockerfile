FROM node:20-alpine AS nodebase
WORKDIR /app
COPY package.json package-lock.json* yarn.lock* ./
RUN npm install
COPY . .
RUN npm run build   

# --- Composer Dependencies Stage (optional, for better cache) ---
FROM composer:2.8.9 AS composerbase
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-dev

# --- PHP (main) stage ---
FROM php:8.2-fpm-alpine AS phpbase

# Install system packages and PHP extensions
RUN apk add --no-cache \
    nginx bash curl supervisor libpng libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
    zip unzip git icu-dev oniguruma-dev

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql gd intl mbstring opcache

# Composer
COPY --from=composer:2.8.9 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html


# Copy node modules from the Node deps stage
COPY composer.json composer.lock ./
COPY --from=composerbase /app/vendor ./vendor
COPY . .

# NOW build assets
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# -- COPY BUILT FRONTEND ASSETS --
COPY --from=nodebase /app/public/build ./public/build

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Nginx and supervisor config
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf

EXPOSE 80

CMD php artisan config:cache && /usr/bin/supervisord -c /etc/supervisord.conf

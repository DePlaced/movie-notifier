FROM node:20-alpine AS nodebuild
WORKDIR /app

COPY package.json package-lock.json* yarn.lock* ./
RUN npm install

COPY . .
RUN npm run build

# ---- Main PHP/Nginx image ----
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx \
    bash \
    curl \
    supervisor \
    libpng \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    icu-dev \
    oniguruma-dev

RUN docker-php-ext-configure gd \
    --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql gd intl mbstring opcache

COPY --from=composer:2.8.9 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# COPY BUILT ASSETS FROM NODE BUILD
COPY --from=nodebuild /app/public/build /var/www/html/public/build

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf

EXPOSE 80

# Startup: cache config (AFTER Render injects env vars), then start services
CMD php artisan config:cache && /usr/bin/supervisord -c /etc/supervisord.conf

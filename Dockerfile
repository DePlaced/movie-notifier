FROM php:8.2-fpm-alpine as base

# System deps
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
    oniguruma-dev \
    postgresql-dev


# PHP extensions
RUN docker-php-ext-configure gd \
    --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_pgsql gd intl mbstring opcache

# Composer
COPY --from=composer:2.8.9 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Copy application files
COPY . .

# Run composer scripts and optimize autoloader
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader

# Node/Vite build
RUN npm ci && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Nginx and supervisor config
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
# ---- Build Stage: Frontend ----
FROM node:20-alpine AS frontend
WORKDIR /app
COPY solarshop/package*.json ./
RUN npm ci
COPY solarshop/vite.config.js ./
COPY solarshop/resources ./resources
RUN npm run build

# ---- Build Stage: PHP Dependencies ----
FROM composer:2 AS vendor
WORKDIR /var/www/html
COPY solarshop/composer*.json ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# ---- Runtime Stage ----
FROM php:8.3-fpm-alpine AS runtime

# Install system dependencies
RUN apk add --no-cache \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    libzip-dev zip unzip git \
    linux-headers \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_pgsql zip bcmath opcache

# Install Caddy
RUN apk add --no-cache caddy

WORKDIR /var/www/html

# Copy PHP dependencies
COPY --from=vendor /var/www/html/vendor ./vendor

# Copy application code from solarshop subdirectory
COPY solarshop/ .

# Copy built frontend assets
COPY --from=frontend /app/public/build ./public/build

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Create Caddy directory and config
RUN mkdir -p /etc/caddy /run/php-fpm

# Copy Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# PHP-FPM config for socket
RUN echo "listen = /run/php-fpm.sock" > /usr/local/etc/php-fpm.d/zz-render.conf \
    && echo "listen.owner = www-data" >> /usr/local/etc/php-fpm.d/zz-render.conf \
    && echo "listen.group = www-data" >> /usr/local/etc/php-fpm.d/zz-render.conf \
    && echo "listen.mode = 0660" >> /usr/local/etc/php-fpm.d/zz-render.conf

EXPOSE 80 443

# Start script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["caddy", "run", "--config", "/etc/caddy/Caddyfile"]
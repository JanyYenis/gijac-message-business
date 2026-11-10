FROM php:8.3-fpm

# Extensiones que necesitan tus paquetes: dompdf/phpword/excel (gd, zip, xml, mbstring),
# libphonenumber (intl), n8n/Meta (curl), mysql, colas (pcntl), cifras (bcmath)
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip curl libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev \
        libicu-dev libonig-dev libxml2-dev ffmpeg \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" gd zip intl mbstring bcmath pdo_mysql exif pcntl opcache sockets \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-gijac.ini

WORKDIR /var/www/gijac-message-business

# El código se monta como volumen (ver docker-compose.yml)

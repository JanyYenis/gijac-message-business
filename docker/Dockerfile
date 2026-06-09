FROM php:8.4-fpm

# 1. Instala dependencias de sistema + extensiones PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    supervisor \
    cron && \
    docker-php-ext-install \
      pdo \
      pdo_mysql \
      zip \
      gd \
      bcmath        # ← agregamos bcmath

# 2. Node.js para Webpack/Vite
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# 3. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. Copia el código y ajusta permisos/git
COPY . .

# Marca el directorio como seguro para Git
RUN git config --global --add safe.directory /var/www/html

# (Opcional) Asegura que www-data sea dueño, evita issues de permisos
RUN chown -R www-data:www-data /var/www/html

# 5. Instala dependencias y compila assets
RUN composer install --no-interaction --optimize-autoloader && \
    npm install && \
    npm run production

EXPOSE 8000

# 6. Arranque por defecto: Laravel built‑in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

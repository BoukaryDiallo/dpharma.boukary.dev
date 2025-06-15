FROM php:8.2-fpm
LABEL authors="boukary"

# Dépendances système
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    libzip-dev libpq-dev && \
    docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# App code
WORKDIR /var/www
COPY . /var/www

# Droits
RUN chown -R www-data:www-data /var/www

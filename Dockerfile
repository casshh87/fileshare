FROM php:8.1-cli

# Устанавливаем зависимости для GD и PostgreSQL
RUN apt-get update && \
    apt-get install -y libpq-dev libjpeg-dev libpng-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-jpeg --with-freetype && \
    docker-php-ext-install gd pdo_pgsql


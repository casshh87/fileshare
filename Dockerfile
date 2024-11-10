FROM php:8.1-cli

# Устанавливаем зависимости для imagick 
RUN apt-get update && \
    apt-get install -y libmagickwand-dev && \
    pecl install imagick && \
    docker-php-ext-enable imagick 




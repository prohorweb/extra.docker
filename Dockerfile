FROM php:8.2-fpm

# Устанавливаем системные зависимости + curl для Node.js
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Настраиваем и устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql zip exif intl

# Копируем Composer из официального Docker-образа
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libonig-dev libxml2-dev zip unzip git \
    libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

WORKDIR /var/www/html

COPY . /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader --no-interaction; else echo "composer.json not found, skipping composer install"; fi

RUN chown -R www-data:www-data /var/www/html \
    && mkdir -p /var/www/html/runtime /var/www/html/web/assets \
    && chmod -R 775 /var/www/html/runtime /var/www/html/web/assets

EXPOSE 9000
CMD ["php-fpm"]

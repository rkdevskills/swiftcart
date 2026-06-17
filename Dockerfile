FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip nodejs npm \
    libpng-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip gd exif

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN npm ci
RUN npm run build

EXPOSE 10000

CMD php artisan config:clear && \
    php artisan migrate --force && \
    (php artisan db:seed --force || true) && \
    php artisan serve --host=0.0.0.0 --port=10000
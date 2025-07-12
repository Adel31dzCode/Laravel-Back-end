FROM php:8.2-fpm

WORKDIR /var/www

# ✅ إضافة PostgreSQL Client libraries
RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    sqlite3 libsqlite3-dev libpq-dev # <--- هذا مهم لـ pdo_pgsql

# ✅ إضافة pdo_pgsql
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www
COPY --chown=www-data:www-data . /var/www

RUN chmod -R 755 /var/www
RUN composer install

# ✅ نسخ .env فقط إذا موجود
RUN [ -f .env.example ] && cp .env.example .env || echo ".env.example not found, skipping"

RUN php artisan key:generate

# ✅ تنفيذ المايغريشن (إنشاء الجداول)
RUN php artisan migrate --force

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000

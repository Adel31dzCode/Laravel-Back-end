# المرحلة الأولى: بناء أصول Vite
FROM node:20 AS node-builder

WORKDIR /app

COPY package*.json ./
RUN yarn install

COPY . .
RUN yarn build

# المرحلة الثانية: إعداد Laravel
FROM php:8.2-fpm

# تثبيت المتطلبات
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# تثبيت Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY --from=node-builder /app /var/www

RUN composer install --no-dev --optimize-autoloader

# نسخ ملفات الأصول المبنية
COPY --from=node-builder /app/public/build /var/www/public/build

# إعطاء صلاحيات للمجلدات
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# نسخ ملف الإعداد لتشغيل Laravel
COPY ./docker/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]

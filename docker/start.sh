#!/bin/bash

echo "🧩 بدء إعداد Laravel..."

# تشغيل كاش الكونفيغ والراوت
php artisan config:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache

# تنفيذ الترحيلات (migrations) إن وجدت
php artisan migrate --force

# تشغيل PHP-FPM كخدمة
echo "🚀 Laravel جاهز على Render!"
exec php-fpm

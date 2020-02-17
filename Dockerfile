FROM php:7.2.2-fpm

CMD php artisan serve --host=localhost --port=8000
EXPOSE 8000

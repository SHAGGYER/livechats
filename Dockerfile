FROM php:7.2.2-fpm

WORKDIR /app
COPY . /app

EXPOSE 8000
CMD php artisan serve --host=localhost --port=8000

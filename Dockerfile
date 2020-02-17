FROM php:7.2.2-fpm

WORKDIR /app
COPY . /app

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000

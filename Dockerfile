FROM php:7.2.2-fpm

WORKDIR /app
COPY . /app

CMD php artisan serve --host=0.0.0.0 --port=8000
EXPOSE 8000

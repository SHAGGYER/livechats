FROM php:7.2.2-fpm

WORKDIR /app
COPY . /app

RUN php artisan key:generate

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000

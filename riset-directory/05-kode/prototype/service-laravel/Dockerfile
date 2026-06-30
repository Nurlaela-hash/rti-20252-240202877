FROM php:8.2-cli-alpine

WORKDIR /app

RUN docker-php-ext-install pdo pdo_mysql

COPY . .

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
FROM php:8.2-cli-alpine
WORKDIR /app
RUN docker-php-ext-install pdo pdo_mysql
COPY . .
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

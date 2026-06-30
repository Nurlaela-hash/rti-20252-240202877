FROM php:8.4-cli-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache git unzip zip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy Composer files
COPY composer.json composer.lock ./

# Install dependencies first (leverages Docker cache)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy the rest of the application
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --no-dev --optimize

# Set permissions for storage & bootstrap/cache
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
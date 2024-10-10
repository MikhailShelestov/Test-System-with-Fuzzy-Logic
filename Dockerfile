# Use PHP 8.2 with FPM
FROM php:8.2-fpm

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the project files
WORKDIR /var/www/symfony
COPY . .

# Install Symfony dependencies
RUN composer install

# Set permissions
RUN chown -R www-data:www-data /var/www/symfony/var

# Expose the port for PHP
EXPOSE 9000

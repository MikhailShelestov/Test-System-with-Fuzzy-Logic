# Dockerfile
FROM php:8.2-fpm

# Установка нужных расширений PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копирование проекта
WORKDIR /var/www/symfony
COPY . .

# Установка зависимостей Symfony
RUN composer install

# Настройка прав
RUN chown -R www-data:www-data /var/www/symfony/var

# Экспонируем порт для PHP
EXPOSE 9000

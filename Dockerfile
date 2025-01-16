# Используем PHP 8.3 с Apache
FROM php:8.3-apache

# Устанавливаем необходимые пакеты
RUN apt-get update && apt-get install -y \
  zip \
  unzip \
  git \
  curl \
  libonig-dev \
  libxml2-dev \
  libzip-dev \
  && docker-php-ext-install \
  pdo_mysql \
  mysqli \
  mbstring \
  zip \
  opcache

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка прав доступа
RUN chown -R www-data:www-data /var/www

# Настройка Apache (опционально, измените по необходимости)
RUN a2enmod rewrite

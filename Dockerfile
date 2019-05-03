FROM php:7.2.17-fpm

WORKDIR /var/www/api.vost.test

# Install dependencies
RUN apt-get update && apt-get install -y \
build-essential \
locales \
zlib1g-dev \
zip \
vim \
unzip \
git \
curl \
libmcrypt-dev

# Install extensions
RUN pecl install mcrypt-1.0.2 && docker-php-ext-enable mcrypt
RUN pecl install xdebug-2.7.1 && docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo_mysql mbstring zip pcntl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 9000

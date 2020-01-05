FROM php:7.3.11-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    git \
    libmcrypt-dev \
    locales \
    unzip \
    vim \
    libzip-dev \
    zip \
    --no-install-recommends && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pcntl \
    pdo_mysql \
    zip

RUN pecl channel-update pecl.php.net \
    && printf "\n" | pecl install mcrypt-1.0.3 \
    && printf "\n" | pecl install xdebug-2.9.0 \
    && printf "\n" | pecl install redis-5.0.2 \
    && docker-php-ext-enable mcrypt xdebug redis

COPY conf/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY conf/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/api.vost.test

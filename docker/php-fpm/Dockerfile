FROM php:7.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    git \
    curl \
    libzip-dev \
    icu-dev \
    mysql-client \
    oniguruma-dev \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    intl \
    opcache \
    bcmath

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /srv/app

CMD ["php-fpm"]
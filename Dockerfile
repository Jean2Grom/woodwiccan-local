
FROM composer:lts as deps

WORKDIR /app

RUN --mount=type=bind,source=composer.json,target=composer.json \
#    --mount=type=bind,source=composer.lock,target=composer.lock \
#    --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction

FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql && a2enmod rewrite && docker-php-ext-enable mysqli pdo pdo_mysql

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"


COPY --from=deps app/vendor/ /var/www/html/vendor
COPY . /var/www/html

USER www-data

WORKDIR /var/www/html

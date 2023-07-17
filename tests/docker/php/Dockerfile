ARG PHP_VERSION
FROM --platform=linux/amd64 php:${PHP_VERSION}-cli-alpine

RUN apk add unzip zlib-dev libzip-dev

RUN docker-php-ext-install zip pcntl opcache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

ADD . /var/www
WORKDIR /var/www

RUN composer update --prefer-dist --no-interaction
CMD ["sleep", "infinity"]
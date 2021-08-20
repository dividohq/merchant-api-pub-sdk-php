ARG PHP_VERSION
ARG ALPINE_VERSION
FROM divido/php:${PHP_VERSION}-fpm-alpine${ALPINE_VERSION}-dev

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache git $PHPIZE_DEPS \
    && pecl install pcov \
    && docker-php-ext-enable pcov

WORKDIR /opt/divido/app
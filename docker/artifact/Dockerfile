ARG PHP_VERSION=8.0.13


FROM jorge07/alpine-php:${PHP_VERSION} as php-base
# List your dependencies here
ARG PHP_MODULES="php8-pdo php8-pdo_mysql php8-pecl-amqp php8-tokenizer"
RUN echo "http://dl-4.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories \
    && apk add -U --no-cache ${PHP_MODULES}

FROM jorge07/alpine-php:${PHP_VERSION}-dev as php-dev
# Install your project DEVELOPMENT dependencies here
ARG PHP_MODULES="php8-pdo php8-pdo_mysql php8-pecl-amqp php8-tokenizer php8-posix php8-simplexml php8-xmlwriter"
RUN echo "http://dl-4.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories \
    && apk add -U --no-cache ${PHP_MODULES}

ENV PHP_INI_DIR /etc/php8

FROM php-dev as builder

WORKDIR /app

ENV APP_ENV prod
ENV APP_SECRET default-secret

COPY composer.json /app
COPY composer.lock /app
COPY symfony.lock /app

RUN composer install --no-ansi --no-scripts --no-dev --no-interaction --no-progress --optimize-autoloader

COPY bin /app/bin
COPY config /app/config
COPY src /app/src
COPY public /app/public

RUN composer run-script post-install-cmd

FROM php-base as php

ENV APP_ENV prod

WORKDIR /app

COPY --from=builder /app /app

FROM nginx:1.17-alpine as nginx

ENV APP_ENV prod

WORKDIR /app

COPY etc/artifact/nginx/nginx.conf /etc/nginx/conf.d/default.conf

COPY --from=builder /app/public /app/public
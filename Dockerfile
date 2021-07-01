FROM php:8-alpine

RUN apk --no-cache add postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

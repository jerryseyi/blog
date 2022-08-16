FROM php:fpm-alpine

RUN apk add --no-cache $PHPIZE_DEPS oniguruma-dev libzip-dev curl-dev && docker-php-ext-install pdo pdo_mysql mbstring zip curl && pecl install xdebug redis && docker-php-ext-enable xdebug redis
RUN apk update && apk add curl && \
  curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

WORKDIR /app
ENV PATH /app/node_modules/.bin:$PATH
ENV SHELL=/bin/sh

COPY package*.json ./
# COPY redis.conf /usr/local/etc/redis/redis.conf
# CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]

CMD php artisan serve --host=0.0.0.0 --port=8000
EXPOSE 8000

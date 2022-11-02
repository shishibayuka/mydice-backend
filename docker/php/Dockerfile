FROM php:7.4-fpm-alpine3.12 AS local
# https://github.com/docker-library/php/blob/master/7.4/alpine3.12/fpm/Dockerfile
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf

RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    zlib-dev \
    mariadb-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip pdo_mysql bcmath
#Composer install
COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV COMPOSER_MEMORY_LIMIT -1
ENV PATH $PATH:/composer/vendor/bin

WORKDIR /var/www

RUN composer global require "laravel/installer"

FROM local AS remote

COPY ./src /var/www

RUN composer install
RUN composer swagger
RUN chmod -R 777 /var/www/storage/

VOLUME /var/www/public

RUN echo '* * * * * php /var/www/artisan schedule:run 1>> /dev/stdout 2>&1' >> /etc/crontabs/root

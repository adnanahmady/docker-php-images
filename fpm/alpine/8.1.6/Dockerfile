FROM composer:2.2.12 as composer
FROM php:8.1.6-fpm-alpine3.15

RUN apk --no-cache add sudo
RUN adduser -D "docker" docker \
    && echo 'docker ALL=(ALL) NOPASSWD: ALL' > /etc/sudoers.d/docker \
    && chmod 0440 /etc/sudoers.d/docker

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN sudo echo "@community http://dl-cdn.alpinelinux.org/alpine/edge/main" >> /etc/apk/repositories \
    && sudo echo "@community http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories \
    && apk update
RUN sudo apk update && sudo apk --no-cache add supervisor
RUN sudo apk --no-cache add zip \
    unzip \
    git
#RUN sudo apk update && sudo apk --no-cache add libpq libpng libzip
RUN sudo apk --update --no-cache add libpq-dev libpng-dev libzip-dev
RUN apk add --update --no-cache freetype-dev \
    libpng-dev \
    jpeg-dev \
    libjpeg-turbo-dev
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql
#RUN docker-php-ext-install -j$(nproc) iconv \
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install zip
RUN docker-php-ext-install sockets
RUN docker-php-ext-install bcmath
RUN sudo apk --update --no-cache add autoconf \
    pkg-config \
    libmcrypt-dev \
    libcurl4-openssl-dev \
    pkg-config
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN pecl install mongodb \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install opcache
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/docker-php-ext-memory_limit.ini \
    && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/docker-php-ext-mongo.ini \
    && echo "max_file_uploads=100" > /usr/local/etc/php/conf.d/docker-php-ext-max_file_uploads.ini \
    && echo "post_max_size=200M" > /usr/local/etc/php/conf.d/docker-php-ext-post_max_size.ini \
    && echo "upload_max_filesize=200M" > /usr/local/etc/php/conf.d/docker-php-ext-upload_max_filesize.ini \
    && echo "max_execution_time=99999999999" > /usr/local/etc/php/conf.d/docker-php-set-max-execution-timeout.ini

USER docker

FROM composer:2.2.12 as composer
FROM php:8.1.6-fpm-alpine3.15 as base

RUN echo "@community http://dl-cdn.alpinelinux.org/alpine/edge/main" >> /etc/apk/repositories \
    && echo "@community http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
        mongodb \
        redis
RUN docker-php-ext-enable xdebug \
        mongodb \
        redis
RUN apk add --update --no-cache libpq-dev \
    openssl-dev \
    libpng-dev \
    libzip-dev \
    libmcrypt-dev \
    libressl-dev \
    zip \
    unzip \
    git \
    sudo \
    bash \
    freetype-dev \
    libpng-dev \
    jpeg-dev \
    libjpeg-turbo-dev

RUN docker-php-ext-install opcache \
        pcntl \
        pdo \
        pdo_pgsql \
        pdo_mysql \
        zip \
        sockets \
        bcmath

RUN docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg
RUN apk del -fr --purge .build-deps
FROM php:8.1.6-fpm-alpine3.15

RUN apk add --no-cache --update less \
    sudo \
    grep \
	libpq-dev \
    libpng-dev \
    libzip-dev \
    libmcrypt-dev \
    libressl-dev \
    freetype-dev \
    libpng-dev \
    jpeg-dev \
    libjpeg-turbo-dev

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=base /usr/bin/zip /usr/bin/zip
COPY --from=base /usr/bin/unzip /usr/bin/unzip
COPY --from=base /usr/bin/git /usr/bin/git
COPY --from=base /usr/lib/libpcre2-8.so.0 /usr/lib/libpcre2-8.so.0
COPY --from=base /bin/ /bin/
COPY --from=base /usr/local/lib/ /usr/local/lib/
COPY --from=base /usr/local/etc/ /usr/local/etc/
COPY --from=base /usr/local/include/ /usr/local/include/

RUN adduser -D "docker" docker \
    && echo 'docker ALL=(ALL) NOPASSWD: ALL' > /etc/sudoers.d/docker \
    && chmod 0440 /etc/sudoers.d/docker

RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/docker-php-ext-memory_limit.ini \
    && echo "max_file_uploads=100" > /usr/local/etc/php/conf.d/docker-php-ext-max_file_uploads.ini \
    && echo "post_max_size=200M" > /usr/local/etc/php/conf.d/docker-php-ext-post_max_size.ini \
    && echo "upload_max_filesize=200M" > /usr/local/etc/php/conf.d/docker-php-ext-upload_max_filesize.ini \
    && echo "max_execution_time=99999999999" > /usr/local/etc/php/conf.d/docker-php-set-max-execution-timeout.ini \
    && echo 'opcache.enable=1' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo 'opcache.jit=tracing' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache-jit.ini \
    && echo 'opcache.jit_buffer_size=500M' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache-jit.ini \
    && echo 'opcache.enable_cli=1' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache-cli.ini \
    && echo 'xdebug.mode=off' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug-configuration.ini

USER docker
VOLUME /home/docker
COPY --chown=docker:docker .bashrc /home/docker/.bashrc
USER root

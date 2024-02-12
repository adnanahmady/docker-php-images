FROM composer:2.6.6 as composer
FROM php:8.3-fpm-alpine as base

RUN echo "@community http://dl-cdn.alpinelinux.org/alpine/edge/main" >> /etc/apk/repositories \
    && echo "@community http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories

RUN apk add --update linux-headers \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
        mongodb \
        redis
RUN docker-php-ext-enable xdebug \
        mongodb \
        redis
RUN apk add --update --no-cache libpq-dev \
    libpng-dev \
    libzip-dev \
    libmcrypt-dev \
    icu-dev \
    zip \
    unzip \
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
FROM php:8.3-fpm-alpine

RUN apk add --no-cache --update less \
    sudo \
    grep \
    git \
    zsh \
	libpq-dev \
    libpng-dev \
    libzip-dev \
    libmcrypt-dev \
    freetype-dev \
    libpng-dev \
    jpeg-dev \
    openrc \
    libjpeg-turbo-dev

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=base /usr/bin/zip /usr/bin/zip
COPY --from=base /usr/bin/unzip /usr/bin/unzip
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
COPY --chown=docker:docker .bashrc /home/docker/.bashrc
COPY --chown=docker:docker .zshrc /home/docker/.zshrc
COPY --chown=docker:docker .oh-my-zsh /home/docker/.oh-my-zsh
USER root
COPY --chown=root:root .bashrc /root/.bashrc
COPY --chown=root:root .zshrc /root/.zshrc
COPY --chown=root:root .oh-my-zsh /root/.oh-my-zsh

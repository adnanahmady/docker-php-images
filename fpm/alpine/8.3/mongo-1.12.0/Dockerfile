FROM adnanahmady/php:8.3-fpm-alpine as base

RUN apk add --no-cache --update --virtual .build-deps $PHPIZE_DEPS
RUN pecl uninstall mongodb \
    && pecl install mongodb-1.12.0
RUN docker-php-ext-enable mongodb 
RUN apk del -fr --purge .build-deps

FROM adnanahmady/php:8.3-fpm-alpine

COPY --from=base /usr/local/lib/ /usr/local/lib/
COPY --from=base /usr/local/etc/ /usr/local/etc/
COPY --from=base /usr/local/include/ /usr/local/include/

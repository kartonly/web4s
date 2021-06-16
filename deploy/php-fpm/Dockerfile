FROM php:7.4.14-fpm-alpine3.12

# Install intl extension
RUN apk add --no-cache \
    icu-dev \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-enable intl \
    && rm -rf /tmp/*

# Install mbstring extension
RUN apk add --no-cache \
    oniguruma-dev \
    && docker-php-ext-install mbstring \
    && docker-php-ext-enable mbstring \
    && rm -rf /tmp/*

RUN mkdir -p /usr/src/php/ext/mongodb \
    && curl -fsSL --ipv4 https://pecl.php.net/get/mongodb | tar xvz -C "/usr/src/php/ext/mongodb" --strip 1 \
    && docker-php-ext-install mongodb

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

ENV DATA_ROOT /data

WORKDIR ${DATA_ROOT}

RUN mkdir -p ${DATA_ROOT}/public
RUN mkdir -p ${DATA_ROOT}/var/log
RUN chmod -R 777 ${DATA_ROOT}/var/log

COPY deploy/php-fpm/php-fpm.d /usr/local/etc/php-fpm.d
COPY .env* ${DATA_ROOT}/
COPY composer.* ${DATA_ROOT}/
COPY bin/ bin/
COPY config/ config/
COPY src/ src/
COPY public/ public/
COPY vendor/ vendor/


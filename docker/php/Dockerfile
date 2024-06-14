FROM php:8.3.8-fpm-alpine3.20

ARG XDEBUG_VERSION=3.3.2
ARG COMPOSER_VERSION=2.7.7

# Install extensions
RUN apk add --no-cache \
    git \
    curl \
    zip \
    libpq-dev  \
    icu-dev \
    linux-headers \
    $PHPIZE_DEPS \
&& docker-php-ext-install \
    intl \
    pdo_pgsql \
    opcache \
;

# Install apcu
RUN pecl install apcu && docker-php-ext-enable apcu;

# Install xdebug
RUN pecl install xdebug-${XDEBUG_VERSION} && docker-php-ext-enable xdebug;

# Install Composer
RUN  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
     php composer-setup.php --version=${COMPOSER_VERSION} && \
     php -r "unlink('composer-setup.php');" && \
     mv composer.phar /usr/local/bin/composer \
;

# Copy php.ini configuration
ADD ./conf/php.ini /usr/local/etc/php/conf.d/php.ini

# Entrypoint
COPY entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

WORKDIR /var/www/src

CMD ["php-fpm"]
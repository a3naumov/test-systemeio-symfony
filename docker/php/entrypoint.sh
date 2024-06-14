#!/usr/bin/env sh

set -eu

#
# Xdebug
#

if [ "${PHP_XDEBUG_ENABLE}" = "true" ]; then
    {
        echo "xdebug.mode=debug"
        echo "xdebug.start_with_request=yes"
        echo "xdebug.client_host=host.docker.internal"
        echo "xdebug.client_port=9003"
    } >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
fi

#
# Run PHP-FPM
#

if [ "${1#-}" != "$1" ]; then
  set -- php-fpm "$@"
fi

exec "$@"
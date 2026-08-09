#!/bin/sh

set -e

cd /var/www/html

# Create Laravel storage symlink
if [ ! -L public/storage ]; then
    rm -rf public/storage
    ln -s /var/www/html/storage/app/public /var/www/html/public/storage
fi

# Start PHP-FPM
php-fpm -D

# Start Nginx
nginx -g "daemon off;"
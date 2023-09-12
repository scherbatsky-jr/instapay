#!/bin/sh

/var/www/html/docker/config.sh

php artisan migrate --force

service supervisor start
service cron start

supervisorctl reread

supervisorctl update

supervisorctl start worker:*

crontab /var/www/html/crontab

php-fpm -D

nginx -g 'daemon off;'

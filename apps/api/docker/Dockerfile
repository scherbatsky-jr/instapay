FROM dzangolab/php-nginx:8.1
# LABEL maintainer="op@dznago.com"

ARG env=''
ARG build='build'
ARG post_max_size='500M'
ARG timezone='Asia/Bangkok'
ARG upload_max_filesize='100M'
ARG version='version'

ENV APP_BUILD $build
ENV APP_ENV $env
ENV APP_TIMEZONE $timezone
ENV APP_VERSION $version

RUN composer self-update

COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/worker.conf /etc/supervisor/conf.d/worker.conf

RUN chown -R www-data:www-data /var/www/html/

COPY --chown=www-data:www-data . /var/www/html/

RUN apt-get update && apt-get install -y supervisor \
  && cd /var/www/html \
  && composer install --optimize-autoloader $(if [ "$env" != "development" ]; then echo '--no-dev'; fi) \
  && chown -R www-data:www-data storage/ bootstrap/cache/ \
  && chmod -R 777 storage/ bootstrap/cache/ docker/ \
  && echo "date.timezone="$timezone > /usr/local/etc/php/conf.d/date_timezone.ini \
  && echo "upload_max_filesize="$upload_max_filesize > /usr/local/etc/php/conf.d/upload_max_filesize.ini \
  && echo "post_max_size="$post_max_size > /usr/local/etc/php/conf.d/post_max_size.ini

WORKDIR /var/www/html

CMD ["/var/www/html/docker/startup.sh"]

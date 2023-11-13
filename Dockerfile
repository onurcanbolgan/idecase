FROM webdevops/php-nginx:8.2-alpine

WORKDIR /opt/docker
COPY etc/nginx/vhost.conf etc/nginx/vhost.conf
COPY bin/service.d/cron.d/10-init.sh bin/service.d/cron.d/10-init.sh
COPY provision/entrypoint.d/05-permissions.sh provision/entrypoint.d/05-permissions.sh

RUN apk update && apk add --no-cache supervisor
RUN mkdir -p "/etc/supervisor/logs"

#COPY provision/entrypoint.d/artisan.sh /tmp
COPY etc/supervisord.conf /etc/supervisord.conf
COPY etc/php.ini /usr/local/etc/php/conf.d/app.ini
#ENTRYPOINT ["/tmp/artisan.sh"]

# PHP Error Log Files
RUN mkdir /var/log/php
RUN touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log

RUN apk add npm
RUN apk add git-lfs

WORKDIR /var/www/html
CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]

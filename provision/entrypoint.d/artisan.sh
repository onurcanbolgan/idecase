#!/bin/bash
#/usr/local/bin/php /var/www/html/artisan route:cache
#/usr/local/bin/php /var/www/html/artisan route:clear
/usr/local/bin/php /var/www/html/artisan config:cache
/usr/local/bin/php /var/www/html/artisan config:clear
/usr/local/bin/php /var/www/html/artisan optimize
/usr/local/bin/php /var/www/html/artisan migrate:fresh --seed

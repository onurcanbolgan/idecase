mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
chmod -R +rw /var/www/html/storage
chown -R 1000:1000 /var/www/html/storage
chmod 1777 /tmp

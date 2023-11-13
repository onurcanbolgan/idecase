# The MIT License (MIT)
# Copyright (c) 2015-2023 WebDevOps
# https://github.com/webdevops/Dockerfile/blob/master/docker/base/alpine/conf/bin/service.d/cron.d/10-init.sh

# Install crontab files
if [[ -d "/opt/docker/etc/cron" ]]; then
    mkdir -p /etc/cron.d/

    find /opt/docker/etc/cron -type f | while read CRONTAB_FILE; do
        # fix permissions
        chmod 0644 -- "$CRONTAB_FILE"

        # add newline, cron needs this
        echo >> "$CRONTAB_FILE"

        # Install files
        cp -a -- "$CRONTAB_FILE" "/etc/crontabs/$(basename "$CRONTAB_FILE")"
    done
fi

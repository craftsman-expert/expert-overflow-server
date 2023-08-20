#!/usr/bin/env sh

php /srv/www/bin/console messenger:consume async_low --limit=500 --memory-limit=1024M --time-limit=3600 -vvv
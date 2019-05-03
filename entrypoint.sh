#!/bin/sh

LOG_FILE="/var/www/api.vost.test/storage/logs/laravel.log"

> $LOG_FILE && chown -R 1000:1000 $LOG_FILE && chmod 777 $LOG_FILE && php-fpm

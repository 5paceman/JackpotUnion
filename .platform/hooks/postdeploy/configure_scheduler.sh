#!/bin/sh

echo "* * * * * root cd /var/app/current && php artisan schedule:run >> /dev/null 2>&1" \
    | sudo tee /etc/cron.d/laravel_scheduler_cron
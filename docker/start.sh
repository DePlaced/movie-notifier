#!/usr/bin/env bash
set -e
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm ci
npm run build
php artisan migrate --seed --force
exec /usr/bin/supervisord -c /etc/supervisord.conf

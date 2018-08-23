release: php artisan migrate --force
web: vendor/bin/heroku-php-apache2 public/
worker-01: php artisan queue:work --sleep=3 --tries=3 --daemon --queue=high,low
worker-02: php artisan queue:work --sleep=3 --tries=3 --daemon --queue=high,low
worker-03: php artisan queue:work --sleep=3 --tries=3 --daemon --queue=high,low

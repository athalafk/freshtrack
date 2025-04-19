#!/bin/bash

# nginx configuration
cp /home/site/wwwroot/nginx.conf /etc/nginx/nginx.conf

# Restart php-fpm dan nginx
service php8.2-fpm restart
service nginx restart

# Keep the container alive
tail -f /dev/null

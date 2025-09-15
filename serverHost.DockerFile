FROM php:8.2-apache

WORKDIR /var/www/html

COPY ./www-root/index.php /var/www/html

RUN rm index.html

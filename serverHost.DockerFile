FROM php:8.2-apache

WORKDIR /var/www/html

COPY ./www-root/ /var/www/html/

RUN docker-php-ext-install mysqli

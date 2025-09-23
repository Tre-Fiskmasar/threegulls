FROM php:8.2-apache

WORKDIR /var/www/html

COPY ./000-default.conf /etc/apache2/sites-available/

COPY ./www-root/ /var/www/html/

RUN docker-php-ext-install mysqli

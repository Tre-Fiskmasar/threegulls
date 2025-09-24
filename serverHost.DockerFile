FROM php:8.2-apache

WORKDIR /var/www/html

COPY ./000-default.conf /etc/apache2/sites-available/
COPY ./default-ssl.conf /etc/apache2/sites-available/
COPY ./ports.conf /etc/apache2/

COPY ./www-root/ /var/www/html/

RUN docker-php-ext-install mysqli

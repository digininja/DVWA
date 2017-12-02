FROM php:5.6-apache

RUN apt-get update && apt-get -y install libpng12-dev
RUN docker-php-ext-install gd
RUN docker-php-ext-install mysql
COPY php.ini /usr/local/etc/php/php.ini

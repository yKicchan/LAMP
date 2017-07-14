FROM php:5.6-apache
MAINTAINER yKicchan
COPY php/php.ini /usr/local/etc/php/
RUN apt-get update
RUN docker-php-ext-install mysqli
# RUN a2enmod rewrite

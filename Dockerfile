FROM php:8.0-apache

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite


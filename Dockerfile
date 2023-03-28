FROM php:8.2.0-apache

COPY ./ /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite \
    && docker-php-ext-install pdo_mysql

ENV PORT=8080
EXPOSE 8080

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT}"]
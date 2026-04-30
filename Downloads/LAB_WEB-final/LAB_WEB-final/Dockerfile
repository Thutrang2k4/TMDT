FROM php:8.2-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN a2enmod rewrite


COPY . /var/www/html/

WORKDIR /var/www/html/

EXPOSE 80

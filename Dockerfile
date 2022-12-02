FROM composer:1.9.3 as vendor

WORKDIR /tmp/

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install

FROM php:8.0-apache-buster
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql

RUN a2enmod rewrite && service apache2 restart

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

COPY . /var/www/html
COPY --from=vendor /tmp/vendor/ /var/www/html/vendor/

RUN chmod +x ./entrypoint.sh

CMD ./entrypoint.sh

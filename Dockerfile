# Dockerfile
FROM php:8.0-apache

USER root

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        zip \
        curl \
        unzip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip \
    && docker-php-source delete

    #&& docker-php-ext-install pdo_mysql \
    #&& docker-php-ext-install mysqli \
RUN pecl install mongodb \
    &&  echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongo.ini

COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# RUN chmod -R 777 /var/www/html/storage # Run cette commande si une erreur sur laravel
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

#RUN cd /var/www/html
#RUN php artisan cache:clear && composer dump-autoload && php artisan key:generate ## Si error 500 sur laravel il faut run ces commandes



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
        libcurl4-openssl-dev \
        pkg-config \
        libssl-dev \
        npm \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip \
    && docker-php-source delete
    
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/xdebug.ini 

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

RUN echo "Mutex posixsem" >> /etc/apache2/apache2.conf

COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite \
    && a2enmod ssl

#RUN composer update
#RUN npm update
#RUN chmod -R 775 /var/www/html/storage
#RUN cd /var/www/html && php artisan cache:clear && composer dump-autoload && php artisan key:generate


# RUN chmod -R 775 /var/www/html/storage # Run cette commande si une erreur sur laravel
#RUN cd /var/www/html
#RUN php artisan cache:clear && composer dump-autoload && php artisan key:generate ## Si error 500 sur laravel il faut run ces commandes



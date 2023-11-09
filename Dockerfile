FROM php:8.0-fpm

# Install system dependencies
RUN apt-get update \
	&& apt-get install -y libfreetype6-dev libjpeg-dev libpng-dev libfontconfig1 libxrender1 git libmcrypt-dev libzip-dev libonig-dev zip rsync \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd \
    && pecl install mcrypt

# Install PHP extensions
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN docker-php-ext-install mbstring
RUN docker-php-ext-enable mcrypt

RUN apt-get install -y curl \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && docker-php-ext-enable xdebug

# Set working directory
WORKDIR /var/www

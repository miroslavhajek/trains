FROM php:8.3-fpm

RUN apt-get update \
    && apt-get install -y git unzip libzip-dev zlib1g-dev libicu-dev libxslt-dev \
    && apt-get clean \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install xsl \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure intl && docker-php-ext-install intl

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

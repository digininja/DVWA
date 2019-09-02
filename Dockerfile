FROM php:7.2-apache

RUN set -eux \
        && apt-get update \
        && apt-get install -y --no-install-recommends \
            libfreetype6-dev \
            libjpeg62-turbo-dev \
            libpng-dev \
        && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
        && docker-php-ext-install -j$(nproc) mysqli pdo pdo_mysql gd \
        && rm -rf /var/lib/apt/lists/* \
        && rm -f /var/www/html/index.html

COPY ./ /var/www/html

RUN set -eux \
    && mv config/config.inc.docker.php /var/www/html/config/config.inc.php \
    && chown -R www-data:www-data \
        /var/www/html/hackable/uploads \
        /var/www/html/external/phpids/0.6/lib/IDS/tmp \
        /var/www/html/config
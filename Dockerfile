FROM docker.io/library/php:8-apache
WORKDIR /var/www/html

# https://www.php.net/manual/en/image.installation.php
RUN apt-get update \
 && apt-get install -y zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev iputils-ping \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-configure gd --with-jpeg --with-freetype \
 # Use pdo_sqlite instead of pdo_mysql if you want to use sqlite
 && docker-php-ext-install gd mysqli pdo pdo_mysql

COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data config/config.inc.php.dist config/config.inc.php

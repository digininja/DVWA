FROM ubuntu:18.04
RUN apt-get update
ENV TZ=Europe/London
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt-get -y install apache2 mariadb-server mariadb-client php php-mysqli php-gd libapache2-mod-php tzdata
RUN echo mariadb-server mysql-server/root_password password vulnerables | debconf-set-selections && \
    echo mariadb-server mysql-server/root_password_again password vulnerables | debconf-set-selections && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

COPY php.ini /etc/php/7.2/apache2/php.ini
COPY . /var/www/html/
RUN cp /var/www/html/config/config.inc.php.dist /var/www/html/config/config.inc.php

RUN chown www-data:www-data -R /var/www/html && \
    rm /var/www/html/index.html

RUN service mysql start && \
    sleep 3 && \
    mysql -uroot -pvulnerables -e "CREATE USER dvwa@localhost IDENTIFIED BY 'p@ssw0rd';CREATE DATABASE dvwa;GRANT ALL privileges ON dvwa.* TO 'dvwa'@localhost;"

EXPOSE 80

COPY run.sh /
ENTRYPOINT ["/run.sh"]

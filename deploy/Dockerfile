FROM ubuntu:bionic
MAINTAINER soheil@gmail.com

ENV CREATE_MYSQL_BASIC_USER_AND_DB true
ENV MYSQL_USER_NAME dvwa
ENV MYSQL_USER_DB dvwa
ENV MYSQL_USER_PASS p@ssw0rd

# Install packages
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update && \
		apt-get -y install git wget unzip supervisor apache2 mysql-server php php-mysqli php-gd libapache2-mod-php
#  apt-get -y install supervisor git nano apache2 libapache2-mod-php5 mysql-server php5-mysql pwgen php-apc php5-mcrypt && \
#  echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Add image configuration and scripts
ADD conf/start-apache2.sh /start-apache2.sh
ADD conf/start-mysql.sh /start-mysql.sh
ADD conf/run.sh /run.sh
RUN chmod 755 /*.sh
ADD conf/my.cnf /etc/mysql/conf.d/my.cnf
ADD conf/supervisord-apache2.conf /etc/supervisor/conf.d/supervisord-apache2.conf
ADD conf/supervisord-mysql.conf /etc/supervisor/conf.d/supervisord-mysql.conf

# Remove pre-installed database
RUN rm -rf /var/lib/mysql/*

# Add MySQL utils
ADD conf/create_mysql_users.sh /create_mysql_users.sh
RUN chmod 755 /*.sh
RUN chmod +4000 /usr/bin/find

# config to enable .htaccess
ADD conf/apache_default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

#Environment variables to configure php
ENV PHP_UPLOAD_MAX_FILESIZE 60M
ENV PHP_POST_MAX_SIZE 60M

# Add volumes for MySQL
VOLUME  ["/etc/mysql", "/var/lib/mysql" ]


ENV DEBIAN_FRONTEND noninteractive

# Setup /app directory
RUN mkdir -p /app && rm -fr /var/www/html && ln -s /app /var/www/html

# Preparation
RUN \
#  rm -fr /app/* && \
  wget https://github.com/ethicalhack3r/DVWA/archive/master.zip && \
  unzip /master.zip && \
  cp -r /DVWA-master/* /app && \
  rm -rf /DVWA-master && \
  echo 'session.save_path = "/tmp"' >> /etc/php/7.2/apache2/php.ini && \
  sed -ri -e "s/^allow_url_include.*/allow_url_include = On/" /etc/php/7.2/apache2/php.ini && \
  chmod a+w /app/hackable/uploads && \
  chmod a+w /app/external/phpids/0.6/lib/IDS/tmp/phpids_log.txt && \
  cp /app/config/config.inc.php.dist /app/config/config.inc.php



EXPOSE 80 3306
CMD ["/run.sh"]

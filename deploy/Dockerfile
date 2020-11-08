FROM ubuntu:bionic
MAINTAINER soheil@gmail.com

ARG PHP_VERSION=7.2

ENV DB_SERVER_HOST 127.0.0.1

# Install packages
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update && \
		apt-get -y install git wget unzip supervisor apache2 php php-mysqli php-gd libapache2-mod-php iputils-ping

# Add image configuration and scripts
ADD conf/run.sh /run.sh
RUN chmod 755 /*.sh

# config to enable .htaccess
ADD conf/apache_default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
# To enable loging Apache http traffic detail
RUN a2enmod dump_io


#Environment variables to configure php
ENV PHP_UPLOAD_MAX_FILESIZE 60M
ENV PHP_POST_MAX_SIZE 60M

ENV DEBIAN_FRONTEND noninteractive

# Setup /app directory
RUN mkdir -p /app && rm -fr /var/www/html && ln -s /app /var/www/html

# Preparation
RUN \
#  rm -fr /app/* && \
  wget https://github.com/digininja/DVWA/archive/master.zip && \
  unzip /master.zip && \
  cp -r /DVWA-master/* /app && \
  rm -rf /DVWA-master && \
  echo 'session.save_path = "/tmp"' >> /etc/php/$PHP_VERSION/apache2/php.ini && \
  sed -ri -e "s/^allow_url_include.*/allow_url_include = On/" /etc/php/$PHP_VERSION/apache2/php.ini && \
  chmod a+w /app/hackable/uploads && \
  chmod a+w /app/external/phpids/0.6/lib/IDS/tmp/phpids_log.txt && \
  cp /app/config/config.inc.php.dist /app/config/config.inc.php && \
  sed -i "s/^.*db_server.* = '127.0.0.1';/\$_DVWA[ 'db_server' ] = getenv('DB_SERVER_HOST');/g" /app/config/config.inc.php && \
  sed -i "s/^.*recaptcha_public_key.* = '';/\$_DVWA[ 'recaptcha_public_key' ] = getenv('RECAPTCHA_PUBLIC_KEY');/g" /app/config/config.inc.php && \
  sed -i "s/^.*recaptcha_private_key' ].* = '';/\$_DVWA[ 'recaptcha_private_key' ] = getenv('RECAPTCHA_PRIVATE_KEY');/g" /app/config/config.inc.php



EXPOSE 80
CMD ["/run.sh"]

![alt text](http://www.randomstorm.com/images/dvwa_grey.png "DVWA")

DAMN VULNERABLE WEB APP
=======================

Damn Vulnerable Web App (DVWA) is a PHP/MySQL web application that is damn vulnerable. Its main goals are to be an aid for security professionals to test their skills and tools in a legal environment, help web developers better understand the processes of securing web applications and aid teachers/students to teach/learn web application security in a class room environment.

WARNING!
========
Damn Vulnerable Web App is damn vulnerable! Do not upload it to your hosting provider's public html folder or any working web
server as it will be hacked. I recommend downloading and installing XAMPP onto a local machine inside your LAN which is used solely for testing. 

We do not take responsibility for the way in which any one uses Damn Vulnerable Web App (DVWA). We have made the purposes of the application clear and it should not be used maliciously. We have given warnings and taken measures to prevent users from installing DVWA on to live web servers. If your web server is compromised via an installation of DVWA it is not our responsibility it is the responsibility of the person/s who uploaded and installed it.

License
=======

This file is part of Damn Vulnerable Web App (DVWA).

Damn Vulnerable Web App (DVWA) is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Damn Vulnerable Web App (DVWA) is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Damn Vulnerable Web App (DVWA).  If not, see http://www.gnu.org/licenses/.

Download
========

DVWA is available either as a package that will run on your own web server or as a Live CD

 - DVWA v1.0.8 (latest) - (1.3MB) [Download](https://github.com/RandomStorm/DVWA/archive/v1.0.8.zip)
 - DVWA v1.0.7 LiveCD - (480MB) [Download](http://www.dvwa.co.uk/DVWA-1.0.7.iso)

Installation
============

*Default username = admin*

*Default password = password*

Installation video:
http://www.youtube.com/watch?v=GzIj07jt8rM

The easiest way to install DVWA is to download and install 'XAMPP' if you do not already have a web server setup. 

XAMPP is a very easy to install Apache Distribution for Linux, Solaris, Windows and Mac OS X. The package includes the Apache web server, MySQL, PHP, Perl, a FTP server and phpMyAdmin.

XAMPP can be downloaded from:
http://www.apachefriends.org/en/xampp.html

Simply unzip dvwa.zip, place the unzipped files in your public html folder, then point your browser to http://127.0.0.1/dvwa/index.php

Database Setup
==============

To set up the database, simply click on the Setup button in the main menu, then click on the 'Create / Reset Database' button. This will create / reset the database for you with some data in.

If you receive an error while trying to create your database, make sure your database credentials are correct within /config/config.inc.php

The variables are set to the following by default: 
```
$_DVWA[ 'db_user' ] = 'root';
$_DVWA[ 'db_password' ] = '';
$_DVWA[ 'db_database' ] = 'dvwa';
```

Troubleshooting
===============

For the latest troubleshooting information please visit:
http://code.google.com/p/dvwa/issues/list

+Q. SQL Injection wont work on PHP version 5.2.6.

-A.If you are using PHP version 5.2.6 you will need to do the following in order for SQL injection and other vulnerabilities to work.

In .htaccess:

  Replace:
```
  <IfModule mod_php5.c>
    php_flag magic_quotes_gpc off
    #php_flag allow_url_fopen on
    #php_flag allow_url_include on
  </IfModule>
```
  With:
```
  <IfModule mod_php5.c>
    magic_quotes_gpc = Off
    allow_url_fopen = On
    allow_url_include = On
  </IfModule>
```
+Q. Command execution won't work.

-A. Apache may not have high enough priviledges to run commands on the web server. If you are running DVWA under linux make sure you are logged in as root. Under Windows log in as Administrator.

+Q. My XSS payload won't run in IE.

-A. If your running IE8 or above IE actively filters any XSS. To disable the filter you can do so by setting the HTTP header 'X-XSS-Protection: 0' or disable it from internet options. There may also be ways to bypass the filter.

Links
=====

Homepage: http://www.dvwa.co.uk

Project Home: https://github.com/RandomStorm/DVWA

*Created by the DVWA team*

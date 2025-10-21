# DAMN VULNERABLE WEB APPLICATION


i edited this file 
Damn Vulnerable Web Application (DVWA) is a PHP/MariaDB web application that is damn vulnerable. Its main goal is to be an aid for security professionals to test their skills and tools in a legal environment, help web developers better understand the processes of securing web applications and to aid both students & teachers to learn about web application security in a controlled class room environment.

The aim of DVWA is to **practice some of the most common web vulnerabilities**, with **various levels of difficulty**, with a simple straightforward interface.
Please note, there are **both documented and undocumented vulnerabilities** with this software. This is intentional. You are encouraged to try and discover as many issues as possible.
- - -

## WARNING!

Damn Vulnerable Web Application is damn vulnerable! **Do not upload it to your hosting provider's public html folder or any Internet facing servers**, as they will be compromised. It is recommended using a virtual machine (such as [VirtualBox](https://www.virtualbox.org/) or [VMware](https://www.vmware.com/)), which is set to NAT networking mode. Inside a guest machine, you can download and install [XAMPP](https://www.apachefriends.org/) for the web server and database.

### Disclaimer

We do not take responsibility for the way in which any one uses this application (DVWA). We have made the purposes of the application clear and it should not be used maliciously. We have given warnings and taken measures to prevent users from installing DVWA on to live web servers. If your web server is compromised via an installation of DVWA, it is not our responsibility, it is the responsibility of the person/s who uploaded and installed it.

- - -

## License

This file is part of Damn Vulnerable Web Application (DVWA).

Damn Vulnerable Web Application (DVWA) is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Damn Vulnerable Web Application (DVWA) is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Damn Vulnerable Web Application (DVWA).  If not, see <https://www.gnu.org/licenses/>.

- - -

## Internationalisation

This file is available in multiple languages:

- Arabic: [العربية](README.ar.md)
- Chinese: [简体中文](README.zh.md)
- French: [Français](README.fr.md)
- Korean: [한국어](README.ko.md)
- Persian: [فارسی](README.fa.md)
- Polish: [Polski](README.pl.md)
- Portuguese: [Português](README.pt.md)
- Spanish: [Español](README.es.md)
- Turkish: [Türkçe](README.tr.md)
- Indonesia: [Indonesia](README.id.md)
- Vietnamese: [Vietnamese](README.vi.md)
- Italian: [Italiano](README.it.md)

If you would like to contribute a translation, please submit a PR. Note though, this does not mean just run it through Google Translate and send that in, those will be rejected. Submit your translated version by adding a new 'README.xx.md' file where xx is the two-letter code of your desired language (based on [ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes)).

- - -

## Download

While there are various versions of DVWA around, the only supported version is the latest source from the official GitHub repository. You can either clone it from the repo:

```sh
git clone https://github.com/digininja/DVWA.git
```

Or [download a ZIP of the files](https://github.com/digininja/DVWA/archive/master.zip).

- - -

## Installation

### Automated Installation 🛠️

**Note, this is not an official DVWA script, it was written by [IamCarron](https://github.com/iamCarron/). A lot of work went into creating the script and, when it was created, it did not do anything malicious, however it is recommended you review the script before blindly running it on your system, just in case. Please report any bugs to [IamCarron](https://github.com/iamCarron/), not here.**

An automated configuration script for DVWA on Debian-based machines, including Kali, Ubuntu, Kubuntu, Linux Mint, Zorin OS...

**Note: This script requires root privileges and is tailored for Debian-based systems. Ensure you are running it as the root user.**

#### Installation Requirements

- **Operating System:** Debian-based system (Kali, Ubuntu, Kubuntu, Linux Mint, Zorin OS)
- **Privileges:** Execute as root user

#### Installation Steps

##### One-Liner

This will download an install script written by [@IamCarron](https://github.com/IamCarron) and run it automatically. This would not be included here if we did not trust the author and the script as it was when we reviewed it, but there is always the chance of someone going rogue, and so if you don't feel safe running someone else's code without reviewing it yourself, follow the manual process and you can review it once downloaded.

```sh
sudo bash -c "$(curl --fail --show-error --silent --location https://raw.githubusercontent.com/IamCarron/DVWA-Script/main/Install-DVWA.sh)"
```

##### Manually Running the Script

1. **Download the script:**

   ```sh
   wget https://raw.githubusercontent.com/IamCarron/DVWA-Script/main/Install-DVWA.sh
   ```

2. **Make the script executable:**

   ```sh
   chmod +x Install-DVWA.sh
   ```

3. **Run the script as root:**

   ```sh
   sudo ./Install-DVWA.sh
   ```

### Installation Videos

- [Installing DVWA on Kali running in VirtualBox](https://www.youtube.com/watch?v=WkyDxNJkgQ4)
- [Installing DVWA on Windows using XAMPP](https://youtu.be/Yzksa_WjnY0)
- [Installing Damn Vulnerable Web Application (DVWA) on Windows 10](https://www.youtube.com/watch?v=cak2lQvBRAo)

### Windows + XAMPP

The easiest way to install DVWA is to download and install [XAMPP](https://www.apachefriends.org/) if you do not already have a web server setup.

XAMPP is a very easy to install Apache Distribution for Linux, Solaris, Windows and Mac OS X. The package includes the Apache web server, MySQL, PHP, Perl, a FTP server and phpMyAdmin.

This [video](https://youtu.be/Yzksa_WjnY0) walks you through the installation process for Windows but it should be similar for other OSs.

### Docker

Thanks to [hoang-himself](https://github.com/hoang-himself) and [JGillam](https://github.com/JGillam), every commit to the `master` branch causes a Docker image to be built and ready to be pulled down from GitHub Container Registry.

For more information on what you are getting, you can browse [the prebuilt Docker images](https://github.com/digininja/DVWA/pkgs/container/dvwa).

#### Getting Started

Prerequisites: Docker and Docker Compose.

- If you are using Docker Desktop, both of these should be already installed.
- If you prefer Docker Engine on Linux, make sure to follow their [installation guide](https://docs.docker.com/engine/install/#server).

**We provide support for the latest Docker release as shown above.**
If you are using Linux and the Docker package that came with your package manager, it will probably work too, but support will only be best-effort.

Upgrading Docker from the package manager version to upstream requires that you uninstall the old versions as seen in their manuals for [Ubuntu](https://docs.docker.com/engine/install/ubuntu/#uninstall-old-versions), [Fedora](https://docs.docker.com/engine/install/fedora/#uninstall-old-versions) and others.
Your Docker data (containers, images, volumes, etc.) should not be affected, but in case you do run into a problem, make sure to [tell Docker](https://www.docker.com/support) and use search engines in the mean time.

Then, to get started:

1. Run `docker version` and `docker compose version` to see if you have Docker and Docker Compose properly installed. You should be able to see their versions in the output.

    For example:

    ```text
    >>> docker version
    Client:
     [...]
     Version:           23.0.5
     [...]

    Server: Docker Desktop 4.19.0 (106363)
     Engine:
      [...]
      Version:          23.0.5
      [...]

    >>> docker compose version
    Docker Compose version v2.17.3
    ```

    If you don't see anything or get a command not found error, follow the prerequisites to setup Docker and Docker Compose.

2. Clone or download this repository and extract (see [Download](#download)).
3. Open a terminal of your choice and change its working directory into this folder (`DVWA`).
4. Run `docker compose up -d`.

DVWA is now available at `http://localhost:4280`.

**Notice that for running DVWA in containers, the web server is listening on port 4280 instead of the usual port of 80.**
For more information on this decision, see [I want to run DVWA on a different port](#i-want-to-run-dvwa-on-a-different-port).

#### Local Build

If you made local changes and want to build the project from local, go to `compose.yml` and change `pull_policy: always` to `pull_policy: build`.

Running `docker compose up -d` should trigger Docker to build an image from local regardless of what is available in the registry.

See also: [`pull_policy`](https://github.com/compose-spec/compose-spec/blob/master/05-services.md#pull_policy).

#### Serve local files

If your making local changes and don't want to build the project for every change :
1. Go to `compose.yml` and uncomment :
    ```
        # volumes:
        #   - ./:/var/www/html
    ```
2. Run `cp config/config.inc.php.dist config/config.inc.php` to copy the default config file.
3. Run `docker compose up -d` and changes to local files will reflect on the container.

### PHP Versions

Ideally you should be using the latest stable version of PHP as that is the version that this app will be developed and tested on.

Support will not be given for anyone trying to use PHP 5.x.

Versions less than 7.3 have known issues that will cause problems, most of the app will work, but random things may not. Unless you have a very good reason for using such an old version, support will not be given.

### Linux Packages

If you are using a Debian based Linux distribution, you will need to install the following packages _(or their equivalent)_:

- apache2
- libapache2-mod-php
- mariadb-server
- mariadb-client
- php php-mysqli
- php-gd

I would recommend doing an update before this, just so you make sure you are going to get the latest version of everything.

```sh
apt update
apt install -y apache2 mariadb-server mariadb-client php php-mysqli php-gd libapache2-mod-php
```

The site will work with MySQL instead of MariaDB but we strongly recommend MariaDB as it works out of the box whereas you have to make changes to get MySQL to work correctly.

### Apache Modules

If you want to use the API lab you must have the Apache module `mod_rewrite` enabled. To do this in Linux run:

```
a2enmod rewrite
```

And then restart Apache with:

```
apachectl restart
```

### Vendor Files

If you want to use the API module you will need to install a set of vendor files using [Composer](https://getcomposer.org/).

First, make sure you have Composer installed. There seem to be backward compatibility issues so I always get the latest version from here:

https://getcomposer.org/doc/00-intro.md

Follow the instructions the site gives to get it installed.

Now go into the `vulnerabilities/api` directory and run:

```
composer.phar install
```

If you did not install Composer to the system path, make sure you reference its full location.

## Configurations

### Config File

DVWA ships with a dummy copy of its config file which you will need to copy into place and then make the appropriate changes. On Linux, assuming you are in the DVWA directory, this can be done as follows:

`cp config/config.inc.php.dist config/config.inc.php`

On Windows, this can be a bit harder if you are hiding file extensions, if you are unsure about this, this blog post explains more about it:

[How to Make Windows Show File Extensions](https://www.howtogeek.com/205086/beginner-how-to-make-windows-show-file-extensions/)

### Config with environment variables

Instead of modifying the configuration file, you can also set most settings using environment variables. In a Docker or Kubernetes deployment, this allows you to modify the configuration without creating a new Docker image. You'll find the variables in the [config/config.inc.php.dist](config/config.inc.php.dist) file.

If you want to set the default security level to "low", simply add the following line to the [compose.yml](./compose.yml) file:

```yml
environment:
  - DB_SERVER=db
  - DEFAULT_SECURITY_LEVEL=low
```

### Database Setup

To set up the database, simply click on the `Setup DVWA` button in the main menu, then click on the `Create / Reset Database` button. This will create / reset the database for you with some data in.

If you receive an error while trying to create your database, make sure your database credentials are correct within `./config/config.inc.php`. _This differs from config.inc.php.dist, which is an example file._

The variables are set to the following by default:

```php
$_DVWA[ 'db_server'] = '127.0.0.1';
$_DVWA[ 'db_port'] = '3306';
$_DVWA[ 'db_user' ] = 'dvwa';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';
$_DVWA[ 'db_database' ] = 'dvwa';
```

Note, if you are using MariaDB rather than MySQL (MariaDB is default in Kali), then you can't use the database root user, you must create a new database user. To do this, connect to the database as the root user then use the following commands:

```mariadb
MariaDB [(none)]> create database dvwa;
Query OK, 1 row affected (0.00 sec)

MariaDB [(none)]> create user dvwa@localhost identified by 'p@ssw0rd';
Query OK, 0 rows affected (0.01 sec)

MariaDB [(none)]> grant all on dvwa.* to dvwa@localhost;
Query OK, 0 rows affected (0.01 sec)

MariaDB [(none)]> flush privileges;
Query OK, 0 rows affected (0.00 sec)
```

### Disable Authentication

Some tools don't work well with authentication so can't be used with DVWA. To get around this, there is a config option to disable authentication checking. To do this, simply set the following in the config file:

```php
$_DVWA[ 'disable_authentication' ] = true;
```

You will also need to set the security level to one that is appropriate to the testing you want to do:

```php
$_DVWA[ 'default_security_level' ] = 'low';
```

In this state, you can access all the features without needing to log in and set any cookies.

### Folder Permissions

- `./hackable/uploads/` - Needs to be writeable by the web service (for File Upload).

### PHP Configuration

On Linux systems, likely found in `/etc/php/x.x/fpm/php.ini` or `/etc/php/x.x/apache2/php.ini`.

- To allow  Remote File Inclusions (RFI):
  - `allow_url_include = on` [[allow_url_include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
  - `allow_url_fopen = on` [[allow_url_fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]

- To make sure PHP shows all error messages:
  - `display_errors = on` [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]
  - `display_startup_errors = on` [[display_startup_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-startup-errors)]

Make sure you restart the php service or Apache after making the changes.

### reCAPTCHA

This is only required for the "Insecure CAPTCHA" lab, if you aren't playing with that lab, you can ignore this section.

Generated a pair of API keys from <https://www.google.com/recaptcha/admin/create>.

These then go in the following sections of `./config/config.inc.php`:

- `$_DVWA[ 'recaptcha_public_key' ]`
- `$_DVWA[ 'recaptcha_private_key' ]`

### Default Credentials

**Default username = `admin`**

**Default password = `password`**

_...can easily be brute forced ;)_

Login URL: <http://127.0.0.1/login.php>

_Note: This will be different if you installed DVWA into a different directory._

- - -

## Troubleshooting

These assume you are on a Debian based distro, such as Debian, Ubuntu and Kali. For other distros, follow along, but update the command where appropriate.

If you'd rather watch a video than read words, the most common issues are covered in the video [Fixing DVWA Setup Issues](https://youtu.be/C-kig5qrPSA?si=_a4Bop505-1tXb_F).

### Containers

#### I want to access the logs

If you are using Docker Desktop, logs can be accessed from the graphical application.
Some minor details may change with newer versions, but the access method should be the same.

![Overview of DVWA compose](./docs/graphics/docker/overview.png)
![Viewing DVWA logs](docs/graphics/docker/detail.png)

Logs can also be accessed from the terminal.

1. Open a terminal and change its working directory to DVWA
2. Show the merged logs

    ```sh
    docker compose logs
    ```

   In case you want to export the logs to a file, e.g. `dvwa.log`

   ```sh
   docker compose logs > dvwa.log
   ```

#### I want to run DVWA on a different port

We don't use port 80 by default for a few reasons:

- Some users might already be running something on port 80.
- Some users might be using a rootless container engine (like Podman), and 80 is a privileged port (< 1024). Additional configuration (e.g. setting `net.ipv4.ip_unprivileged_port_start`) is required, but you will have to research on your own.

You can expose DVWA on a different port by changing the port binding in the `compose.yml` file.
For example, you can change

```yml
ports:
  - 127.0.0.1:4280:80
```

to

```yml
ports:
  - 127.0.0.1:8806:80
```

DVWA is now accessible at `http://localhost:8806`.

In cases in which you want DVWA to not only be accessible exclusively from your own device, but
on your local network too (e.g. because you are setting up a test machine for a workshop), you
can remove the `127.0.0.1:` from the port mapping (or replace it with you LAN IP). This way it
will listen on all available device. The safe default should always be to only listen on your
local loopback device. After all, it is a damn vulnerable web application, running on your machine.

#### DVWA auto starts when Docker runs

The included [`compose.yml`](./compose.yml) file automatically runs DVWA and its database when Docker starts.

To disable this, you can delete or comment out the `restart: unless-stopped` lines in the [`compose.yml`](./compose.yml) file.

If you want to disable this behavior temporarily, you can run `docker compose stop`, or use Docker Desktop, find `dvwa` and click Stop.
Additionally, you can delete the containers, or run `docker compose down`.

### Log files

On Linux systems Apache generates two log files by default, `access.log` and `error.log` and on Debian based system these are usually found in `/var/log/apache2/`.

When submitting error reports, problems, anything like that, please include at least the last five lines from each of these files. On Debian based systems you can get these like this:

```sh
tail -n 5 /var/log/apache2/access.log /var/log/apache2/error.log
```

### I browsed to the site and got a 404 or Apache2 default page

[Video Help](https://youtu.be/C-kig5qrPSA?si=wTS3Aj8fycW3Idfr&t=141)

If you are having this problem you need to understand file locations. By default, the Apache document root (the place it starts looking for web content) is `/var/www/html`. If you put the file `hello.txt` in this directory, to access it you would browse to `http://localhost/hello.txt`.

If you created a directory and put the file in there - `/var/www/html/mydir/hello.txt` - you would then need to browse to `http://localhost/mydir/hello.txt`.

Linux is by default case sensitive and so in the example above, if you tried to browse to any of these, you would get a `404 Not Found`:

- `http://localhost/MyDir/hello.txt`
- `http://localhost/mydir/Hello.txt`
- `http://localhost/MYDIR/hello.txt`

How does this affect DVWA? Most people use git to clone DVWA into `/var/www/html`, this gives them the directory `/var/www/html/DVWA/` with all the DVWA files inside it. They then browse to `http://localhost/` and get either a `404` or the default Apache welcome page. As the files are in DVWA, you must browse to `http://localhost/DVWA`.

The other common mistake is to browse to `http://localhost/dvwa` which will give a `404` because `dvwa` is not `DVWA` as far as Linux directory matching is concerned.

So after setup, if you try to visit the site and get a `404`, think about where you installed the files to, where they are relative to the document root, and what the case of the directory you used is.

### I browsed to the site and got a blank screen

[Video Help](https://youtu.be/C-kig5qrPSA?si=wTS3Aj8fycW3Idfr&t=243)

This is usually one configuration issue hiding another issue. By default, PHP does not display errors, and so if you forgot to turn error display on during the setup process, any other problems, such as failure to connect to the database, will stop the app from loading but the message to tell you what is wrong will be hidden.

To fix this, make sure you set `display_errors` and `display_startup_errors` as covered in [PHP Configuration](#php-configuration) and then restart Apache.

### "Access denied" running setup

If you see the following when running the setup script it means the username or password in the config file do not match those configured on the database. [Video Help](https://youtu.be/C-kig5qrPSA?si=_a4Bop505-1tXb_F&t=973)

```mariadb
Database Error #1045: Access denied for user 'notdvwa'@'localhost' (using password: YES).
```

The error is telling you that you are using the username `notdvwa`.

The following error says you have pointed the config file at the wrong database. [Video Help](https://youtu.be/C-kig5qrPSA?si=_a4Bop505-1tXb_F&t=630)

```mariadb
SQL: Access denied for user 'dvwa'@'localhost' to database 'notdvwa'
```

It is saying that you are using the user `dvwa` and trying to connect to the database `notdvwa`.

The first thing to do is to double check what you think you put in the config file is what is actually there.

If it matches what you expect, the next thing to do is to check you can log in as the user on the command line. Assuming you have a database user of `dvwa` and a password of `p@ssw0rd`, run the following command:

```sh
mysql -u dvwa -pp@ssw0rd -D dvwa
```

_Note: There is no space after the -p_

If you see the following, the password is correct:

```mariadb
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 14
Server version: 10.3.22-MariaDB-0ubuntu0.19.10.1 Ubuntu 19.10

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [dvwa]>
```

As you can connect on the command line, it is likely something wrong in the config file, double check that and then raise an issue if you still can't get things working.

If you see the following, the username or password you are using is wrong. Repeat the [Database Setup](#database-setup) steps and make sure you use the same username and password throughout the process.

```mariadb
ERROR 1045 (28000): Access denied for user 'dvwa'@'localhost' (using password: YES)
```

If you get the following, the user credentials are correct but the user does not have access to the database. Again, repeat the setup steps and check the database name you are using.

```mariadb
ERROR 1044 (42000): Access denied for user 'dvwa'@'localhost' to database 'dvwa'
```

The final error you could get is this:

```mariadb
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
```

This is not an authentication issue but tells you that the database server is not running. Start it with the following

```sh
sudo service mysql start
```

### Connection Refused

[Video Help](https://youtu.be/C-kig5qrPSA?si=_a4Bop505-1tXb_F&t=444)

An error similar to this one:

```mariadb
Fatal error: Uncaught mysqli_sql_exception: Connection refused in /var/sites/dvwa/non-secure/htdocs/dvwa/includes/dvwaPage.inc.php:535
```

Means your database server is not running or you've got the wrong IP address in the config file.

Check this line in the config file to see where the database server is expected to be:

```php
$_DVWA[ 'db_server' ]   = '127.0.0.1';
```

Then go to this server and check that it is running. In Linux this can be done with:

```sh
systemctl status mariadb.service
```

And you are looking for something like this, the important bit is that it says `active (running)`.

```sh
● mariadb.service - MariaDB 10.5.19 database server
     Loaded: loaded (/lib/systemd/system/mariadb.service; enabled; preset: enabled)
     Active: active (running) since Thu 2024-03-14 16:04:25 GMT; 1 week 5 days ago
```

If it is not running, you can start it with:

```sh
sudo systemctl stop mariadb.service 
```

Note the `sudo` and make sure you put your Linux user password in if requested.

In Windows, check the status in the XAMPP console.

### Unknown authentication method

With the most recent versions of MySQL, PHP can no longer talk to the database in its default configuration. If you try to run the setup script and get the following message it means you have configuration.

```mariadb
Database Error #2054: The server requested authentication method unknown to the client.
```

You have two options, the easiest is to uninstall MySQL and install MariaDB. The following is the official guide from the MariaDB project:

<https://mariadb.com/resources/blog/how-to-migrate-from-mysql-to-mariadb-on-linux-in-five-steps/>

Alternatively, follow these steps:

1. As root, edit the following file: `/etc/mysql/mysql.conf.d/mysqld.cnf`
1. Under the line `[mysqld]`, add the following:
  `default-authentication-plugin=mysql_native_password`
1. Restart the database: `sudo service mysql restart`
1. Check the authentication method for your database user:

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------------------+-----------------------+
    | Host      | User             | plugin                |
    +-----------+------------------+-----------------------+
    | localhost | dvwa             | caching_sha2_password |
    +-----------+------------------+-----------------------+
    1 rows in set (0.00 sec)
    ```

1. You'll likely see `caching_sha2_password`. If you do, run the following command:

    ```sql
    mysql> ALTER USER dvwa@localhost IDENTIFIED WITH mysql_native_password BY 'p@ssw0rd';
    ```

1. Re-running the check, you should now see `mysql_native_password`.

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------+-----------------------+
    | Host      | User | plugin                |
    +-----------+------+-----------------------+
    | localhost | dvwa | mysql_native_password |
    +-----------+------+-----------------------+
    1 row in set (0.00 sec)
    ```

After all that, the setup process should now work as normal.

If you want more information see the following page: <https://www.php.net/manual/en/mysqli.requirements.php>.

### Database Error #2002: No such file or directory

The database server is not running. In a Debian based distro this can be done with:

```sh
sudo service mysql start
```

### Errors "MySQL server has gone away" and "Packets out of order"

There are a few reasons you could be getting these errors, but the most likely is the version of database server you are running is not compatible with the version of PHP.

This is most commonly found when you are running the latest version of MySQL as PHP and it do not get on well. Best advice, ditch MySQL and install MariaDB as this is not something we can support.

For more information, see:

<https://www.ryadel.com/en/fix-mysql-server-gone-away-packets-order-similar-mysql-related-errors/>

### Why can't the database connect on CentOS?

You may be running into problems with SELinux.  Either disable SELinux or run this command to allow the web server to talk to the database:

```sh
setsebool -P httpd_can_network_connect_db 1
```

### Anything Else

For the latest troubleshooting information please read both open and closed tickets in the git repo:

<https://github.com/digininja/DVWA/issues>

Before submitting a ticket, please make sure you are running the latest version of the code from the repo. This is not the latest release, this is the latest code from the master branch.

If raising a ticket, please submit at least the following information:

- Operating System
- The last 5 lines from the web server error log directly after whatever error you are reporting occurs
- If it is a database authentication problem, go through the steps above and screenshot each step. Submit these along with a screenshot of the section of the config file showing the database user and password.
- A full description of what is going wrong, what you expect to happen, and what you have tried to do to fix it. "login broken" is no enough for us to understand your problem and to help fix it.

- - -

## Tutorials

I am going to try to put together some tutorial videos that walk through some of the vulnerabilities and show how to detect them and then how to exploit them. Here are the ones I've made so far:

[Finding and Exploiting Reflected XSS](https://youtu.be/V4MATqtdxss)

- - -

## SQLite3 SQL Injection

_Support for this is limited, before raising issues, please ensure you are prepared to work on debugging, do not simply claim "it does not work"._

By default, SQLi and Blind SQLi are done against the MariaDB/MySQL server used by the site but it is possible to switch to do the SQLi testing against SQLite3 instead.

I am not going to cover how to get SQLite3 working with PHP, but it should be a simple case of installing the `php-sqlite3` package and making sure it is enabled.

To make the switch, simply edit the config file and add or edit these lines:

```php
$_DVWA["SQLI_DB"] = "sqlite";
$_DVWA["SQLITE_DB"] = "sqli.db";
```

By default it uses the file `database/sqli.db`, if you mess it up, simply copy `database/sqli.db.dist` over the top.

The challenges are exactly the same as for MariaDB, they just run against SQLite3 instead.

- - -

👨‍💻 Contributors
-----

Thanks for all your contributions and keeping this project updated. :heart:

If you have an idea, some kind of improvement or just simply want to collaborate, you are welcome to contribute and participate in the Project, feel free to send your PR.

<p align="center">
<a href="https://github.com/digininja/DVWA/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=digininja/DVWA&max=500">
</a>
</p>

- - -

## Reporting Vulnerabilities

To put it simply, please don't!

Once a year or so, someone will submit a report for a vulnerability they've found in the app, some are well written, sometimes better than I've seen in paid pen test reports, some are just "you are missing headers, pay me".

In 2023, this elevated to a whole new level when someone decided to request a CVE for one of the vulnerabities, they were given [CVE-2023-39848](https://nvd.nist.gov/vuln/detail/CVE-2023-39848). Much hilarity ensued and time was wasted getting this corrected.

The app has vulnerabilities, it is deliberate. Most are the well documented ones that you work through as lessons, others are "hidden" ones, ones to find on your own. If you really want to show off your skills at finding the hidden extras, write a blog post or create a video as there are probably people out there who would be interested in learning about them and about how your found them. If you send us the link, we may even include it in the references.

## Links

Project Home: <https://github.com/digininja/DVWA>

_Created by the DVWA team_

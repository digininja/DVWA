# اپلیکیشن وبی وحشتناک آسیب‌پذیر
اپلیکیشن وبی وحشتناک آسیب‌پذیر (DVWA)، یک اپلیکیشن وبی مبتنی بر پی‌اچ‌پی/مای‌اسکیوال است که به شدت آسیب‌پذیر طراحی شده است. اهداف اصلی آن  یاری رساندن به متخصصین حوزهٔ امنیت برای محک‌زدن مهارت‌ها و ابزارهایشان در محیطی قانونی، کمک کردن به توسعه‌دهنگان برا درک بهتر فرایند ایمن‌سازی اپلیکیشن‌های وبی و همچنین کمک کردن به مدرسین و دانشجویان برای یادگیری امنیست اپلیکیشن وبی در محیط کنترل‌شدهٔ کلاسی را شامل می‌شود.  

هدف DVWA، **تمرین بخشی از متداول‌ترین نفوذپذیری‌های وبی**، در **سطح‌های متفاوتی از دشواری**، با بهرا‌گیری از یک رابط سرراست و آسان است. لطفاً در نظر داشته‌باشید که در این نرم‌افزار **هم نفوذپذیری‌های مستند‌سازی‌شده و هم غیرمستندسازی‌شده** وجود دارند. این موضوع تعمدی است. از شما دعوت می‌شود که تلاش کنید و اشکالات را تا هرآنقدر که میسر است بیابید.   
- - -
## هشدار!
اپلیکیشن وبی وحشتناک آسیب‌پذیر، به‌ شکل وحشتناکی آسیب‌پذیر است! **آن را در پوشه‌های اچ‌تی‌ام‌ال عمومی سرویس دهندهٔ میزبانی خود یا هر سروری که در اینترنت قرار دارد بارگذاری نکنید**، چراکه مورد نفوذ قرار خواهند گرفت. برای این کار استفاده از یک ماشین مجازی پیشنهاد می‌شود (مثل [ورچوال باکس](https://www.virtualbox.org/) یا [وی‌ام‌ویر](https://www.vmware.com/)) که در حالت شبکه‌ای NAT پیکربندی شده باشد. در داخل ماشین مجازی می‌توانید [زمپ](https://www.apachefriends.org/) را برای سرور وب و پایگاه دادهٔ خود دانلود کنید.

### تکذیب‌نامه
ما در مورد اینکه از این اپلیکیشن (DVWA) چگونه استفاده می‌شود هیچ مسؤولیتی نمی‌پذیریم. ما هدف این برنامه را به صراحت بیان کرده‌ایم و از آن نباید برای مقاصد بدخواهانه استفاده شود. ما هشدارها و اقدامات خود را در جهت جلوگیری از نصب DVWA بر روی سرویس‌دهندگان وب برخط انجام داده‌ایم. اگر به سرور وب شما از طریق یک نسخه از DVWA نفوذ شد، تقصیری متوجه ما نیست. مسؤولیت آن بر عهدهٔ کسی است که آن را بارگذاری و نصب کرده است.
- - -
## مجوز
این فایل بخشی از اپلیکیشن وبی وحشتناک آسیب‌پذیر (DVWA) است.
اپلیکیشن وبی وحشتناک آسیب‌پذیر (DVWA) یک نرم‌افزار آزاد است. شما می‌توانید آن را تحت مجوز نسخه سوم‌ یا به‌اختیر خودتان نسخه‌های جدید‌تری از مجوز عمومی گنو (GNU) که توسط بنیاد نرم‌افزار آزاد منشر شده است، توزیع کنید و/یا تغییر دهید. 
اپلیکیشن وبی وحشتناک آسیب‌پذیر (DVWA) به امید اینکه سودمند واقع شود توزیع شده است، لیکن بدون هیچگونه تضمینی، حتی به صورت ضمنی که برای مقاصد خاصی مناسب باشد ارائه می‌شود. مجوز عمومی گنو را برای اطلاعات بیشتر ببینید.  
شما می‌بایست یک رونوشت از مجوز عمومی گنو را همرا با اپلیکیشن وبی وحشتناک آسیب‌پذیر (DVWA) دریافت کرده‌باشید. اگر این اتفاق نیفتاده است، <https://www.gnu.org/licenses/> را ببینید. 




- - -

## بین‌المللی کردن

این فایل به زبان‌های مختلف دیگری موجود است:
- انگلیسی: [English](README.md)
- ترکی: [Türkçe](README.tr.md)
- چینی: [简体中文](README.zh.md)
- عربی: [العربیه](README.ar.md)
- فرانسوی: [French](README.fr.md)

اگر شما نیز می‌خواهید به ترجمه‌کردن این مستند به زبان‌های دیگر کمک کنید، لطفاً یک PR‌ ارسال کنید. این بدان معنا نیست که فایل را به ترجمه‌گر گوگل بدهید و خروجی آن را ارسال کنید، اینگونه ترجمه‌‌‌ها مردود می‌شوند.

- - -

## دانلود
در حالیکه ممکن است نسخه‌های متفاوتی از DVWA در اطراف پراکنده شده باشند، تنها نسخه پشتیبانی شده،آخرین نسخه از مخزن رسمی گیت‌هاب است. شما یا می‌توانید آن را از طریق کلون کردن مخزن:



```
git clone https://github.com/digininja/DVWA.git
```

یا [بارگیری نسخهٔ زیپ‌شدهٔ فایلها](https://github.com/digininja/DVWA/archive/master.zip) دانلود کنید.

- - -

## نصب

### ویدئو‌های نصب

- [نصب بر کالی‌لینوکس در ورچوال‌باکس](https://www.youtube.com/watch?v=WkyDxNJkgQ4)
- [نصب در وینوز با استفاده از زمپ](https://youtu.be/Yzksa_WjnY0)
- [نصب بر روی ویندوز ۱۰](https://www.youtube.com/watch?v=cak2lQvBRAo)

### ویندوز+زمپ
اگر در حال حاضر یک وب‌سرور راه‌اندازی‌شده در اختیار ندارید، راحت‌ترین روش نصب DVWA از طریق دانلود و نصب [زمپ](https://www.apachefriends.org/) است

زمپ یک توزیع از آپاچی است که نصب بسیار آسانی دارد و برای لینوکس، سولاریس، ویندوز و مک‌او‌اس‌ اکس عرضه شده است. این بسته شامل سرویس‌دهندهٔ وب آپاچی، مای‌اس‌کیوال، پی‌اچ‌پی، پرل، یک سرویس‌دهندهٔ اف‌‌تی‌پی و پی‌اج‌پی‌مای‌ادمین است.
این [ویدئو](https://youtu.be/Yzksa_WjnY0) شما را قدم به قدم در مراحل نصب آن برای ویندوز هدایت می‌کند، البته برای سایر سیستم‌عامل‌ها نیز کمابیش به همین‌ شکل است.



### فایل کانفیگ
برنامهٔ DVWA همرا با یک فایل کانفیگ دم‌دستی توزیع می‌شود که لازم است شما آن را در جای مناسب کپی کنید و تغییرات لازم را بر روی آن اعمال کنید. در لینوکس با فرض بر اینکه در پوشهٔ DVWAقرار دارید، به این طریق می‌توانید فایل را کپی کنید:

`cp config/config.inc.php.dist config/config.inc.php`

در ویندوز، اگر پسوند فایل‌ها مخفی باشد، کار اندکی دشوارتر می‌شود. اگر در این مورد مطمئن نیستید، برای توضیحات بیشتر این پست وبلاگ را ببینید:

[چگونه به ویندوز بگوییم پسوند فایل‌ها را نمایش دهد](https://www.howtogeek.com/205086/beginner-how-to-make-windows-show-file-extensions/)

### پکیج‌های لینوکس

اگر از یک توزیع لینوکس مبتنی بر دبیان استفاده می کنید، لازم است بسته‌های نرم‌افزاری زیر _(یا مشابه آنها)_ را نصب کنید:

- apache2
- libapache2-mod-php
- mariadb-server
- mariadb-client
- php php-mysqli
- php-gd

جهت اطمینان از ایکنکه آخرین نسخه از همه‌چیز را دریافت خواهید کرد، اجرای یک Update‌ قبل از هر کاری توصیه می‌شود.

```
apt update
apt install -y apache2 mariadb-server mariadb-client php php-mysqli php-gd libapache2-mod-php
```
سایت با مای‌اسکیوال بجای ماریا‌دی‌بی کار می‌کند، اما ما قویاً ماریاد‌ی‌بی را توصیه می‌کنیم، چرا که بدون نیاز به تغییرات مستقیما کار خواهد کرد در حالیه برای راه‌اناختن صحیح مای‌اس‌کیو‌ال نیاز است تغییراتی در آن بدهید.


### نصب پایگاه داده
برای نصب پایگاه داده کافیست بر روی دکمهٔ  `Setup DVWA` در منوی اصلی کلیک کنید و پس از آن دکمهٔ `Create / Reset Database` را فشار دهید. این کار پایگاه داده را به همراه مقداری داده در آن ایجاد/بازسازی می‌کند.
اگر در حین ساختن پایگاه داده خطایی دریافت می‌کنید، مطمئن باشید اطلاعات اعتبارسنجی تنظیم‌شده در `./config/config.inc.php` صحیح باشد. *‌دقت کنید که این فایل با config.inc.php.dis که صرفاً یک فایل نمونه است تفاوت دارد.*  

متغیرها به صورت پیش‌فرض با مقادیر زیر تنظیم می‌شوند:

```php
$_DVWA[ 'db_server'] = '127.0.0.1';
$_DVWA[ 'db_port'] = '3306';
$_DVWA[ 'db_user' ] = 'dvwa';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';
$_DVWA[ 'db_database' ] = 'dvwa';
```

دقت کنید که، اگر از ماریادی‌بی بجای مای‌اس‌کیو‌ال استفاده می‌کنید (در  کالی‌لبنوکس ماریادی‌بی پیش‌فرض است)، نخواهید توانست از کاربر root‌ پایگاه داده استفاده کنید و می‌بایست کاربر جدیدی ایجاد کنید. برای این کار با کاربر روت به پایگاه داده وصل شوید و دستورات زیر را اجرا کنید:

```mysql
mysql> create database dvwa;
Query OK, 1 row affected (0.00 sec)

mysql> create user dvwa@localhost identified by 'p@ssw0rd';
Query OK, 0 rows affected (0.01 sec)

mysql> grant all on dvwa.* to dvwa@localhost;
Query OK, 0 rows affected (0.01 sec)

mysql> flush privileges;
Query OK, 0 rows affected (0.00 sec)
```

### غیر فعال کردن ورود
بعضی از ابزارها با بخش ورود به خوبی کار نمی‌کنند و با DVWA نمی‌توانند استفاده شوند. برای رفع این مشکل، گزینه‌ای در کانفیگ وجود دارد که بتوانید کنترل ورود را غیر فعال کنید. برای این کار کافیست تنظیم زیر را در فایل کانفیگ انجام دهید:

```php
$_DVWA[ 'disable_authentication' ] = true;
```

همچنین لازم است سطح امنیت را به مقداری که برای آزمونتان مد نظر دارید تغییر دهید:


```php
$_DVWA[ 'default_security_level' ] = 'low';
```

در این حالت شما می‌توانید از تمامی امکانات بدون نیاز به ورود و تنظیم کوکی‌ها بهره ببید.

### سایر تنظیمات
بسته به سیستم عامل و نسخه‌ای از پی‌اچ‌پی که اجرا می‌‌کنید، ممکن است بخواهید در تنظیمات پیش‌فرض تغییراتی ایجاد کنید. محل قرارگیری فایل ها از ماشینی تا ماشین دیگر ممکن است متفاوت باشد. 


**سطح دسترسی به پوشه‌ها**:

* `./hackable/uploads/` - باید توسط سرویس وب قابل نوشتن باشد (برای آپلود فایل).
* `./external/phpids/0.6/lib/IDS/tmp/phpids_log.txt` - باید توسط سرویس وب قابل نوشتن باشد (اگر قصد استفاده از PHPIDS را دارید ).

**تنظیمات پی‌اچ‌پی**:

* `allow_url_include = on` - اجازه واردکردن فایل ریموت را می‌دهد (RFI)   [[allow_url_include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
* `allow_url_fopen = on` -  اجازه بازکردن فایل ریموت را می‌دهد (RFI)    [[allow_url_fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]
* `safe_mode = off` - (تا نسخه ۵.۴ پی‌اج‌پی) اجازه تزریق اس‌کیو‌ال را می‌دهد (SQLi) [[safe_mode](https://secure.php.net/manual/en/features.safe-mode.php)]
* `magic_quotes_gpc = off` - (تا نسخه ۵.۴ پی‌اج‌پی) اجازه تزریق اس‌کیو‌ال را می‌دهد (SQLi) [[magic_quotes_gpc](https://secure.php.net/manual/en/security.magicquotes.php)]
* `display_errors = off` - (دلخواه) هشدار‌های پی‌اچ‌پی را خاموش می‌کند تا کمتر شلوغ باشد [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]

**File: `config/config.inc.php`**:

* `$_DVWA[ 'recaptcha_public_key' ]` & `$_DVWA[ 'recaptcha_private_key' ]` - این مقادیر باید از اینجا ایجاد شوند: https://www.google.com/recaptcha/admin/create

### اطلاعات ورود پیش‌فرض

**نام کاریری پیش‌فرض = `admin`**

**کلمه عبور پیش‌فرض = `password`**

_...که به راحتی می‌تواند مورد حملات بروت‌فورس قرار گیرد ;)_

نشانی ورود: http://127.0.0.1/login.php

_نکته: اگر DVWA را در مسیرمتفاوتی نصب کرده باشد، این نیز برای شما تفاوت خواهد داشت._

- - -

## کانتینر داکر

_This section of the readme was added by @thegrims, for support on Docker issues, please contact them or @opsxcq who is the maintainer of the Docker image and repo. Any issue tickets will probably be pointed at this and closed._

- [dockerhub page](https://hub.docker.com/r/vulnerables/web-dvwa/)
`docker run --rm -it -p 80:80 vulnerables/web-dvwa`

Please ensure you are using aufs due to previous MySQL issues. Run `docker info` to check your storage driver. If it isn't aufs, please change it as such. There are guides for each operating system on how to do that, but they're quite different so we won't cover that here.

- - -

## Troubleshooting

These assume you are on a Debian based distro, such as Debian, Ubuntu and Kali. For other distros, follow along, but update the command where appropriate.

### I browsed to the site and got a 404

If you are having this problem you need to understand file locations. By default, the Apache document root (the place it starts looking for web content) is `/var/www/html`. If you put the file `hello.txt` in this directory, to access it you would browse to `http://localhost/hello.txt`.

If you created a directory and put the file in there - `/var/www/html/mydir/hello.txt` - you would then need to browse to `http://localhost/mydir/hello.txt`.

Linux is by default case sensitive and so in the example above, if you tried to browse to any of these, you would get a `404 Not Found`:

- `http://localhost/MyDir/hello.txt`
- `http://localhost/mydir/Hello.txt`
- `http://localhost/MYDIR/hello.txt`

How does this affect DVWA? Most people use git to checkout DVWA into `/var/www/html`, this gives them the directory `/var/www/html/DVWA/` with all the DVWA files inside it. They then browse to `http://localhost/` and get either a `404` or the default Apache welcome page. As the files are in DVWA, you must browse to `http://localhost/DVWA`.

The other common mistake is to browse to `http://localhost/dvwa` which will give a `404` because `dvwa` is not `DVWA` as far as Linux directory matching is concerned.

So after setup, if you try to visit the site and get a `404`, think about where you installed the files to, where they are relative to the document root, and what the case of the directory you used is.

### "Access denied" running setup

If you see the following when running the setup script it means the username or password in the config file do not match those configured on the database:

```
Database Error #1045: Access denied for user 'notdvwa'@'localhost' (using password: YES).
```

The error is telling you that you are using the username `notdvwa`.

The following error says you have pointed the config file at the wrong database.

```
SQL: Access denied for user 'dvwa'@'localhost' to database 'notdvwa'
```

It is saying that you are using the user `dvwa` and trying to connect to the database `notdvwa`.

The first thing to do is to double check what you think you put in the config file is what is actually there.

If it matches what you expect, the next thing to do is to check you can log in as the user on the command line. Assuming you have a database user of `dvwa` and a password of `p@ssw0rd`, run the following command:

```
mysql -u dvwa -pp@ssw0rd -D dvwa
```

*Note: There is no space after the -p*

If you see the following, the password is correct:

```
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 14
Server version: 10.3.22-MariaDB-0ubuntu0.19.10.1 Ubuntu 19.10

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [dvwa]>
```

As you can connect on the command line, it is likely something wrong in the config file, double check that and then raise an issue if you still can't get things working.

If you see the following, the username or password you are using is wrong. Repeat the [Database Setup](#database-setup) steps and make sure you use the same username and password throughout the process.

```
ERROR 1045 (28000): Access denied for user 'dvwa'@'localhost' (using password: YES)
```

If you get the following, the user credentials are correct but the user does not have access to the database. Again, repeat the setup steps and check the database name you are using.

```
ERROR 1044 (42000): Access denied for user 'dvwa'@'localhost' to database 'dvwa'
```

The final error you could get is this:

```
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
```

This is not an authentication issue but tells you that the database server is not running. Start it with the following

```sh
sudo service mysql start
```

### Unknown authentication method

With the most recent versions of MySQL, PHP can no longer talk to the database in its default configuration. If you try to run the setup script and get the following message it means you have configuration.

```
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

### Database Error #2002: No such file or directory.

The database server is not running. In a Debian based distro this can be done with:

```sh
sudo service mysql start
```

### Errors "MySQL server has gone away" and "Packets out of order"

There are a few reasons you could be getting these errors, but the most likely is the version of database server you are running is not compatible with the version of PHP.

This is most commonly found when you are running the latest version of MySQL as PHP and it do not get on well. Best advice, ditch MySQL and install MariaDB as this is not something we can support.

For more information, see:

<https://www.ryadel.com/en/fix-mysql-server-gone-away-packets-order-similar-mysql-related-errors/>

### Command Injection won't work

Apache may not have high enough privileges to run commands on the web server. If you are running DVWA under Linux make sure you are logged in as root. Under Windows log in as Administrator.

### Why can't the database connect on CentOS?

You may be running into problems with SELinux.  Either disable SELinux or run this command to allow the web server to talk to the database:

```
setsebool -P httpd_can_network_connect_db 1
```

### Anything else

For the latest troubleshooting information please read both open and closed tickets in the git repo:

<https://github.com/digininja/DVWA/issues>

Before submitting a ticket, please make sure you are running the latest version of the code from the repo. This is not the latest release, this is the latest code from the master branch.

If raising a ticket, please submit at least the following information:

- Operating System
- The last 5 lines from the web server error log directly after whatever error you are reporting occurs
- If it is a database authentication problem, go through the steps above and screenshot each step. Submit these along with a screenshot of the section of the config file showing the database user and password.
- A full description of what is going wrong, what you expect to happen, and what you have tried to do to fix it. "login broken" is no enough for us to understand your problem and to help fix it.

- - -

## SQLite3 SQL Injection

_Support for this is limited, before raising issues, please ensure you are prepared to work on debugging, do not simply claim "it does not work"._

By default, SQLi and Blind SQLi are done against the MariaDB/MySQL server used by the site but it is possible to switch to do the SQLi testing against SQLite3 instead.

I am not going to cover how to get SQLite3 working with PHP, but it should be a simple case of installing the `php-sqlite3` package and making sure it is enabled.

To make the switch, simply edit the config file and add or edit these lines:

```
$_DVWA["SQLI_DB"] = "sqlite";
$_DVWA["SQLITE_DB"] = "sqli.db";
```

By default it uses the file `database/sqli.db`, if you mess it up, simply copy `database/sqli.db.dist` over the top.

The challenges are exactly the same as for MySQL, they just run against SQLite3 instead.

- - -

## Links

Project Home: <https://github.com/digininja/DVWA>

*Created by the DVWA team*

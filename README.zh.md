# 翻译

翻译：@[inVains](https://github.com/inVains) @[songzy12](https://github.com/songzy12) @[cnskis](https://github.com/cnskis)

- - -

# 关于 DAMN VULNERABLE WEB APPLICATION（DVWA）

Damn Vulnerable Web Application (DVWA)(译注：可以直译为："该死的"不安全Web应用程序)，是一个编码差的、易受攻击的 PHP/MySQL Web应用程序。 它的主要目的是帮助信息安全专业人员在合法的环境中，练习技能和测试工具，帮助 Web 开发人员更好地了解如何加强 Web 应用程序的安全性，并帮助学生和教师在可控的教学环境中了解和学习 Web 安全技术。
    
DVWA的目的是通过简单明了的界面来**练习一些最常见的 Web 漏洞**，所练习的漏洞具有**不同的难度级别**。 请注意，此软件**存在提示和无提示的漏洞**。 这是特意为止。 我们鼓励您依靠自己的能力尝试并发现尽可能多的安全问题。
- - -

## 警告！

DVWA非常容易被攻击！  **不要将其上传到您的云服务器上对外公开的 web 文件夹中或任何在公网中的web服务器上**，否则服务器可能会被攻击。 建议使用虚拟机安装DVWA（如[VirtualBox](https://www.virtualbox.org/) 或[VMware](https://www.vmware.com/)），网络配置为NAT组网。在客机（guest machine）中，您可以下载并安装 [XAMPP](https://www.apachefriends.org/) 用作搭建DVWA的 Web 服务和数据库服务。

### 免责声明

我们不对任何人使用此应用程序 (DVWA) 的方式负责。 我们已经明确了应用程序的目的，该程序以及相关技术不应被恶意使用。 我们已警告并采取相关措施阻止用户将 DVWA 安装到生产环境的 Web 服务器上。 如果您的 Web 服务器因安装 DVWA 而被攻击，这不是我们的责任，而是由上传和安装它的人负责。

- - -

## 许可

该文件是Damn Vulnerable Web Application (DVWA) 的一部分。

Damn Vulnerable Web Application (DVWA)是自由软件：您可以根据自由软件基金会发布的 GNU 通用公共许可证（许可证的第 3 版，或（由您选择的）任何更高版本）重新分发和/或修改。

Damn Vulnerable Web Application (DVWA) 的发布是为了希望它有用，但不（对"有用性"）做任何保证； 甚至不对可销售性（MERCHANTABILITY）或针对特定目的的适用性（FITNESS FOR A PARTICULAR PURPOSE）的做任何暗示保证。 有关更多详细信息，请参阅 GNU 通用公共许可证。

您应该已经在Damn Vulnerable Web Application (DVWA)中收到一份GNU通用公共许可证副本。 如果没有，请参阅 <https://www.gnu.org/licenses/>。

- - -

## 国际化

该文件有多种语言版本：

- 英文：[English](README.md)

如果您想贡献翻译，请提交 PR。 但是请注意，这并不意味着只是简单的通过谷歌翻译本文档并提交，这种提交将被拒绝接受。

- - -

## 下载

虽然有各种版本的 DVWA，但唯一受支持的版本是来自官方 GitHub 存储仓库（repository）的最新源码。 你可以从 repo 中克隆它：

``` 
git clone https://github.com/digininja/DVWA.git
``` 

或者 [下载 ZIP 文件](https://github.com/digininja/DVWA/archive/master.zip)。

- - -

## 安装

### 安装视频

- [在 kali 下的 VirtualBox 中安装DVWA](https://www.youtube.com/watch?v=WkyDxNJkgQ4)
- [在 Windows 10 上安装DVWA](https://www.youtube.com/watch?v=cak2lQvBRAo) [12分39秒]

### Windows + XAMPP 方式

如果您没有配置 web 服务器，那么安装 DVWA 最简单的方法就是下载并安装 [XAMPP](https://www.apachefriends.org/) 

XAMPP 可以非常方便快捷的在 Linux, Solaris, Windows and Mac OS X 上安装Apache WEB 服务器， XAMPP 中包含了 Apache web 服务器, MySQL数据库, PHP环境, Perl环境, 一个 FTP 服务器 和 phpMyAdmin服务.

XAMPP 可以在以下地址下载:
<https://www.apachefriends.org/>

只需要解压 dvwa.zip, 然后将解压后的文件放到XAMPP的 web 服务文件夹中, 然后用浏览器打开: `http://127.0.0.1/dvwa/setup.php`

### 配置文件

DVWA 附带了一个示例配置文件，需要根据实际环境复制一份该文件并修改。 比如在 Linux 环境的 DVWA 路径下， 可以直接执行命令:

`cp config/config.inc.php.dist config/config.inc.php`

在 Windows 系统上,操作系统可能默认隐藏了后缀名，稍微有点麻烦，如果不确定是不是隐藏了后缀名，可以参考下面的博客:

[在 Windows 上显示文件后缀名](https://www.howtogeek.com/205086/beginner-how-to-make-windows-show-file-extensions/)

### Linux 软件包

如果您使用的是 Debian 操作系统, 您需要安装以下依赖软件包 _(或者其他能实现相同功能的)_:

- apache2
- libapache2-mod-php
- mariadb-server
- mariadb-client
- php php-mysqli
- php-gd

我们建议在安装之前进行更新，这样可以确保安装的都是最新版本。

下面是更新和安装依赖的命令：

```
apt update
apt install -y apache2 mariadb-server mariadb-client php php-mysqli php-gd libapache2-mod-php
```

DVWA 默认使用MySQL数据库而不是 MariaDB 数据库，但是我们强烈推荐使用 MariaDB 数据库，因为MariaDB数据库无需额外配置开箱即用，MySQL 需要手动配置才行。

### 数据库配置

配置数据库很简单, 在主菜单上单击 `Setup DVWA`, 然后单击 `Create / Reset Database`. 系统会创建 / 重置 数据库并插入其他数据。

如果在创建数据库的时候报错, 务必确保在 `./config/config.inc.php` 中的配置信息是正确的。 *不同于 config.inc.php.dist, 后者只是示例文件，请根据实际情况进行配置。*

该文件的默认配置如下:

```php
$_DVWA[ 'db_server'] = '127.0.0.1';
$_DVWA[ 'db_port'] = '3306';
$_DVWA[ 'db_user' ] = 'dvwa';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';
$_DVWA[ 'db_database' ] = 'dvwa';
```

有一点要注意，如果您用的是 MariaDB 而不是 MySQL (Kali中默认是 MariaDB ), 那么您将无法在数据库中使用root用户, 您必须创建一个新的数据库用户. 因此, 需要先用root用户登录数据库，然后执行以下命令:

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

### 其他配置

根据您的操作系统以及PHP版本等，修改默认配置以达到需求，因环境不同，配置文件的位置也是不同的。

**文件夹权限**:

* `./hackable/uploads/` - 需要授予 web 服务可写权限 (用作存储上传的文件).
* `./external/phpids/0.6/lib/IDS/tmp/phpids_log.txt` - 需要授予 web 服务可写权限 (如果您想使用PHPIDS的话).

**PHP 配置**:

* `allow_url_include = on` - 允许包含远程文件 (RFI)   [[启用url-include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
* `allow_url_fopen = on` -  允许远程访问（就是请求http） (RFI)    [[启用url-fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]
* `safe_mode = off` - (如果 PHP 版本 <= v5.4) 允许SQL注入 (SQLi) [[安全模式](https://secure.php.net/manual/en/features.safe-mode.php)]
* `magic_quotes_gpc = off` - (如果 PHP 版本 <= v5.4) 允许SQL注入 (SQLi) [[魔术引号](https://secure.php.net/manual/en/security.magicquotes.php)]
* `display_errors = off` - (可选) 不显示PHP警告消息 [[关闭错误显示](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]

**`config/config.inc.php` 文件配置**:

* `$_DVWA[ 'recaptcha_public_key' ]` & `$_DVWA[ 'recaptcha_private_key' ]` - 这里的值可以在此网址生成: https://www.google.com/recaptcha/admin/create

### 默认用户与密码

**默认用户 = `admin`**

**默认密码 = `password`**

_...很容易被破解 ;)_

登录地址: http://127.0.0.1/login.php

_注意: 根据DVWA实际安装位置自行调整。_

- - -

## Docker 容器配置

_这一部分说明由 @thegrims 添加，有关Docker的问题或支持，请联系他们或 @opsxcq，他是Docker映像和repo的维护者。任何问题都可能会被指向此处并解决 _

- [dockerhub 地址](https://hub.docker.com/r/vulnerables/web-dvwa/)
`docker run --rm -it -p 80:80 vulnerables/web-dvwa`

由于以前的MySQL问题，请确保您正在使用 aufs 。 执行 `docker info` 命令进行检查。 如果不是 aufs, 请改为 aufs， 每个操作系统都有修改的方法，且各个差异较大，此处不做赘述。

- - -

## 常见问题

这些问题与解决方法是认为你在基于Debian的发行版上配置的DVWA，比如Debian，Ubuntu和Kali。对于其他发行版，大同小异，但是需要根据实际情况进行修改。

### 打开网站 404 Not Found

如果遇到了这个问题，首先需要知道文件所在位置。 默认情况下Apache WEB 服务的网站根目录位于 `/var/www/html`. 比如，放一个测试文件 `hello.txt` 到该目录, 那么在本机浏览器访问 `http://localhost/hello.txt` 就可以看到该文件的内容。

比如将该文件放在 - `/var/www/html/mydir/hello.txt` - 那么需要在网址后加上文件夹名，如： `http://localhost/mydir/hello.txt`.

Linux 系统是大小写敏感的，如果按下面的地址访问，都会提示 `404 Not Found`:

- `http://localhost/MyDir/hello.txt`
- `http://localhost/mydir/Hello.txt`
- `http://localhost/MYDIR/hello.txt`

这对 DVWA 有何影响？大部分人都是直接用 git 将DVWA 克隆到 `/var/www/html`, 那么此时 DVWA 的目录为： `/var/www/html/DVWA/` 这里面包含了 DVWA 所有的文件. 此时访问 `http://localhost/` 就会提示 `404` 或者是Apache的默认欢迎页面。 像这种情况，需要将访问网址改为： `http://localhost/DVWA`.

还有一种常见错误是在访问 `http://localhost/dvwa` 时也会报 `404` 因为Linux大小写敏感，认为 `dvwa` 与 `DVWA` 是两个不同的路径 。

所以在安装完以后, 如果打开网站提示 `404`, 检查文件是不是在 web 服务器的网站根目录下，然后确定大小写是否正确。

### 安装中提示 "Access denied" 

如果在安装过程中提示 Access denied ，请检查配置文件中的数据库账号密码是否正确:

```
Database Error #1045: Access denied for user 'notdvwa'@'localhost' (using password: YES).
```

该错误提示正在使用的数据库用户名为：notdvwa

下面的错误提示无法访问数据库，很可能是数据库配置错了。

```
SQL: Access denied for user 'dvwa'@'localhost' to database 'notdvwa'
```

说明正在使用 dvwa 用户访问 notdvwa数据库，但是访问被拒绝。

首先确定配置文件是存在的。

如果文件确实存在，那么在命令行下检查一下， 比如用户名为： `dvwa` 密码为： `p@ssw0rd`, 那么执行以下命令:

```
mysql -u dvwa -pp@ssw0rd -D dvwa
```

*注: 参数-p后面没有空格*

如果看到以下提示信息，那么说明账号密码是正确的:

```
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 14
Server version: 10.3.22-MariaDB-0ubuntu0.19.10.1 Ubuntu 19.10

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [dvwa]>
```

在命令行中进行连接是正常的, 那么问题可能出在配置文件上, 再仔细检查一遍配置文件，看看是不是可以使用，如果还不行，再提issue。

如果看到以下提示信息，说明当前使用的账号密码不正确。 重新进行 [数据库配置](#database-setup) 确保使用的账号密码是正确的。

```
ERROR 1045 (28000): Access denied for user 'dvwa'@'localhost' (using password: YES)
```
如果看到以下提示信息，说明当前使用的账号密码是正确的，但是没有访问当前数据库的权限。重新配置数据库，检查一下配置的数据库名是否正确。

```
ERROR 1044 (42000): Access denied for user 'dvwa'@'localhost' to database 'dvwa'
```

最后一个可能遇到的错误如下:

```
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
```

这不是说账号密码不正确，而是数据库没启动，执行如下命令：

```sh
sudo service mysql start
```

### Unknown authentication method

在 MySQL 最新的几个版本中, PHP的默认配置无法连接数据库。 此时进行安装配置，会提示以下消息，那么需要手动修改配置。

```
Database Error #2054: The server requested authentication method unknown to the client.
```

有两个办法，最简单的就是卸载 MySQL 安装 MariaDB 就行了。 下面是 MariaDB 的官方文档:

<https://mariadb.com/resources/blog/how-to-migrate-from-mysql-to-mariadb-on-linux-in-five-steps/>

另一个办法如下:

1. 用 root 身份编辑该配置文件: `/etc/mysql/mysql.conf.d/mysqld.cnf`
1. 在 `[mysqld]` 此行, 添加如下内容:
  `default-authentication-plugin=mysql_native_password`
1. 重启数据库，命令: `sudo service mysql restart`
1. 查询数据库用户的身份认证方式:

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------------------+-----------------------+
    | Host      | User             | plugin                |
    +-----------+------------------+-----------------------+
    | localhost | dvwa             | caching_sha2_password |
    +-----------+------------------+-----------------------+
    1 rows in set (0.00 sec)
    ```

1. 如果显示的是 `caching_sha2_password`. 那么执行下面的命令:

    ```sql
    mysql> ALTER USER dvwa@localhost IDENTIFIED WITH mysql_native_password BY 'p@ssw0rd';
    ```

1. 再查一遍，应该显示的是 `mysql_native_password`.

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------+-----------------------+
    | Host      | User | plugin                |
    +-----------+------+-----------------------+
    | localhost | dvwa | mysql_native_password |
    +-----------+------+-----------------------+
    1 row in set (0.00 sec)
    ```

修改完成后，DVWA 安装程序应该可以正常进行。

如果想了解更多相关内容，请访问: <https://www.php.net/manual/en/mysqli.requirements.php>.

### Database Error #2002: No such file or directory.

数据库没有启动。 在 Debian 上执行如下命令即可:

```sh
sudo service mysql start
```

### Errors "MySQL server has gone away" and "Packets out of order"

出现这个错误有多个原因，最有可能是当前数据库版本和 PHP 版本不兼容导致的。

很有可能是当前用的数据库是最新的，导致 PHP 不兼容， 最好的办法还是放弃 MySQL 安装 MariaDB ，因为不兼容问题，我们也无法提供支持。

更多相关内容，请访问:

<https://www.ryadel.com/en/fix-mysql-server-gone-away-packets-order-similar-mysql-related-errors/>

### 命令注入没反应

这个原因是 Apache 没有权限执行系统命令，如果是 Linux 系统，请用root用户启动Apache， 如果是Windows请以管理员身份启动Apache。

### 在 CentOS 上连不上数据库？

很有可能是启用了 SELinux.  要么关闭 SELinux 要么执行下面的命令，以允许数据库访问:

```
setsebool -P httpd_can_network_connect_db 1
```

### 更多

更多问题请参考以下仓库中已关闭的 issue :

<https://github.com/digininja/DVWA/issues>

在提交issue之前，确保使用的是该仓库最新版本的代码。本仓库代码不是最新的, 只是主干中的最新代码。

如果需要提交issue，请至少提供以下信息:

- 操作系统是什么？
- 出现错误后的web容器中最后至少五行日志。
- 如果是数据库认证问题，那就重新进行一遍上面的步骤，截图每一步。将这些截图与显示数据库用户和密码的配置文件部分的屏幕截图一起提交。
- 对该问题的详细描述，你觉得会发生什么，以及你已经尽力去解决它。像 "登录失败" 不足以让我们明白您的问题出在哪里，也无法帮助您解决。
- - -

## SQLite3 SQL 注入

_对该部分的支持是有限的, 在提交issue之前，确保已经尝试尽力去解决, 而不是简单的一句 "它没反应"。_

通常情况下 SQL 注入 和 SQL 盲注 都是对使用 MySQL 和 MariaDB 数据库站点进行测试的，但是也可以用在sqlite上。

我不打算介绍如何在PHP中使用 SQLite3 ， 不过安装 `php-sqlite3` 依赖来实现 PHP 连接 SQLite3 应该是比较简单的。

要切换为 SQLite3 只需要编辑下面几行:

```
$_DVWA["SQLI_DB"] = "sqlite";
$_DVWA["SQLITE_DB"] = "sqli.db";
```

默认情况，使用的是 `database/sqli.db` 文件, 如果配置错了或者崩了，只要复制一份 `database/sqli.db.dist` 覆盖掉原文件就行了。

可能出现的问题和 MySQL 差不多，唯一不同的是，当前数据库是SQLite3

- - -

## 关于

项目地址: <https://github.com/digininja/DVWA>

*DVWA 团队*

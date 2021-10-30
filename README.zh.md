# Translation

翻译：@[inVains](https://github.com/inVains) @[songzy12](https://github.com/songzy12)

- - -

# DAMN VULNERABLE WEB APPLICATION

Damn Vulnerable Web Application (DVWA)(译注：可以直译为："该死的"不安全Web应用网站)，是一个编码糟糕的、易受攻击的 PHP/MySQL Web应用程序。 它的主要目的是帮助安全专业人员在合法的环境中，测试他们的技能和工具，帮助 Web 开发人员更好地了解如何增强 Web 应用程序的安全性，并帮助学生和教师在受控的课堂环境中，了解 Web 应用程序的安全。
    
DVWA的具体目标是通过简单明了的界面，来**演练一些最常见的 Web 漏洞**，这些漏洞具有**不同的难度级别**。 请注意，此软件**存在说明和未说明的漏洞**。 这是故意的。 我们鼓励您尝试并发现尽可能多的安全问题。
- - -

## 警告！

DVWA十分易受攻击！  **不要将其上传到您的云服务器的公共 html 文件夹或任何面向 Internet 的服务器**，因为它们会受到危害。 建议使用虚拟机（如[VirtualBox](https://www.virtualbox.org/) 或[VMware](https://www.vmware.com/)），设置为NAT组网方式。在客机（guest machine）中，您可以下载并安装 [XAMPP](https://www.apachefriends.org/en/xampp.html) 作为 Web 服务器和数据库。

### 免责声明

我们不对任何人使用此应用程序 (DVWA) 的方式负责。 我们已经明确了应用程序的目的，不应被恶意使用。 我们已发出警告并采取措施防止用户将 DVWA 安装到实际生产运行的 Web 服务器上。 如果您的 Web 服务器因安装 DVWA 而受到损害，这不是我们的责任，而是上传和安装它的人的责任。

- - -

## 许可

该文件是Damn Vulnerable Web Application (DVWA) 的一部分。

Damn Vulnerable Web Application (DVWA)是自由软件：您可以根据自由软件基金会发布的 GNU 通用公共许可证（许可证的第 3 版，或（由您选择的）任何更高版本）重新分发和/或修改。

Damn Vulnerable Web Application (DVWA) 的发布是为了希望它有用，但不（对"有用性"）做任何保证； 甚至不对适销性（MERCHANTABILITY）或针对特定目的的适用性（FITNESS FOR A PARTICULAR PURPOSE）的做任何暗示保证。 有关更多详细信息，请参阅 GNU 通用公共许可证。

您应该已经随Damn Vulnerable Web Application (DVWA)收到一份GNU通用公共许可证。 如果没有，请参阅 <http://www.gnu.org/licenses/>。

- - -

## 国际化

该文件有多种语言版本：

- 英文：[English](README.md)

如果您想贡献翻译，请提交 PR。 但是请注意，这并不意味着只是简单的通过谷歌翻译并提交，这种提交将被拒绝。

- - -

## 下载

虽然有各种版本的 DVWA，但唯一受支持的版本是来自官方 GitHub 存储仓库（repository）的最新源码。 你可以从 repo 中克隆它：

``` 
git clone https://github.com/digininja/DVWA.git
``` 

或者 [下载文件的 ZIP](https://github.com/digininja/DVWA/archive/master.zip)。

- - -

## 安装

**请确保您的 config/config.inc.php 文件存在。 只有 config.inc.php.dist 是不够的，您必须编辑它以适应您的环境并将其重命名为 config.inc.php。  [Windows 可能会隐藏文件扩展名。](https://support.microsoft.com/en-in/help/865219/how-to-show-or-hide-file-name-extensions-in-windows-explorer)**

### 安装视频

- [在 Windows 10 上安装 DVWA（Installing Damn Vulnerable Web Application (DVWA) on Windows 10）](https://www.youtube.com/watch?v=cak2lQvBRAo) [12:39 分钟]

### Windows + XAMPP

如果您还没有设置 Web 服务器，安装 DVWA 的最简单方法是下载并安装 [XAMPP](https://www.apachefriends.org/en/xampp.html)。

XAMPP 是一个非常易于安装的 Apache 发行版，适用于 Linux、Solaris、Windows 和 Mac OS X。该软件包包括 Apache Web 服务器、MySQL、PHP、Perl、一个 FTP 服务器和 phpMyAdmin。

XAMPP 可以从以下位置下载：https://www.apachefriends.org/en/xampp.html 

只需解压缩 dvwa.zip，将解压缩的文件放在您的公共 html 文件夹中，然后使用浏览器访问：`http://127.0.0.1/dvwa/setup.php`

### Linux Packages

如果您使用的是基于 Debian 的 Linux 发行版，则需要安装以下软件包 _（或与它们具有相同功能的软件包）_：

`apt-get -y install apache2 mariadb-server php php-mysqli php-gd libapache2-mod-php` 

该站点在使用 MySQL 时也可正常运行，但我们强烈推荐 MariaDB。因为它开箱即用，而您必须进行配置更改才能使 MySQL 正常工作。

### 数据库设置

要设置数据库，只需单击主菜单中的`Setup DVWA`按钮，然后单击`Create / Reset Database`按钮。 这将为您创建/重置数据库，并填入一些数据。

如果您在尝试创建数据库时收到错误消息，请确保您在 `./config/config.inc.php` 中的数据库凭据是正确的。  *config.inc.php.dist 仅作为示例，./config/config.inc.php 中的内容不必与其相同。*

变量默认设置如下： 

```php
$_DVWA[ 'db_user' ] = 'dvwa';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';
$_DVWA[ 'db_database' ] = 'dvwa';
```

注意，如果你使用的是 MariaDB 而不是 MySQL（ Kali 默认使用 MariaDB ），那么你不能使用数据库 root 用户，你必须创建一个新的数据库用户。 为此，请以 root 用户身份连接到数据库，然后使用以下命令：

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

根据您的操作系统以及 PHP 版本，您可能希望更改默认配置。 相关文件的位置因机器而异。

**文件夹权限**：

* `./hackable/uploads/` - 需要允许web服务可写（用于文件上传）。
* `./external/phpids/0.6/lib/IDS/tmp/phpids_log.txt` - 需要允许web服务可写（如果你想使用 PHPIDS）。

**PHP配置**:

* `allow_url_include = on` - 允许远程文件包含 (RFI) [[allow_url_include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
* `allow_url_fopen = on` - 允许远程文件包含 (RFI) [[allow_url_fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]
* `safe_mode = off` - （如果 PHP <= v5.4）允许 SQL 注入（SQLi） [[safe_mode](https://secure.php.net/manual/en/features.safe-mode.php)]
* `magic_quotes_gpc = off` - （如果 PHP <= v5.4）允许 SQL 注入（SQLi） [[magic_quotes_gpc](https://secure.php.net/manual/en/security.magicquotes.php)] 
* `display_errors = off` - （可选）隐藏 PHP 警告消息以使其不那么冗长 [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]

**文件: `config/config.inc.php`**:
* `$_DVWA[ 'recaptcha_public_key' ]` & `$_DVWA[ 'recaptcha_private_key' ]` - 这些值需要从https://www.google.com/recaptcha/admin/create 生成

### 默认的凭证

**默认 username = `admin`**

**默认 password = `password`**

_...很容易被暴力破解；)_

登录 URL：http://127.0.0.1/login.php

_注意：如果您将 DVWA 安装到不同的目录中，上述登录 URL 将有所不同。_

- - -

## Docker容器
- [dockerhub 页面](https://hub.docker.com/r/vulnerables/web-dvwa/)
`docker run --rm -it -p 80:80 vulnerables/web-dvwa`

由于老版本的 MySQL 问题，请确保您正在使用 aufs。 运行 `docker info` 来检查你的存储驱动程序。 如果它不是aufs，请更改它为aufs。 每个操作系统都有关于如何执行此操作的指南，但它们有所不同，因此我们不再此赘述。

- - -

## 故障排除

以下的故障排除操作，假设您使用的是基于 Debian 的发行版，例如 Debian、Ubuntu 和 Kali。 对于其他发行版，可参考执行，但需要适当更换命令。

### 配置数据库时的"Access denied"错误

如果您在配置数据库时看到以下内容，则表示配置文件（./config/config.inc.php）中的用户名或密码，与数据库中配置的用户名或密码不匹配：

```
Database Error #1045: Access denied for user 'notdvwa'@'localhost' (using password: YES).
```

该错误告诉您，您正在使用用户名`notdvwa`。

以下错误表示，您在配置文件中设置了错误的数据库。

```
SQL: Access denied for user 'dvwa'@'localhost' to database 'notdvwa'
```

这表示您正在使用用户 `dvwa` 并试图连接到数据库 `notdvwa`。

首先要做的，是再次确认您配置文件中的内容是否真的如您所想。

如果它符合您的预期，接下来要做的是检查，您是否可以使用命令行，以您配置的用户身份登录数据库。 假设你的数据库用户是 `dvwa`，密码是 `p@ssw0rd`，运行以下命令： 

```
mysql -u dvwa -pp@ssw0rd -D dvwa
```

*注意：-p后没有空格*

如果您看到以下内容，则密码是正确的：

```
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 14
Server version: 10.3.22-MariaDB-0ubuntu0.19.10.1 Ubuntu 19.10

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [dvwa]>
```

由于您可以在命令行上进行连接，因此配置文件中可能有问题，请仔细检查。如果仍然无法正常工作，请在 GitHub 上提交issue。

如果您看到以下内容，则您使用的用户名或密码有误。 重复 [数据库设置](#数据库设置) 步骤，并确保在整个过程中使用相同的用户名和密码。

```
ERROR 1045 (28000): Access denied for user 'dvwa'@'localhost' (using password: YES)
```

如果您得到以下信息，则用户凭据正确，但用户无权访问数据库。 再次重复设置步骤并检查您正在使用的数据库名称。

```
ERROR 1044 (42000): Access denied for user 'dvwa'@'localhost' to database 'dvwa'
```

最后一个您可能遇到的错误是：

```
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
```

这不是身份验证问题，而是告诉您数据库服务器没有运行。 如下启动数据库服务器：

```sh
sudo service mysql start
```

### Unknown authentication method错误

PHP 无法再与最新版本的 MySQL 默认配置中的数据库通信。 如果您尝试运行配置脚本并收到以下消息，则表示您正在使用这个默认配置。

```
Database Error #2054: The server requested authentication method unknown to the client.
```

您有两个选择，最简单的是卸载 MySQL 并安装 MariaDB。 以下是来自 MariaDB 项目的官方指南：

<https://mariadb.com/resources/blog/how-to-migrate-from-mysql-to-mariadb-on-linux-in-five-steps/>

或者，按照以下步骤操作：
1. 以 root 用户身份编辑以下文件：`/etc/mysql/mysql.conf.d/mysqld.cnf`
2. 在 `[mysqld]` 行下，添加以下内容： `default-  authentication-plugin=mysql_native_password`
3. 重启数据库：`sudo service mysql restart`
4. 检查数据库用户的身份验证方法：

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------------------+-----------------------+
    | Host      | User             | plugin                |
    +-----------+------------------+-----------------------+
    | localhost | dvwa             | caching_sha2_password |
    +-----------+------------------+-----------------------+
    1 rows in set (0.00 sec)
    ```
5. 你可能会看到 `caching_sha2_password`。 如果是这样，请运行以下命令：

    ```sql
    mysql> ALTER USER dvwa@localhost IDENTIFIED WITH mysql_native_password BY 'p@ssw0rd';
    ```
6. 重新运行检查，您现在应该看到`mysql_native_password`。
    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------+-----------------------+
    | Host      | User | plugin                |
    +-----------+------+-----------------------+
    | localhost | dvwa | mysql_native_password |
    +-----------+------+-----------------------+
    1 row in set (0.00 sec)
    ```

经过以上步骤，设置过程现在应该可以正常工作了。

如果您想了解更多信息，请参阅以下页面：<https://www.php.net/manual/en/mysqli.requirements.php>。

### 数据库错误 Error #2002: No such file or directory.

数据库服务器没有运行。 在基于 Debian 的发行版中，这可以通过以下方式完成：

```sh
sudo service mysql start
```

### "MySQL server has gone away" 和 "Packets out of order" 错误

出现这些错误的原因有多种，但最有可能的原因是您运行的数据库服务器版本与 PHP 版本不兼容。

最常见的是，你运行了最新版本的 MySQL 与 PHP 搭配部署，而这两者并不十分兼容。最好的建议是，放弃 MySQL 并安装 MariaDB，因为这（译注：使用最新版MySQL）不是我们可以支持的。

有关更多信息，请参阅：

<https://www.ryadel.com/en/fix-mysql-server-gone-away-packets-order-similar-mysql-related-errors/>

### SQL注入在 PHP v5.2.6.上不正常

PHP 5.x 的维护已于 2019 年 1 月终止，因此我们建议您使用当前 7.x 版本运行 DVWA，如果您一定要使用 5.x ……

如果您使用的是 PHP v5.2.6 或更高版本，则需要执行以下操作才能使 SQL 注入和其他漏洞发挥作用。

在`.htaccess`中：

将以下设置：

```php
<IfModule mod_php5.c>
    php_flag magic_quotes_gpc off
    #php_flag allow_url_fopen on
    #php_flag allow_url_include on
</IfModule>
```

替换为：

```php
<IfModule mod_php5.c>
    magic_quotes_gpc = Off
    allow_url_fopen = On
    allow_url_include = On
</IfModule>
```

### 命令行注入 不工作

-A. Apache 可能没有足够的权限在 Web 服务器上运行命令。 如果您在 Linux 下运行 DVWA，请确保您以 root 身份登录。 在 Windows 下以管理员身份登录。

### CentOS上为什么不能连接数据库？

您可能遇到 SELinux 的问题。 禁用 SELinux 或运行此命令以允许 Web 服务器与数据库通信：

```
setsebool -P httpd_can_network_connect_db 1
```

### 其他事项

有关最新的故障排除信息，请阅读 GitHub Issues 中仍开放或已关闭的问题：

<https://github.com/digininja/DVWA/issues>

在提交issue之前，请确保您正在运行仓库（repo）中最新版本的代码。注意：不是最新发布 (release) 版本，而是 master 分支的最新代码。

如果提交issue，请至少包含以下信息： 

- 操作系统 
- 您正在报告的错误发生时，紧接着来自 Web 服务器错误日志的最后 5 行 
- 如果是数据库身份验证问题，请执行上文中的步骤并对每个步骤进行截图。提交这些截图，同时提交数据库配置文件中，用户和密码部分的屏幕截图。
- 完整描述出了什么问题，您期望发生什么，以及您已经采取了什么措施。 类似"登录中断"这种描述，不足以让我们了解您的问题并帮助解决它。

- - -

## 链接

主页: <http://www.dvwa.co.uk/>

项目主页: <https://github.com/digininja/DVWA>

*Created by the DVWA team*

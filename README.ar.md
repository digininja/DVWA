# DAMN VULNERABLE WEB APPLICATION

إن  Damn Vulnerable Web Application (DVWA) هو تطبيق ويب تم إضعافه عمداً ومصمم بـ PHP / MySQL. الهدف الرئيسي هو مساعدة مختصي أمن المعلومات وذلك باختبار مهاراتهم وأدواتهم في بيئة تشبه البيئة الحقيقية، ومساعدة مطوري الويب على فهم طرق تأمين تطبيقات الويب بشكل أفضل ومساعدة كل من الطلاب والمدرسين في التعرف على أمان تطبيقات الويب في بيئة محكمة.

الهدف من DVWA هو **التدرب على بعض نقاط الضعف على الويب الأكثر شيوعًا** ، ضمن **مستويات مختلفة من الصعوبة** ، بواجهة بسيطة ومباشرة.
يرجى ملاحظة أن هناك **ثغرات موثقة وغير موثقة** في هذا التطبيق ,هو إجراء متعمد. نحن نشجع على محاولة اكتشاف أكبر عدد ممكن من المشكلات.
- - -

## تحذير!

إن  Damn Vulnerable Web Application (DVWA) ضعيف للغاية أمنياً! **لا تضعه في مجلد html العام في الاستضافة الخاصة بك أو الخوادم التي تعمل على الانترنت** ، إذ أنه سيتم اختراقها. يُوصى باستخدام كيان افتراضي (مثل [VirtualBox] (https://www.virtualbox.org/)  أو [VMware] (https://www.vmware.com/)) ، ويتم تعيينه على وضع شبكة NAT، يمكنك تنزيل وتثبيت [XAMPP] (https://www.apachefriends.org/) لخادم الويب وقاعدة البيانات.


### إخلاء مسؤولية

نحن لا نتحمل مسؤولية الطريقة التي يستخدم بها أي شخص هذا التطبيق (DVWA). إذ أننا أوضحنا أغراض التطبيق ولا ينبغي استخدامه بشكل ضار. لقد أصدرنا تحذيرات واتخذنا تدابير لمنع المستخدمين من تثبيت DVWA على خوادم الويب الحقيقية. إذا تم اختراق خادم الويب الخاص بك عن طريق تثبيت DVWA ، فهذه ليست مسؤوليتنا ، بل تقع على عاتق الشخص / الأشخاص الذين قاموا بتحميله وتثبيته.

- - -

## ترخيص

هذا الملف جزء من Damn Vulnerable Web Application (DVWA).

يعد تطبيق Damn Vulnerable Web Application (DVWA) برنامجًا مجانيًا: يمكنك إعادة توزيعه و / أو تعديله
بموجب شروط   GNU General Public License  كما تم نشرها بواسطة
Free Software Foundation ، إما الإصدار 3 من الترخيص ، أو 
(حسب اختيارك) أي إصدار لاحق.

يتم توزيع Damn Vulnerable Web Application (DVWA) لتحقيق الفائدة ، 
ولكن دون أي ضمان ؛ حتى بدون الضمان الضمني لـ 
القابلية للتسويق أو الملاءمة لغرض معين. يرجى الاطلاع على 
ترخيص  GNU General Public License لمزيد من التفاصيل.


يجب أن تكون قد تلقيت نسخة من ترخيص    GNU General Public License 
مع   Damn Vulnerable Web Application (DVWA)، إذا لم تتلقى هذه الرخصة، يرجى الاطلاع  على   <https://www.gnu.org/licenses/>.

- - -

## الترجمة

هذا الملف متوفر بعدة لغات:

- الصينية: [简体中文](README.zh.md)
- التركية: [Türkçe](README.tr.md)
- العربية: [العربية](README.ar.md)

إذا كنت ترغب في المساهمة في ترجمة ، يرجى تقديم  PR . ولا يعني ذلك مجرد استخدام خدمة الترجمة من Google وإرسال المساهمة ، إذ أنه سيتم رفضها.

- - -

## التحميل

توجد إصدارات مختلفة من DVWA حولها ، والإصدار الوحيد المدعوم هو أحدث مصدر من مستودع GitHub الرسمي. يمكنك إما سحب نسخة clone من الريبو Repo:

```
git clone https://github.com/digininja/DVWA.git
```

أو  [تحميل ملف ZIP للملفات](https://github.com/digininja/DVWA/archive/master.zip).

- - -

## التثبيت

**يرجى التأكد من وجود ملف config / config.inc.php الخاص بك. إن وجود ملف config.inc.php.dist بمفرده لن يكون كافيًا ويجب عليك تعديله ليلائم بيئتك وإعادة تسميته إلى config.inc.php ، قد يخفي Windows امتدادات الملفات، يجب عليك إظهارها لتعديل امتداد الملف.](https://www.howtogeek.com/205086/beginner-how-to-make-windows-show-file-extensions/)**

### فيديو التثبيت

- [تثبيت Damn Vulnerable Web Application (DVWA)  على نظام التشغيل Windows 10 ](https://www.youtube.com/watch?v=cak2lQvBRAo) [12:39 دقيقة]

### تثبيت Windows + XAMPP

أسهل طريقة لتثبيت DVWA هي تحميل [XAMPP] وتثبيته (https://www.apachefriends.org/) إذا لم يكن لديك خادم الويب جاهز ومعد مسبقاً.

يعد XAMPP  وسيلة سهلة لتثبيت Apache Distribution في أنظمة Linux و Solaris و Windows و Mac OS X. تتضمن الحزمة خادم الويب Apache و MySQL و PHP و Perl وخادم FTP و phpMyAdmin.

يمكن تحميل XAMPP من هنا:
<https://www.apachefriends.org/>

ببساطة قم بفك ضغط dvwa.zip ، ضع الملفات التي تم فك ضغطها في مجلد html العام ، ثم اطلب العنوان التالي من المتصفح: `http://127.0.0.1/dvwa/setup.php`

### حزم Linux
إذا كنت تستخدم توزيعة Linux مبنية على Debian ، فستحتاج إلى تثبيت الحزم التالية _ (أو ما يكافئها) _:

`apt-get -y install apache2 mariadb-server php php-mysqli php-gd libapache2-mod-php`

سيعمل الموقع مع MySQL بدلاً من MariaDB لكننا نوصي بشدة باستخدام MariaDB لأنه يعمل خارج الصندوق، سيتعين عليك إجراء تغييرات لتمكين  MySQL  من العمل بشكل صحيح.

### إعداد قاعدة البيانات

لإعداد قاعدة البيانات ، ما عليك سوى الضغط على الزر `Setup DVWA` في القائمة الرئيسية ، ثم االضغط على الزر `Create / Reset Database`. سيؤدي هذا إلى إنشاء / إعادة تعيين قاعدة البيانات وإضافة بعض البيانات.

إذا ظهر خطأ أثناء محاولة إنشاء قاعدة البيانات، فتأكد من صحة بيانات الدخول قاعدة البيانات (اسم المستخدم وكلمة المرور) في الملف `/config/config.inc.php` *وهذا الملف يختلف عن config.inc.php.dist والذي يعتبر مثال.*

تم ضبط قيم المتحولات التالية افتراضياً وفق ما يلي:

```php
$_DVWA[ 'db_server'] = '127.0.0.1';
$_DVWA[ 'db_port'] = '3306';
$_DVWA[ 'db_user' ] = 'dvwa';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';
$_DVWA[ 'db_database' ] = 'dvwa';
```

ملاحظة ، إذا كنت تستخدم MariaDB بدلاً من MySQL (يعد MariaDB افتراضيًا في Kali) ، فلا يمكنك استخدام root كمستخدم، يجب عليك إنشاء مستخدم قاعدة بيانات جديد. للقيام بذلك ، اتصل بقاعدة البيانات بصفتك المستخدم root، ونفذ الأوامر التالية:

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

### تكوينات  أخرى

اعتمادًا على نظام التشغيل الخاص بك وإصدار PHP ، قد ترغب في تغيير التكوين الافتراضي default configuration. سيكون موقع الملفات مختلفًا حسب كل جهاز.

**سماحيات المجلد**:
* المسار `/hackable/uploads/` - يجب أن تستطيع خدمة الويب الكتابة على هذا الملف (لتنفيذ وظيفة تحميل الملف).
* المسار `/external/phpids/0.6/lib/IDS/tmp/phpids_log.txt` - يجب أن تستطيع خدمة الويب الكتابة على هذا الملف (إذا كنت ترغب باستخدام PHPIDS).


**تكوين PHP**:
* الخيار `allow_url_include = on` - السماح بتضمين الملفات عن بعد  Remote File Inclusions (RFI)   [[allow_url_include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
* الخيار `allow_url_fopen = on` -  السماح بتضمين الملفات عن بعد  Remote File Inclusions (RFI)    [[allow_url_fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]
* الخيار `safe_mode = off` - (إذا كان إصدار PHP أقل من أو يساوي 5.4) السماح بحقن SQL  - SQL Injection (SQLi) [[safe_mode](https://secure.php.net/manual/en/features.safe-mode.php)]
* الخيار `magic_quotes_gpc = off` - (إذا كان إصدار PHP أقل من أو يساوي 5.4) السماح بحقن SQL  - SQL Injection (SQLi) [[magic_quotes_gpc](https://secure.php.net/manual/en/security.magicquotes.php)]
* الخيار `display_errors = off` - (اختياري)  إخفاء رسائل تحذير PHP لجعلها أقل إسهابًا  [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]

**الملف:  `config/config.inc.php`**:

* المتحولات `$_DVWA[ 'recaptcha_public_key' ]`  و`$_DVWA[ 'recaptcha_private_key' ]`  يجب توليد قيم هذه المتحولات وذلك من خلال:  https://www.google.com/recaptcha/admin/create

### بيانات الدخول الافتراضية

**اسم المستخدم الالافتراضي  = `admin`**

**كلمة المرور الافتراضية  = `password`**

_...يمكن بسهولة تخمينها باستخدام هجوم Brute Force ;)_

رابط تسجيل الدخول : http://127.0.0.1/login.php

_ملاحظة: سيختلف الرابط في حال تثبيت DVWA في مسار مختلف._

- - -

## حاوية Docker

- يمكنك زيارة [dockerhub صفحة](https://hub.docker.com/r/vulnerables/web-dvwa/)
`docker run --rm -it -p 80:80 vulnerables/web-dvwa`

يرجى التأكد من أنك تستخدم aufs بسبب مشاكل MySQL السابقة. نفذ الأمر `docker info` للتحقق من storage driver. إذا لم يكن aufs ، يرجى تغييره على هذا النحو. هناك أدلة لكل نظام تشغيل حول كيفية القيام بذلك ، لكنها مختلفة تمامًا لذا لن نغطي ذلك هنا.

- - -

## استكشاف الأخطاء وإصلاحها

يفترض هذا أنك تستخدم توزيعة قائمة على Debian ، كـ Debian و Ubuntu و Kali. بالنسبة إلى التوزيعات الأخرى ، اتبع ذلك ، ولكن قم بتعديل الأمر عند الضرورة.

### الحصول على استجابة 404 عند تصفح الموقع

إذا كنت تواجه هذه المشكلة ، فأنت بحاجة إلى فهم مواقع الملفات. بشكل افتراضي ، جذر مستندات Apache (Apache document root  هو المكان الذي يبدأ فيه البحث عن محتوى الويب) هو  `/var/www/html/` إذا وضعت الملف `hello.txt` في هذا المجلد، يمكن الوصول إليه بطلب `http://localhost/hello.txt` من المتصفح.

إذا أنشأت مجلد ووضعت الملف فيه - `/var/www/html/mydir/hello.txt` فيمكنك الوصول إلى الملف من الخلال المتصفح بزيارة `http://localhost/mydir/hello.txt`.

يعتبر Linux بشكل افتراضي حساسًا لحالة الأحرف ، وبالتالي في المثال أعلاه ، إذا حاولت التصفح للوصول إلى أي من الروابط التالية، فستحصل على  `404 Not Found`:

- `http://localhost/MyDir/hello.txt`
- `http://localhost/mydir/Hello.txt`
- `http://localhost/MYDIR/hello.txt`

كيف يؤثر ذلك على DVWA؟ يستخدم معظم الأشخاص git للتحقق من DVWA في `/var/www/html` ، وهذا يمنحهم الدليل `/var/www/html/DVWA/` متضمنة جميع ملفات DVWA بداخله. ثم يقومون بطلب الرابط `http://localhost/`من المتصفح ويحصلون على 404 أو الصفحة الافتراضية في Apache. نظرًا لأن الملفات موجودة في مجلد DVWA ، يجب طلب `http://localhost/DVWA`.

الخطأ الشائع الآخر هو طلب الرابط `http://localhost/dvwa`  والذي سيعطي` 404` لأن `dvwa` ليس` DVWA` بسبب حساسية الأحرف في  Linux.

لذلك بعد الإعداد ، إذا حاولت زيارة الموقع والحصول على "404" ، ففكر في المكان الذي قمت بتثبيت الملفات فيه ، وأين ترتبط بالمسار الأساسي ، وما هو اسم المجلد  الذي استخدمته.


### مشكلة "Access denied"

إذا رأيت ما يلي عند تشغيل البرنامج النصي للإعداد setup script ، فهذا يعني أن اسم المستخدم أو كلمة المرور في ملف التكوين لا يتطابقان مع تلك التي تم تكوينها في قاعدة البيانات:

```
Database Error #1045: Access denied for user 'notdvwa'@'localhost' (using password: YES).
```

يخبرك الخطأ أن اسم المستخدم هو `notdvwa`.

يشير الخطأ التالي إلى أنك وجهت ملف التكوين إلى قاعدة البيانات الخاطئة.

```
SQL: Access denied for user 'dvwa'@'localhost' to database 'notdvwa'
```

رسالة الخطأ توضح أنك تستخدم المستخدم `dvwa` وتحاول الاتصال بقاعدة البيانات `notdvwa`.

أول ما يجب القيام به هو التحقق مرة أخرى مما تعتقد أنك قد وضعته في ملف التكوين صحيح ومطابق للبيانات الفعلية.

إذا كان يتطابق مع ما تتوقعه ، فإن الشيء التالي الذي يجب فعله هو التحقق من أنه يمكنك تسجيل الدخول كمستخدم في محرر الأوامر command line. بافتراض أن لديك مستخدم قاعدة بيانات لـ `dvwa` وكلمة مرور هي `p@ssw0rd`، قم بتنفيذ الأمر التالي:

```
mysql -u dvwa -pp@ssw0rd -D dvwa
```

*ملاحظة: لا يوجد مسافة بعد  -p*

إذا ظهر الخرج التالي، فكلمة المرور صحيحة:

```
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 14
Server version: 10.3.22-MariaDB-0ubuntu0.19.10.1 Ubuntu 19.10

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [dvwa]>
```

نظرًا لأنه يمكنك الاتصال في سطر الأوامر ، فمن المحتمل أن يكون هناك خطأ ما في ملف التكوين ، تحقق مرة أخرى من ذلك ثم قم بإنشاء تذكرة للمشكلة إذا كنت لا تزال غير قادر على التشغيل.
إذا رأيت ما يلي ، فإن اسم المستخدم أو كلمة المرور التي تستخدمها غير صحيحة. كرر خطوات [إعداد قاعدة البيانات](#إعداد-قاعدة-البيانات) وتأكد من استخدام اسم المستخدم وكلمة المرور نفسهما طوال العملية.

```
ERROR 1045 (28000): Access denied for user 'dvwa'@'localhost' (using password: YES)
```

إذا حصلت على ما يلي ، فإن بيانات الدخول صحيحة ولكن ليس لدى المستخدم حق الوصول إلى قاعدة البيانات. مرة أخرى ، كرر خطوات الإعداد وتحقق من اسم قاعدة البيانات التي تستخدمها.
```
ERROR 1044 (42000): Access denied for user 'dvwa'@'localhost' to database 'dvwa'
```

الخطأ النهائي  الذي يمكن أن تحصل عليه هو :

```
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
```

هذه ليست مشكلة مصادقة ولكنها تخبرك أن خادم قاعدة البيانات لا يعمل. يمكنك تشغيله بتنفيذ الأمر التالي

```sh
sudo service mysql start
```

### مشكلة Unknown authentication method

مع أحدث إصدارات MySQL ، لم يعد بإمكان PHP الاتصال بقاعدة البيانات في تكوينها الافتراضي. إذا حاولت تشغيل البرنامج النصي للإعداد setup script وتلقيت الرسالة الjالية ، فهذا يعني أنه عليك إجراء بعض التعديلات على التكوين.
```
Database Error #2054: The server requested authentication method unknown to the client.
```

لديك خياران ، أسهلهما هو إلغاء تثبيت MySQL وتثبيت MariaDB. تجد في الرابط التالي الدليل الرسمي لمشروع MariaDB:

<https://mariadb.com/resources/blog/how-to-migrate-from-mysql-to-mariadb-on-linux-in-five-steps/>

بدلاً من ذلك، اتبع الخطوات التالية:

1- باستخدام الحساب root، عدل الملف التالي: `/etc/mysql/mysql.conf.d/mysqld.cnf`.

2- أضف ما يلي تحت لاسطر `[mysqld]`:
  `default-authentication-plugin=mysql_native_password`
  
3- أعد تشغيل خدمة قواعد البيانات: `sudo service mysql restart`.

4- تخقق من طريقة المصادقة الخاصة بحساب قاعدة البيانات:


    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------------------+-----------------------+
    | Host      | User             | plugin                |
    +-----------+------------------+-----------------------+
    | localhost | dvwa             | caching_sha2_password |
    +-----------+------------------+-----------------------+
    1 rows in set (0.00 sec)
    ```


5- من المرجح أنها `caching_sha2_password`، إذا كان كذلك، نفذ ما يلي:

    ```sql
    mysql> ALTER USER dvwa@localhost IDENTIFIED WITH mysql_native_password BY 'p@ssw0rd';
    ```


6- تحقق مجدداً، يجب أن تصبح الآن `mysql_native_password` .

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------+-----------------------+
    | Host      | User | plugin                |
    +-----------+------+-----------------------+
    | localhost | dvwa | mysql_native_password |
    +-----------+------+-----------------------+
    1 row in set (0.00 sec)
    ```

بعد كل ما سبق، يجب أن تعمل عملية الإعدد بحالتها الطبيعية.

إذا كنت تريد المزيد من المعلومات يرجى الاطلاع على:  <https://www.php.net/manual/en/mysqli.requirements.php>.

### مشكلة Database Error #2002: No such file or directory.

إذا كان خادم قاعدة البيانات لا يعمل. وكنت تسخدم توزيعة مبنية على Debian، يمكن القيام بذلك باستخدام:

```sh
sudo service mysql start
```

### معالجة الأخطاء  "MySQL server has gone away" و  "Packets out of order"

هناك عدة أسباب لحدوث هذه الأخطاء ، ولكن السبب المرجح هو عدم توافق إصدار خادم قاعدة البيانات مع إصار PHP.

وهو الأكثر شيوعًا عند تشغيل أحدث إصدار من MySQL و PHP ، لا يعمل التطبيق بشكل جيد. ولذلك ننصح باستبدال MySQL  بـ MariaDB  لأن هذه المشكلة لا يوجد دعم لها في الوقت الحالي.

لمزيد من المعلومات، يرجى الاطلاع على:

<https://www.ryadel.com/en/fix-mysql-server-gone-away-packets-order-similar-mysql-related-errors/>

### لا يعمل حقن SQL باستخدام  PHP v5.2.6

توقف دعم PHP 5.x منذ يناير 2019 ، لذلك نوصي بتشغيل DVWA بإصدار 7.x الحالي ، إذا كنت مضطراً لاستخدام الإصدار 5.x ..

إذا كنت تستخدم إصدار PHP v5.2.6 أو أحدث ، فستحتاج إلى القيام بما يلي حتى يعمل حقن SQL والثغرات الأمنية الأخرى.

استبدل الآتي في ملف `htaccess.`:

```php
<IfModule mod_php5.c>
    php_flag magic_quotes_gpc off
    #php_flag allow_url_fopen on
    #php_flag allow_url_include on
</IfModule>
```

بهذا:

```php
<IfModule mod_php5.c>
    magic_quotes_gpc = Off
    allow_url_fopen = On
    allow_url_include = On
</IfModule>
```

### فشل حقن الأوامر Command Injection
قد لا يكون لدى Apache امتيازات عالية كافية لتنفيذ الأوامر على خادم الويب. إذا كنت تقوم بتشغيل DVWA على نظام Linux ، فتأكد من تسجيل الدخول كمستخدم root. أما في Windows  قم بتسجيل الدخول كـ administrator.

### لماذا لا يمكن الاتصال بقاعدة البيانات في CentOS؟

قد تواجه مشاكل مع SELinux، قم إما بتعطيل SELinux أو تشغيل هذا الأمر للسماح لخادم الويب بالتخاطب مع قاعدة البيانات:

```
setsebool -P httpd_can_network_connect_db 1
```

### لأي مشكلة أخرى

للحصول على أحدث معلومات استكشاف الأخطاء وإصلاحها ، يرجى قراءة كل من التذاكر المفتوحة والمغلقة في  الريبو  git repo:

<https://github.com/digininja/DVWA/issues>

قبل إرسال التذكرة ، يرجى التأكد من تشغيل أحدث إصدار من الكود من الريبو. هذا ليس أحدث إصدار ، هذا هو أحدث كود من الفرع الرئيسي master branch . 

في حالة إنشاء تذكرة ، يرجى تقديم المعلومات التالية على الأقل:

- نظام التشغيل
- آخر 5 أسطر من سجل أخطاء خادم الويب مباشرة بعد حدوث أي خطأ تقوم بالإبلاغ عنه
- إذا كانت المشكلة تتعلق بمصادقة قاعدة البيانات ، فانتقل إلى الخطوات السابقة أعلاه وقم بتصوير كل خطوة. وأرسلها مع لقطة شاشة لقسم ملف التكوين الذي يظهر مستخدم قاعدة البيانات وكلمة المرور.
- وصف كامل للخطأ الذي يحدث ، وما تتوقع حدوثه ، وما حاولت فعله لإصلاحه. "تعطل تسجيل الدخول" لا يكفي بالنسبة لنا لفهم مشكلتك والمساعدة في حلها.


- - -

## حقن SQL في SQLite3 

_ الدعم لهذا الأمر محدود ، قبل طرح مشكلتك، يرجى التأكد من استعدادك للعمل على تصحيح الأخطاء ، ولا تكتب ببساطة "أنه لا يعمل" ._

بشكل افتراضي ، يتم تنفيذ SQLi و Blind SQLi على خادم MariaDB / MySQL المستخدم في الموقع ولكن من الممكن التبديل لإجراء اختبار SQLi  على SQLite3 بدلاً من ذلك.

لن نتطرق إلى كيفية تشغيل SQLite3 مع PHP ، ولكنها من المفترض أن تكون حالة بسيطة وذلك بتثبيت حزمة `php-sqlite3` والتأكد من تفعيلها.

لإجراء هذا التبديل ، قم ببساطة بتعديل ملف التكوين وإضافة أو تعديل هذه الأسطر:

```
$_DVWA["SQLI_DB"] = "sqlite";
$_DVWA["SQLITE_DB"] = "sqli.db";
```

بشكل افتراضي ، يستخدم الملف `database/sqli.db` ، إذا أحدثت خللا فيه، فما عليك سوى نسخ محتويات ملف `database/sqli.db.dist` ولصقها في الملف الذي تعمل عليه.

التحديات هي نفسها تمامًا مثل MySQL ، ولكنك الآن تنفذها في SQLite3  بدلاً من MySQL.

- - -

## روابط

الصفحة الرئيسية للمشروع: <https://github.com/digininja/DVWA>

*تم إنشاؤها بواسطة فريقDVWA *

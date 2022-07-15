# DAMN VULNERABLE WEB APPLICATION

Damn Vulnerable Web Application (DVWA), son derece zafiyetli bir PHP/MySQL web uygulamasıdır. Temel amacı; güvenlik uzmanlarına, yeteneklerini ve araçlarını test etmeleri konusunda yasal bir ortam sunmak, web geliştiricilerinin web uygulamalarının güvenliğini sağlama süreçlerini daha iyi anlamalarına yardımcı olmak, öğrencilere ve eğitmenlere web uygulamalarının güvenliğini öğrenme/öğretme konusunda kontrollü bir sınıf ortamı sunmaktır.

DVWA, **en yaygın web zafiyetlerinden bazılarının** basit bir arayüz üzerinden **farklı zorluk seviyelerinde denenmesini** hedefler. Bu uygulamada, **dokümante edilmiş ve edilmemiş** zafiyetler olduğunu hatırlatmakta fayda var. Mümkün mertebe fazla problemi deneyin ve keşfedin!
- - -

## UYARI!

Damn Vulnerable Web Application epey zafiyetlidir! **Internet üzerinden erişilebilen bir sunucuya veya barındırma hizmeti sağlayıcınızın public_html dizinine yüklemeyin.** Bu durum, sunucunuzu tehlikeye atar. [VirtualBox](https://www.virtualbox.org/) veya [VMware](https://www.vmware.com/) gibi bir ortamda, sanal makinede, NAT ağı modunda kullanmanız önerilir. Sanal makine içinde web sunucusu ve veri tabanı için [XAMPP](https://www.apachefriends.org/) indirip kurabilirsiniz.

### Sorumluluk Reddi

Herhangi bir kişinin bu uygulamayı (DVWA) nasıl kullandığı konusunda sorumluluk kabul etmiyoruz. Uygulamanın amaçlarını açıkça ifade ettik, bu uygulama kötü amaçlarla kullanılmamalıdır. Kullanıcıların, DVWA'yı canlı ortamdaki web sunucularına yüklemelerine engel olmak için uyarılarda bulunduk ve önlemler aldık. Web sunucunuz, bir DVWA kurulumu nedeniyle tehlikeye düştüyse, bu bizim sorumluluğumuz değildir. Uygulamayı yükleyen ve kuran kişi ya da kişilerin sorumluluğudur.

- - -

## Lisans

Bu dosya, Damn Vulnerable Web Application'ın (DVWA) bir parçasıdır.

Damn Vulnerable Web Application (DVWA) bir özgür yazılımdır. Yazılımı; Özgür Yazılım Vakfı
tarafından yayınlanan GNU Genel Kamu Lisansı'nın 3. versiyonu ya da tercihinize göre daha yeni
bir versiyonunda yer alan koşullar altında yeniden dağıtabilir ve/veya değiştirebilirsiniz.

Damn Vulnerable Web Application (DVWA), faydalı olması umuduyla, ancak HERHANGİ BİR GARANTİ OLMADAN,
SATILABİLİRLİK veya BELİRLİ BİR AMACA UYGUNLUK garantisi bile ima edilmeden dağıtılmıştır.
Detaylı bilgi için GNU Genel Kamu Lisansı'nı inceleyiniz.

Damn Vulnerable Web Application (DVWA) ile birlikte, GNU Genel Kamu Lisansı'nın da bir kopyasını
edinmiş olmalısınız. Durum böyle değilse, <https://www.gnu.org/licenses/> sayfasını inceleyiniz.

- - -

## Uluslararasılaştırma

Bu dosya, birden fazla dilde mevcuttur:

- Çince: [简体中文](README.zh.md)
- İngilizce: [English](README.md)

Çeviri katkısında bulunmak istiyorsanız lütfen PR açın. Ancak dikkat edin; bu, dosyayı Google Translate'ten geçirip göndermeniz anlamına gelmemektedir. Bu tür talepler reddedilecektir.

- - -

## İndirme

Her ne kadar DVWA'nın farklı sürümleri de olsa, desteklenen tek sürüm, resmi GitHub repository'sindeki son kaynak kodudur. Dilerseniz, repo'dan klonlayabilir:

```
git clone https://github.com/digininja/DVWA.git
```

ya da [ZIP olarak indirebilirsiniz](https://github.com/digininja/DVWA/archive/master.zip).

- - -

## Kurulum

**Lütfen config/config.inc.php dosyasınızın var olduğundan emin olun. Yalnızca config.inc.php.dist dosyasına sahip olmak yeterli olmayacaktır. Bu dosyayı, ortamınıza uygun şekilde düzenlemeniz ve config.inc.php şeklinde yeniden adlandırmanız gerekecektir. [Windows, dosya uzantılarını gizleyebilir.](https://www.howtogeek.com/205086/beginner-how-to-make-windows-show-file-extensions/)**

### Kurulum Videoları

- [Damn Vulnerable Web Application'ın (DVWA) Windows 10'da kurulumu](https://www.youtube.com/watch?v=cak2lQvBRAo) [12:39 dakika]

### Windows + XAMPP

Eğer bir web sunucusu kurulumunuz yoksa, DVWA'yı kurmanın en kolay yolu [XAMPP](https://www.apachefriends.org/) indirip kurmaktır.

XAMPP; Linux, Solaris, Windows ve Mac OS X için kurulumu oldukça kolay bir Apache ürünüdür. Paketin içeriğinde Apache web sunucusu, MySQL, PHP, Perl, bir FTP sunucusu ve phpMyAdmin yer almaktadır.

XAMPP'ı şu bağlantıdan indirebilirsiniz:
<https://www.apachefriends.org/>

dvwa.zip dosyasını arşivden çıkarın. Çıkarılan dosyaları public html dizininize taşıyın. Sonra tarayıcınızdan `http://127.0.0.1/dvwa/setup.php` adresine gidin.

### Linux Paketleri

Debian tabanlı bir Linux dağıtımı kullanıyorsanız, aşağıdaki paketleri _(ya da eşleniklerini)_ kurmanız gerekmektedir:

`apt-get -y install apache2 mariadb-server php php-mysqli php-gd libapache2-mod-php`

Site, MariaDB yerine MySQL ile çalışacak. Ancak kullanıma hazır geldiği için MariaDB'yi şiddetle tavsiye ediyoruz. MySQL'in doğru çalışması için ise bazı değişiklikler yapmanız gerekiyor.

### Veri Tabanının Hazırlanması

Veri tabanını ayağa kaldırmak için, önce ana menüdeki `Setup DVWA` butonuna, sonra da `Create / Reset Database` butonuna tıklayın. Bu işlem sizin için, içinde bir miktar veri ile birlikte veri tabanını oluşturacak ya da veri tabanınızı sıfırlayacaktır.

Eğer veri tabanını oluşturma sırasında bir hata ile karşılaşırsanız, `./config/config.inc.php` dosyasındaki veri tabanı giriş bilgilerinin doğru olduğundan emin olun. *Bu, sadece bir örnek dosya olan config.inc.php.dist dosyasından farklıdır.*

Değişkenler, varsayılan olarak aşağıdaki gibi ayarlanmıştır:

```php
$_DVWA[ 'db_server'] = '127.0.0.1';
$_DVWA[ 'db_port'] = '3306';
$_DVWA[ 'db_user' ] = 'dvwa';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';
$_DVWA[ 'db_database' ] = 'dvwa';
```

Not: Eğer MySQL yerine MariaDB kullanıyorsanız (MariaDB, Kali'nin varsayılanıdır) veri tabanının root kullanıcısını kullanamazsınız. Yeni bir veri tabanı kullanıcısı oluşturmalısınız. Bunu yapmak için, veri tabanına root olarak bağlanın ve aşağıdaki komutları çalıştırın:

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

### Diğer Yapılandırmalar

İşletim sisteminize ve PHP sürümünüze bağlı olarak, varsayılan yapılandırmayı değiştirmek isteyebilirsiniz. Dosyaların konumu, cihazdan cihaza farklılık gösterecektir.

**Dizin İzinleri**:

* `./hackable/uploads/` - Web servisi tarafından yazılabilir olmalıdır (dosya yüklemeleri için).
* `./external/phpids/0.6/lib/IDS/tmp/phpids_log.txt` - Web servisi tarafından yazılabilir olmalıdır (PHPIDS kullanmak istiyorsanız).

**PHP yapılandırması**:

* `allow_url_include = on` - Remote File Inclusions'a (RFI) izin verir [[allow_url_include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
* `allow_url_fopen = on` - Remote File Inclusions'a (RFI) izin verir [[allow_url_fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]
* `safe_mode = off` - (PHP <= v5.4 için) SQL Injection'a (SQLi) izin verir [[safe_mode](https://secure.php.net/manual/en/features.safe-mode.php)]
* `magic_quotes_gpc = off` - (PHP <= v5.4 için) SQL Injection'a (SQLi) izin verir [[magic_quotes_gpc](https://secure.php.net/manual/en/security.magicquotes.php)]
* `display_errors = off` - (İsteğe bağlı) PHP uyarı mesajlarını gizler [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]

**Dosya: `config/config.inc.php`**:

* `$_DVWA[ 'recaptcha_public_key' ]` & `$_DVWA[ 'recaptcha_private_key' ]` - Bu değerler şuradan oluşturulmalı: https://www.google.com/recaptcha/admin/create

### Varsayılan Giriş Bilgileri

**Varsayılan kullanıcı adı = `admin`**

**Varsayılan parola = `password`**

_...kolaylıkla brute force edilebilir ;)_

Giriş URL'i: http://127.0.0.1/login.php

_Not: DVWA'yı farklı bir dizine kurduysanız, URL değişecektir._

- - -

## Docker Container

- [dockerhub sayfası](https://hub.docker.com/r/vulnerables/web-dvwa/)

`docker run --rm -it -p 80:80 vulnerables/web-dvwa`

Lütfen, önceki MySQL sorunları nedeniyle aufs kullandığınızdan emin olun. Depolama sürücünüzü kontrol etmek için `docker info` çalıştırın. aufs değilse, lütfen değiştirin. Her işletim sistemi için bunu nasıl yapacağınıza dair dokümanlar mevcut. Ancak farklılık gösterdikleri için bu konuya değinmeyeceğiz.

- - -

## Sorun Giderme

Bu öneriler; Debian, Ubuntu ve Kali gibi Debian tabanlı bir dağıtım kullandığınızı varsayar. Diğer dağıtımlar için yine bu adımları takip edin ancak gerekli yerlerde komutları değiştirin.

### Site 404 hatası veriyor
Bu sorunu yaşıyorsanız, dosya konumlarını anlamalısınız. Varsayılan olarak Apache'nin belge kökü (web içeriğini aramaya başladığı konum) `/var/www/html` dizinidir. Bu dizine `hello.txt` dosyası eklerseniz, erişmek için `http://localhost/hello.txt` adresine gitmelisiniz.

Eğer bir dizin oluşturup bu dosyayı o dizin içine eklediyseniz - `/var/www/html/mydir/hello.txt` - o hâlde `http://localhost/mydir/hello.txt` adresine gitmelisiniz.

Linux varsayılan olarak büyük-küçük harfe duyarlıdır. Yani yukarıdaki örneğe bakarak, aşağıdakilerden birine gitmeyi denediyseniz, `404 Not Found` alırsınız:

- `http://localhost/MyDir/hello.txt`
- `http://localhost/mydir/Hello.txt`
- `http://localhost/MYDIR/hello.txt`

Bu DVWA'yı nasıl etkiler? Birçok kişi, DVWA'yı `/var/www/html` dizinine git ile klonlar. Bu da içinde tüm DVWA dosyaları ile birlikte `/var/www/html/DVWA/` dizinini oluşturur. Sonrasında `http://localhost/` adresine gittiklerinde `404` ya da varsayılan Apache hoş geldin sayfasını görürler. Dosyalar DVWA dizini içinde olduğu için, `http://localhost/DVWA` adresine gitmeniz gerekir.

Başka bir sık karşılaşılan hata da, `http://localhost/dvwa` adresini ziyaret edip `404` almaktır. Çünkü Linux için `dvwa` ile `DVWA` farklı şeylerdir.

Kurulum sonrasında siteyi ziyaret etmeyi denediğinizde `404` alıyorsanız, dosyaları nereye koyduğunuzu düşünün. Belge köküne göre tam olarak nerede kaldıklarına ve büyük-küçük harf kullanımına dikkat edin.

### Setup'ı çalıştırırken "Access denied"

Kurulum betiğini çalıştırdığınızda aşağıdaki hatayı alıyorsanız, veri tabanındaki kullanıcı adı ve parola ile yapılandırma dosyanızdakiler uyuşmuyor demektir:

```
Database Error #1045: Access denied for user 'notdvwa'@'localhost' (using password: YES).
```

Hataya göre, `notdvwa` kullanıcısını kullanıyorsunuz.

Aşağıdaki hata, yapılandırma dosyanızda yanlış veri tabanını yazdığınızı gösterir.

```
SQL: Access denied for user 'dvwa'@'localhost' to database 'notdvwa'
```

`dvwa` kullancısı ile `notdvwa` veri tabanına bağlanmaya çalıştığınızı belirtiyor.

Yapılacak ilk şey, veri tabanınızın ismi ile yapılandırma dosyanızda belirttiğiniz ismi karşılaştırmaktır.

Eğer eşleşiyorsa, komut satırından giriş yapıp yapamadığınıza bakın. Veri tabanı kullanıcınızın `dvwa` ve parolasının `p@ssw0rd` olduğunu varsayarsak, aşağıdaki komutu çalıştırın:

```
mysql -u dvwa -pp@ssw0rd -D dvwa
```

*Not: -p'den sonra boşluk yok*

Aşağıdakine benzer bir çıktı görüyorsanız, parola doğrudur:

```
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 14
Server version: 10.3.22-MariaDB-0ubuntu0.19.10.1 Ubuntu 19.10

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [dvwa]>
```

Komut satırından bağlanabildiğinize göre, yüksek ihtimalle yapılandırma dosyanızda bir şeyler yanlış. Tekrar kontrol edin. İşin içinden çıkamazsanız bir issue açın.

Aşağıdaki çıktıyı alıyorsanız, kullanıcı adınız ve/veya parolanız hatalıdır. [Veri Tabanının Hazırlanması](#veri-tabanının-hazırlanması) bölümündeki adımları tekrar edin ve süreç boyunca aynı kullanıcı adı ve parolayı kullandığınızdan emin olun.

```
ERROR 1045 (28000): Access denied for user 'dvwa'@'localhost' (using password: YES)
```

Aşağıdaki çıktıyı alıyorsanız, kullanıcı giriş bilgileri doğrudur ancak kullanıcının veri tabanına erişimi yoktur. Veri tabanı yapılandırma adımlarının tekrar edin ve kullandığınız veri tabanının ismini kontrol edin.

```
ERROR 1044 (42000): Access denied for user 'dvwa'@'localhost' to database 'dvwa'
```

Yaşayabileceğiniz son hata ise şu:

```
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
```

Bu bir kimlik doğrulama sorunu değil. Size, veri tabanı sunucunuzun çalışmadığını gösteriyor. Aşağıdaki komut ile çalıştırın

```sh
sudo service mysql start
```

### Unknown authentication method (Bilinmeyen kimlik doğrulama metodu)

MySQL'in yeni sürümlerinde, PHP varsayılan yapılandırmasıyla veri tabanı ile artık konuşamamaktadır. Kurulum betiğini çalıştırdığınızda aşağıdaki mesajı alıyorsanız, yapılandırmanız var demektir.

```
Database Error #2054: The server requested authentication method unknown to the client.
```

İki seçeneğiniz var. En kolayı, MySQL'i kaldırmak ve MariaDB kurmak. Aşağıda, MariaDB projesinin resmi rehberi yer almakta:

<https://mariadb.com/resources/blog/how-to-migrate-from-mysql-to-mariadb-on-linux-in-five-steps/>

Alternatif olarak şu adımları takip edin:

1. root kullanıcısıyla şu dosyayı düzenleyin: `/etc/mysql/mysql.conf.d/mysqld.cnf`

2. `[mysqld]` satırının altına aşağıdakini ekleyin:

  `default-authentication-plugin=mysql_native_password`

3. Veri tabanını yeniden başlatın: `sudo service mysql restart`
4. Veri tabanı kullanıcınız için kimlik doğrulama yöntemini kontrol edin:

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------------------+-----------------------+
    | Host      | User             | plugin                |
    +-----------+------------------+-----------------------+
    | localhost | dvwa             | caching_sha2_password |
    +-----------+------------------+-----------------------+
    1 rows in set (0.00 sec)
    ```

1. Muhtemelen `caching_sha2_password` ifadesini göreceksiniz. Durum böyleyse, aşağıdaki komutu çalıştırın:

    ```sql
    mysql> ALTER USER dvwa@localhost IDENTIFIED WITH mysql_native_password BY 'p@ssw0rd';
    ```

1. Tekrar kontrol ettiğinizda, `mysql_native_password` görmelisiniz.

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------+-----------------------+
    | Host      | User | plugin                |
    +-----------+------+-----------------------+
    | localhost | dvwa | mysql_native_password |
    +-----------+------+-----------------------+
    1 row in set (0.00 sec)
    ```

Bu adımlardan sonra, kurulum işlemi normal şekilde devam etmelidir.

Daha fazla bilgi için şu sayfayı ziyaret edin: <https://www.php.net/manual/en/mysqli.requirements.php>.

### Database Error #2002: No such file or directory.

Veri tabanı sunucusu çalışmıyor. Debian tabanlı bir dağıtımda şunu yapabilirsiniz:

```sh
sudo service mysql start
```

### "MySQL server has gone away" ve "Packets out of order" hataları

Bu hataları almanız için birkaç sebep vardır. Ancak yüksek ihtimalle veri tabanı sunucunuzun sürümü, PHP sürümünüzle uyumlu değildir.

Bu en çok, MySQL'in en son sürümünü kullandığınızda - PHP ile iyi anlaşamadıkları için - karşınıza çıkar. Tavsiyemiz, MySQL'den kurtulun ve MariaDB kurun çünkü bu bizim destekleyebileceğimiz bir konu değil.

Daha fazla bilgi için şu adresi ziyaret edin:

<https://www.ryadel.com/en/fix-mysql-server-gone-away-packets-order-similar-mysql-related-errors/>

### PHP v5.2.6 sürümünde SQL Injection çalışmıyor.

PHP 5.x Ocak 2019'da yaşam döngüsünü tamamladığı için (end-of-life) DVWA'yı şu anki 7.x sürümüyle çalıştırmanızı öneriyoruz.

PHP v5.2.6 ya da daha yukarısını kullanıyorsanız, SQL injection ve diğer zafiyetlerin çalışması için aşağıdaki adımları tamamlamanız gerekiyor.

`.htaccess` içinde:

Bunu:

```php
<IfModule mod_php5.c>
    php_flag magic_quotes_gpc off
    #php_flag allow_url_fopen on
    #php_flag allow_url_include on
</IfModule>
```

Şununla değiştirin:

```php
<IfModule mod_php5.c>
    magic_quotes_gpc = Off
    allow_url_fopen = On
    allow_url_include = On
</IfModule>
```

### Command Injection çalışmıyor

Apache, web sunucusunda komutları çalıştırmak için yeterli yetkilere sahip olmayabilir. DVWA'yı Linux'ta çalıştırıyorsanız root olarak oturum açtığınızdan emin olun. Windows'ta ise Administrator olarak oturum açın.

### CentOS'ta veri tabanı neden bağlanamıyor?

SELinux ile problem yaşıyor olabilirsiniz. Ya SELinux'u kapatın ya da web sunucusunun veri tabanı ile konuşabilmesi için şu komutu kullanın:

```
setsebool -P httpd_can_network_connect_db 1
```

### Kalan her şey

En son sorun giderme kılavuzu için lütfen git repo'sundaki açık ve kapalı taleplerin tamamını okuyun:

<https://github.com/digininja/DVWA/issues>

Bir talep göndermeden önce, repo'daki son kod sürümünü kullandığınızdan emin olun. Son "release" sürümünü değil, master dalındaki son kodları kastediyoruz.

Eğer bir talep açacaksanız, en azından aşağıdaki bilgileri iletin:

- İşletim sistemi
- Raporladığınız hatalar gerçekleştiği anda web sunucunuzun hata log'larına düşen son 5 satır
- Eğer bir veri tabanı kimlik doğrulama sorunu yaşıyorsanız, yukarıdaki adımların her birini tekrar edin ve her adımda ekran görüntüsü alın. Bunları, yapılandırma dosyanızdaki veri tabanı kullanıcı adını ve parolasını gösteren kısmın ekran görüntüsü ile birlikte gönderin.
- Yanlış giden şeyin tam açıklaması, ne olmasını beklediğiniz ve bunu düzeltmek için neler yaptığınız... "login çalışmıyor", sorununuzu anlayıp düzeltmemiz için yeterli değil.

- - -

## SQLite3 SQL Injection

_Bu konudaki destek sınırlıdır. Issue açmadan önce, lütfen hata ayıklama sürecinde çalışmaya hazır olduğunuzdan emin olun. "Çalışmıyor" demeyin._

Varsayılan olarak; SQLi ve Blind SQLi, sitede kullanılan MariaDB/MySQL servisine yapılır. Ancak SQLi testlerini SQLite3'e çevirmek de mümkündür.

SQLite3'ün PHP ile nasıl çalışacağını anlatmayacağım. Ancak `php-sqlite3` paketini kurmak ve bunun aktif olduğundan emin olmak işi çözebilir.

Değiştirmek için, yapılandırma dosyanızı düzenleyin ve şu satırları ekleyin/düzenleyin:

```
$_DVWA["SQLI_DB"] = "sqlite";
$_DVWA["SQLITE_DB"] = "sqli.db";
```

Varsayılan olarak `database/sqli.db` dosyasını kullanır. Bu dosyayı batırırsanız, `database/sqli.db.dist` dosyasını bunun üzerine kopyalayın.

Olay MySQL ile aynı. Sadece SQLite3'e karşı yapılacak.

- - -

## Bağlantılar

Proje Sayfası: <https://github.com/digininja/DVWA>

*DVWA takımı tarafından oluşturulmuştur*

## Çeviri

Ali Sezişli: [alisezisli](https://github.com/alisezisli)

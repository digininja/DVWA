# DAMN VULNERABLE WEB APPLICATION / APLIKASI WEB YANG RENTAN TERHADAP ANCAMAN

Damn Vulnerable Web Application (DVWA) atau yang dapat diartikan sebagai Aplikasi Web Yang Rentan Terhadap Ancaman merupakan aplikasi web berbasis PHP/MySQL yang sangat rentan. 
Tujuan utamanya adalah menjadi alat bantu bagi para profesional keamanan untuk menguji keterampilan dan alat mereka dalam lingkungan hukum. 
DVWA juga bertujuan membantu pengembang web memahami proses pengamanan aplikasi web serta memberikan dukungan bagi siswa dan guru untuk mempelajari keamanan aplikasi web dalam lingkungan kelas yang terkendali.

Tujuan dari DVWA adalah **melatih pengguna dalam menghadapi beberapa kerentanan web paling umum** dengan **berbagai tingkat kesulitan**, yang disajikan melalui antarmuka yang sederhana dan langsung. Harap dicatat bahwa **ada kerentanan yang didokumentasikan dan tidak didokumentasikan** dalam perangkat lunak ini. Hal ini sengaja dilakukan untuk mendorong pengguna mencoba dan menemukan sebanyak mungkin masalah.

## PERINGATAN

Damn Vulnerable Web Application sangat rentan! **Jangan mengunggahnya ke folder html publik penyedia hosting Anda atau server yang terhubung langsung ke internet** karena dapat mengakibatkan kompromi keamanan. 
Disarankan untuk menggunakan mesin virtual (seperti [VirtualBox](https://www.virtualbox.org/) atau [VMware](https://www.vmware.com/)), yang diatur dalam mode jaringan NAT. 
Di dalam mesin virtual, Anda dapat mengunduh dan menginstal [XAMPP](https://www.apachefriends.org/) untuk web server dan database.

### Penyangkalan

Kami tidak bertanggung jawab atas cara penggunaan aplikasi ini (DVWA) oleh siapa pun. 
Tujuan dari aplikasi ini telah kami jelaskan dan seharusnya tidak digunakan dengan niat jahat. 
Kami telah memberikan peringatan dan mengambil langkah-langkah untuk mencegah pengguna menginstal DVWA di server web aktif secara langsung. 
Jika server web Anda terpengaruh melalui instalasi DVWA, itu bukan tanggung jawab kami melainkan tanggung jawab orang/orang yang mengunggah dan menginstalnya.

- - -

## Lisensi

Berkas ini merupakan bagian dari Damn Vulnerable Web Application (DVWA).

Damn Vulnerable Web Application (DVWA) adalah perangkat lunak bebas: Anda dapat mendistribusikannya dan/atau mengubahnya
sesuai dengan ketentuan Lisensi Umum GNU yang diterbitkan oleh
Free Software Foundation, versi 3 Lisensi, atau
(pilihan Anda) versi selanjutnya.

Damn Vulnerable Web Application (DVWA) didistribusikan dengan harapan akan bermanfaat,
tetapi TANPA GARANSI APA PUN; tanpa garansi tersirat pun
DAGANG atau SESUAI UNTUK TUJUAN TERTENTU. Lihat
Lisensi Umum GNU untuk lebih banyak detail.

Anda seharusnya telah menerima salinan Lisensi Umum GNU bersama dengan Damn Vulnerable Web Application (DVWA). Jika tidak, lihat <https://www.gnu.org/licenses/>.

- - -

## Internasionalisasi

Berkas ini tersedia dalam beberapa bahasa:
- Arab: [العربية](README.ar.md)
- Tiongkok: [简体中文](README.zh.md)
- Perancis: [Français](README.fr.md)
- Persia: [فارسی](README.fa.md)
- Portugis: [Português](README.pt.md)
- Spanyol: [Español](README.es.md)
- Turki: [Türkçe](README.tr.md)
- Indonesia: [id](README.id.md)

Jika Anda ingin berkontribusi dengan terjemahan, silakan kirimkan PR (Permintaan Tarik). 
Namun perlu diperhatikan, ini bukan berarti hanya menjalankannya melalui Google Translate dan mengirimkannya, karena itu akan ditolak. 
Kirimkan versi terjemahan Anda dengan menambahkan file baru 'README.xx.md' di mana xx adalah kode dua huruf dari bahasa yang Anda inginkan (berdasarkan [ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes)).

- - -

## Unduh

Meskipun terdapat berbagai versi DVWA, satu-satunya versi yang didukung adalah sumber terbaru dari repositori resmi GitHub. 
Anda dapat mengklonnya dari repositori: 
`git clone https://github.com/digininja/DVWA.git`

Atau [unduh arsip ZIP dari file-file](https://github.com/digininja/DVWA/archive/master.zip).

- - -

## Instalasi

### Video Instalasi

- [Instalasi DVWA di Kali yang berjalan di VirtualBox](https://www.youtube.com/watch?v=WkyDxNJkgQ4)
- [Instalasi DVWA di Windows menggunakan XAMPP](https://youtu.be/Yzksa_WjnY0)
- [Instalasi Damn Vulnerable Web Application (DVWA) di Windows 10](https://www.youtube.com/watch?v=cak2lQvBRAo)

### Windows + XAMPP

Cara paling mudah untuk menginstal DVWA adalah dengan mengunduh dan menginstal [XAMPP](https://www.apachefriends.org/) jika Anda belum memiliki pengaturan server web.
XAMPP adalah Distribusi Apache yang sangat mudah diinstal untuk Linux, Solaris, Windows, dan Mac OS X. Paket ini mencakup server web Apache, MySQL, PHP, Perl, server FTP, dan phpMyAdmin.
[Video ini](https://youtu.be/Yzksa_WjnY0) memandu Anda melalui proses instalasi untuk Windows, tetapi seharusnya serupa untuk OS lainnya.

### Docker

Terima kasih kepada [hoang-himself](https://github.com/hoang-himself) dan [JGillam](https://github.com/JGillam), setiap commit ke cabang `master` menyebabkan pembangunan Docker image dan siap untuk diunduh dari GitHub Container Registry.

Untuk informasi lebih lanjut tentang apa yang Anda dapatkan, Anda dapat menjelajahi [Docker images yang sudah dibangun sebelumnya](https://github.com/digininja/DVWA/pkgs/container/dvwa).

#### Memulai

Prasyarat: Docker dan Docker Compose.

- Jika Anda menggunakan Docker Desktop, keduanya seharusnya sudah terinstal.
- Jika Anda lebih memilih Docker Engine di Linux, pastikan untuk mengikuti [panduan instalasi mereka](https://docs.docker.com/engine/install/#server).

**Kami memberikan dukungan untuk rilis Docker terbaru seperti yang ditunjukkan di atas.**
Jika Anda menggunakan Linux dan paket Docker yang disertakan dengan pengelola paket Anda, kemungkinan besar juga akan berfungsi, tetapi dukungan akan berusaha sebaik mungkin.

Memperbarui Docker dari versi paket manajer ke upstream memerlukan penghapusan versi lama seperti yang terlihat dalam panduan mereka untuk [Ubuntu](https://docs.docker.com/engine/install/ubuntu/#uninstall-old-versions), [Fedora](https://docs.docker.com/engine/install/fedora/#uninstall-old-versions), dan lainnya.
Data Docker Anda (container, gambar, volume, dll.) seharusnya tidak terpengaruh, tetapi jika Anda mengalami masalah, pastikan untuk [memberi tahu Docker](https://www.docker.com/support) dan gunakan mesin pencari dalam waktu yang bersamaan.

Kemudian, untuk memulai:

1. Jalankan `docker version` dan `docker compose version` untuk melihat apakah Docker dan Docker Compose terinstal dengan benar. Anda seharusnya dapat melihat versi mereka dalam output.

    Contoh:

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

    Jika Anda tidak melihat apa-apa atau mendapatkan pesan kesalahan "command not found" ikuti prasyarat untuk menyiapkan Docker dan Docker Compose.

2. Klon atau unduh repositori ini dan ekstrak (lihat [Unduh](#unduh)).
3. Buka terminal pilihan Anda dan ubah direktori kerjanya ke dalam folder ini (`DVWA`).
4. Jalankan `docker compose up -d`.

DVWA sekarang tersedia di `http://localhost:4280`.

**Perhatikan bahwa untuk menjalankan DVWA dalam kontainer, server web mendengarkan port 4280 daripada port biasa 80.**
Untuk informasi lebih lanjut mengenai keputusan ini, lihat [Saya ingin menjalankan DVWA di port yang berbeda](#i-want-to-run-dvwa-on-a-different-port).

#### Pembangunan Lokal

Jika Anda melakukan perubahan lokal dan ingin membangun proyek dari lokal, buka `compose.yml` dan ubah `pull_policy: always` menjadi `pull_policy: build`.

Menjalankan `docker compose up -d` seharusnya akan memicu Docker untuk membangun gambar dari lokal tanpa memperdulikan apa yang tersedia di registri.

Lihat juga: [`pull_policy`](https://github.com/compose-spec/compose-spec/blob/master/05-services.md#pull_policy).

### Paket-paket Linux

Jika Anda menggunakan distribusi Linux berbasis Debian, Anda perlu menginstal paket-paket berikut _(atau yang setara)_:

- apache2
- libapache2-mod-php`
- mariadb-server
- mariadb-client
- php php-mysqli
- php-gd

Saya sarankan melakukan pembaruan sebelumnya, agar Anda memastikan mendapatkan versi terbaru dari semuanya.
```
apt update
apt install -y apache2 mariadb-server mariadb-client php php-mysqli php-gd libapache2-mod-php
```
Situs akan berfungsi dengan MySQL alih-alih MariaDB, tetapi kami sangat menyarankan MariaDB karena berfungsi tanpa masalah sedangkan Anda harus melakukan perubahan agar MySQL dapat berfungsi dengan benar.

## Konfigurasi

### Berkas Konfigurasi

DVWA disertakan dengan salinan palsu dari berkas konfigurasinya yang perlu Anda salin dan lakukan perubahan yang sesuai. Pada Linux, bila Anda berada di direktori DVWA, langkah ini dapat dilakukan seperti berikut:

`cp config/config.inc.php.dist config/config.inc.php`

Pada Windows, langkah ini mungkin sedikit lebih sulit jika ekstensi file disembunyikan. Jika Anda tidak yakin mengenai hal ini, blog post berikut menjelaskan lebih lanjut:

[Cara Membuat Windows Menampilkan Ekstensi File](https://www.howtogeek.com/205086/beginner-how-to-make-windows-show-file-extensions/)

### Pengaturan Database

Untuk mengatur database, cukup klik tombol `Setup DVWA` di menu utama, kemudian klik tombol `Create / Reset Database`. Ini akan membuat/mereset database untuk Anda dengan beberapa data di dalamnya.

Jika Anda menerima pesan kesalahan saat mencoba membuat database Anda, pastikan kredensial database Anda benar dalam `./config/config.inc.php`. *Ini berbeda dari config.inc.php.dist, yang merupakan berkas contoh.*

Variabel-variabelnya diatur secara default sebagai berikut:
```
$_DVWA['db_server'] = '127.0.0.1';
$_DVWA['db_port'] = '3306';
$_DVWA['db_user'] = 'dvwa';
$_DVWA['db_password'] = 'p@ssw0rd';
$_DVWA['db_database'] = 'dvwa';
```

Perhatikan, jika Anda menggunakan MariaDB daripada MySQL (MariaDB adalah default di Kali), maka Anda tidak dapat menggunakan pengguna root database, Anda harus membuat pengguna database baru. Untuk melakukannya, sambungkan ke database sebagai pengguna root kemudian gunakan perintah-perintah berikut:
```
mysql> create database dvwa;
Query OK, 1 row affected (0.00 sec)

mysql> create user dvwa@localhost identified by 'p@ssw0rd';
Query OK, 0 rows affected (0.01 sec)

mysql> grant all on dvwa.* to dvwa@localhost;
Query OK, 0 rows affected (0.01 sec)

mysql> flush privileges;
Query OK, 0 rows affected (0.00 sec)
```

### Menonaktifkan Otentikasi

Beberapa alat tidak berfungsi dengan baik dengan otentikasi sehingga tidak dapat digunakan dengan DVWA. Untuk mengatasi ini, ada opsi konfigurasi untuk menonaktifkan pemeriksaan otentikasi. Untuk melakukannya, cukup atur yang berikut dalam berkas konfigurasi:

```
$_DVWA['disable_authentication'] = true;
```

Anda juga perlu mengatur tingkat keamanan ke tingkat yang sesuai dengan pengujian yang ingin Anda lakukan:
```
$_DVWA['default_security_level'] = 'low';
```

Dalam kondisi ini, Anda dapat mengakses semua fitur tanpa perlu masuk dan mengatur cookie apapun.

### Izin Folder

* `./hackable/uploads/` - Perlu dapat ditulisi oleh layanan web (untuk Unggahan File).

### Konfigurasi PHP

Pada sistem Linux, kemungkinan ditemukan di `/etc/php/x.x/fpm/php.ini` atau `/etc/php/x.x/apache2/php.ini`.

* Untuk mengizinkan Remote File Inclusions (RFI):
    * `allow_url_include = on` [[allow_url_include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
    * `allow_url_fopen = on` [[allow_url_fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]

* Untuk memastikan PHP menampilkan semua pesan kesalahan:
    * `display_errors = on` [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]
    * `display_startup_errors = on` [[display_startup_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-startup-errors)]

Pastikan Anda me-restart layanan php atau Apache setelah melakukan perubahan.

### reCAPTCHA

Ini hanya diperlukan untuk lab "Insecure CAPTCHA", jika Anda tidak bermain dengan lab tersebut, Anda dapat mengabaikan bagian ini.

Buat sepasang kunci API dari <https://www.google.com/recaptcha/admin/create>.

Kemudian masukkan kunci-kunci tersebut ke bagian-bagian berikut di dalam `./config/config.inc.php`:

* `$_DVWA['recaptcha_public_key']`
* `$_DVWA['recaptcha_private_key']`

### Kredensial Default

**Username default = `admin`**

**Password default = `password`**

_...dapat dengan mudah di-brute force ;)_

URL Login: http://127.0.0.1/login.php

_Catatan: Ini akan berbeda jika Anda menginstal DVWA ke direktori yang berbeda._

## Pemecahan Masalah

Asumsi ini berlaku jika Anda menggunakan distribusi berbasis Debian, seperti Debian, Ubuntu, dan Kali. Untuk distribusi lainnya, ikuti langkah-langkah ini, tetapi perbarui perintah sesuai kebutuhan.

### Kontainer

#### Saya ingin mengakses log

Jika Anda menggunakan Docker Desktop, log dapat diakses dari aplikasi grafis.
Beberapa detail kecil mungkin berubah dengan versi terbaru, tetapi metode akses seharusnya tetap sama.

![Overview of DVWA compose](./docs/graphics/docker/overview.png)

![Viewing DVWA logs](docs/graphics/docker/detail.png)

Log juga dapat diakses dari terminal.

1. Buka terminal dan ubah direktori kerjanya ke DVWA.
2. Tampilkan log yang telah digabungkan.

    ```
    docker compose logs
    ```

   Jika Anda ingin mengekspor log ke file, misalnya `dvwa.log`

   ```
   docker compose logs >dvwa.log
   ```

#### Saya ingin menjalankan DVWA di port yang berbeda

Kami tidak menggunakan port 80 secara default karena beberapa alasan:

- Beberapa pengguna mungkin sudah menjalankan sesuatu di port 80.
- Beberapa pengguna mungkin menggunakan mesin kontainer tanpa hak istimewa (seperti Podman), dan 80 adalah port yang memerlukan hak istimewa (< 1024). Konfigurasi tambahan (misalnya, pengaturan `net.ipv4.ip_unprivileged_port_start`) diperlukan, tetapi Anda harus melakukan penelitian sendiri.

Anda dapat mengekspos DVWA di port yang berbeda dengan mengubah ikatan port dalam berkas `compose.yml`.
Sebagai contoh, Anda dapat mengubah

```
ports:
  - 127.0.0.1:4280:80
```

Menjadi

```
ports:
  - 127.0.0.1:8806:80
```

DVWA sekarang dapat diakses di `http://localhost:8806`.

#### DVWA Mulai Otomatis Saat Docker Berjalan

Berkas [`compose.yml`](./compose.yml) yang disertakan secara otomatis menjalankan DVWA dan basis data ketika Docker berjalan.

Untuk menonaktifkan ini, Anda dapat menghapus atau mengomentari baris `restart: unless-stopped` dalam berkas [`compose.yml`](./compose.yml).

Jika Anda ingin menonaktifkan perilaku ini secara sementara, Anda dapat menjalankan `docker compose stop`, atau menggunakan Docker Desktop, temukan `dvwa` dan klik Stop.
Selain itu, Anda dapat menghapus kontainer atau menjalankan `docker compose down`.

### Berkas Log

Pada sistem Linux, Apache secara default menghasilkan dua berkas log, `access.log` dan `error.log`, dan pada sistem berbasis Debian biasanya berada di `/var/log/apache2/`.

Ketika mengirimkan laporan kesalahan, masalah, atau hal lainnya, harap sertakan setidaknya lima baris terakhir dari masing-masing berkas ini. Pada sistem berbasis Debian, Anda dapat mendapatkannya seperti ini:
```
tail -n 5 /var/log/apache2/access.log /var/log/apache2/error.log
```

### Saya mencoba membuka situs dan mendapatkan 404

Jika Anda mengalami masalah ini, Anda perlu memahami lokasi berkas. Secara default, root dokumen Apache (tempat mulai mencari konten web) adalah `/var/www/html`. Jika Anda meletakkan berkas `hello.txt` di direktori ini, untuk mengaksesnya, Anda akan membuka `http://localhost/hello.txt`.

Jika Anda membuat direktori dan meletakkan berkas di dalamnya - `/var/www/html/mydir/hello.txt` - Anda kemudian perlu membuka `http://localhost/mydir/hello.txt`.

Linux secara default bersifat case-sensitive, sehingga dalam contoh di atas, jika Anda mencoba membuka salah satu dari ini, Anda akan mendapatkan `404 Not Found`:

- `http://localhost/MyDir/hello.txt`
- `http://localhost/mydir/Hello.txt`
- `http://localhost/MYDIR/hello.txt`

Bagaimana ini memengaruhi DVWA? Kebanyakan orang menggunakan git untuk mengecek DVWA ke dalam `/var/www/html`, ini memberi mereka direktori `/var/www/html/DVWA/` dengan semua berkas DVWA di dalamnya. Mereka kemudian membuka `http://localhost/` dan mendapatkan entah `404` atau halaman selamat datang Apache default. Karena berkas berada di DVWA, Anda harus membuka `http://localhost/DVWA`.

Kesalahan umum lainnya adalah membuka `http://localhost/dvwa` yang akan memberikan `404` karena `dvwa` bukanlah `DVWA` yang dianggap oleh pencocokan direktori Linux.

Jadi setelah instalasi, jika Anda mencoba mengunjungi situs dan mendapatkan `404`, pertimbangkan di mana Anda menginstal berkas tersebut, di mana berkas tersebut relatif terhadap root dokumen, dan apa huruf kecil dan besar dari direktori yang Anda gunakan.

### "Access denied" saat menjalankan setup

Jika Anda melihat pesan berikut saat menjalankan skrip setup, itu berarti nama pengguna atau kata sandi dalam berkas konfigurasi tidak sesuai dengan yang dikonfigurasi pada basis data:
```
Database Error #1045: Access denied for user 'notdvwa'@'localhost' (using password: YES).
```

Error ini memberi tahu Anda bahwa Anda menggunakan nama pengguna `notdvwa`.

Error berikut mengatakan bahwa Anda telah menunjuk berkas konfigurasi ke basis data yang salah.

```
SQL: Access denied for user 'dvwa'@'localhost' to database 'notdvwa'
```

Ini mengatakan bahwa Anda menggunakan pengguna `dvwa` dan mencoba terhubung ke basis data `notdvwa`.

Hal pertama yang harus dilakukan adalah memeriksa kembali apakah yang Anda kira telah dimasukkan ke dalam berkas konfigurasi sesuai dengan yang sebenarnya ada di sana.

Jika sesuai dengan harapan Anda, langkah berikutnya adalah memeriksa apakah Anda dapat masuk sebagai pengguna tersebut melalui baris perintah. Mengasumsikan Anda memiliki pengguna basis data `dvwa` dan kata sandi `p@ssw0rd`, jalankan perintah berikut:

```
mysql -u dvwa -pp@ssw0rd -D dvwa
```

*Catatan: Tidak ada spasi setelah -p*

Jika Anda melihat yang berikut, kata sandi sudah benar:

```
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 14
Server version: 10.3.22-MariaDB-0ubuntu0.19.10.1 Ubuntu 19.10

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [dvwa]>
```

Seiring terhubung melalui baris perintah, kemungkinan ada kesalahan dalam berkas konfigurasi. Periksa kembali dan laporkan masalah jika Anda masih belum bisa membuat semuanya berfungsi.

Jika Anda melihat yang berikut, nama pengguna atau kata sandi yang Anda gunakan salah. Ulangi langkah-langkah [Database Setup](#database-setup) dan pastikan Anda menggunakan nama pengguna dan kata sandi yang sama sepanjang proses.

```
ERROR 1045 (28000): Access denied for user 'dvwa'@'localhost' (using password: YES)
```

Jika Anda mendapatkan yang berikut, kredensial pengguna benar tetapi pengguna tidak memiliki akses ke basis data. Sekali lagi, ulangi langkah-langkah setup dan periksa nama basis data yang Anda gunakan.
```
ERROR 1044 (42000): Access denied for user 'dvwa'@'localhost' to database 'dvwa'
```

Kesalahan yang mungkin Anda dapatkan adalah ini:
```
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
```

Ini bukan masalah otentikasi tetapi memberi tahu Anda bahwa server basis data tidak berjalan. Mulailah dengan perintah berikut:
```
sudo service mysql start
```

### Metode otentikasi tidak dikenal

Dengan versi MySQL terbaru, PHP tidak lagi dapat berkomunikasi dengan basis data dalam konfigurasi default. Jika Anda mencoba menjalankan skrip setup dan mendapatkan pesan berikut, itu berarti Anda memiliki konfigurasi.

```
Database Error #2054: The server requested authentication method unknown to the client.
```

Anda memiliki dua pilihan, yang paling mudah adalah menghapus MySQL dan menginstal MariaDB. Berikut adalah panduan resmi dari proyek MariaDB: 
<https://mariadb.com/resources/blog/how-to-migrate-from-mysql-to-mariadb-on-linux-in-five-steps/>

Sebagai alternatif, ikuti langkah-langkah berikut:

1. Sebagai root, edit berkas berikut: `/etc/mysql/mysql.conf.d/mysqld.cnf`

2. Di bawah baris [mysqld], tambahkan baris berikut:
   ```default-authentication-plugin=mysql_native_password```
3. Restart database: ```sudo service mysql restart```
4. Periksa metode autentikasi untuk pengguna database Anda:
    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------------------+-----------------------+
    | Host      | User             | plugin                |
    +-----------+------------------+-----------------------+
    | localhost | dvwa             | caching_sha2_password |
    +-----------+------------------+-----------------------+
    1 rows in set (0.00 sec)
    ```
5. Kemungkinan besar Anda akan melihat `caching_sha2_password`. Jika ya, jalankan perintah berikut:
   ```sql
    mysql> ALTER USER dvwa@localhost IDENTIFIED WITH mysql_native_password BY 'p@ssw0rd';
    ```
6. Menjalankan pemeriksaan ulang, seharusnya sekarang Anda akan melihat `mysql_native_password`.
     ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------+-----------------------+
    | Host      | User | plugin                |
    +-----------+------+-----------------------+
    | localhost | dvwa | mysql_native_password |
    +-----------+------+-----------------------+
    1 row in set (0.00 sec)
    ```
Setelah semua langkah tersebut, proses penyiapan seharusnya sekarang berjalan dengan normal.
Jika Anda ingin informasi lebih lanjut, lihat halaman berikut: <https://www.php.net/manual/en/mysqli.requirements.php>.

### Database Error #2002: Tidak ada file atau direktori yang sesuai.

Server database tidak berjalan. Pada distribusi berbasis Debian, ini dapat dilakukan dengan perintah:
```sh
sudo service mysql start
```

### Errors "MySQL server has gone away" and "Packets out of order"

Ada beberapa alasan mengapa Anda bisa mendapatkan kesalahan ini, tetapi yang paling mungkin adalah versi server basis data yang Anda jalankan tidak kompatibel dengan versi PHP.

Ini biasanya terjadi ketika Anda menjalankan versi terbaru MySQL yang tidak selaras dengan PHP. Saran terbaik, ganti MySQL dengan menginstal MariaDB, karena ini bukan sesuatu yang dapat kami dukung.

Untuk informasi lebih lanjut, lihat:

<https://www.ryadel.com/en/fix-mysql-server-gone-away-packets-order-similar-mysql-related-errors/>

### Perintah Injection tidak bekerja

Apache mungkin tidak memiliki hak istimewa yang cukup untuk menjalankan perintah pada server web. Jika Anda menjalankan DVWA di bawah Linux, pastikan Anda masuk sebagai root. Di bawah Windows, masuk sebagai Administrator.

### Database tidak terhubung pada CentOS?

Anda mungkin mengalami masalah dengan SELinux. Matikan SELinux atau jalankan perintah berikut untuk mengizinkan web server berkomunikasi dengan basis data:
```
setsebool -P httpd_can_network_connect_db 1
```

### Yang lainnya

Untuk informasi pemecahan masalah terbaru, harap baca masalah terbuka dan yang sudah ditutup di repositori Git:

<https://github.com/digininja/DVWA/issues>

Sebelum mengajukan tiket, pastikan Anda menjalankan versi terbaru kode dari repositori. Ini bukan versi terbaru yang dirilis, tetapi kode terbaru dari cabang master.

Jika Anda mengajukan tiket, harap kirimkan setidaknya informasi berikut:

- Sistem Operasi
- 5 baris terakhir dari log kesalahan server web segera setelah kesalahan yang Anda laporkan terjadi
- Jika ini adalah masalah otentikasi basis data, ikuti langkah-langkah di atas dan tangkap layar setiap langkah. Kirimkan ini bersama dengan tangkapan layar bagian file konfigurasi yang menunjukkan pengguna dan kata sandi basis data.
- Deskripsi lengkap tentang apa yang salah, apa yang Anda harapkan terjadi, dan apa yang sudah Anda coba lakukan untuk memperbaikinya. "login broken" tidak cukup bagi kami untuk memahami masalah Anda dan membantu memperbaikinya.

- - -

### Panduan

Saya akan mencoba membuat beberapa video tutorial yang menguraikan beberapa kerentanan dan menunjukkan cara mendeteksinya, dan kemudian bagaimana cara mengeksploitasi mereka. Berikut adalah yang sudah saya buat sejauh ini:

[Finding and Exploiting Reflected XSS](https://youtu.be/V4MATqtdxss)

- - -

## SQLite3 SQL Injection

Dukungan untuk ini terbatas, sebelum mengajukan masalah, pastikan Anda siap untuk melakukan debug, jangan hanya mengklaim "tidak berfungsi".

Secara default, SQLi dan Blind SQLi dilakukan terhadap server MariaDB/MySQL yang digunakan oleh situs, tetapi memungkinkan untuk beralih untuk melakukan pengujian SQLi terhadap SQLite3.

Saya tidak akan membahas cara menggunakan SQLite3 dengan PHP, tetapi seharusnya cukup mudah dengan menginstal paket `php-sqlite3` dan memastikan bahwa paket tersebut diaktifkan.

Untuk beralih, cukup edit file konfigurasi dan tambahkan atau edit baris-baris berikut:
```
$_DVWA["SQLI_DB"] = "sqlite";
$_DVWA["SQLITE_DB"] = "sqli.db";
```

Secara default, program ini menggunakan file `database/sqli.db`. Jika Anda mengalami kesalahan, cukup salin file `database/sqli.db.dist` di atasnya.

Tantangannya sama persis seperti untuk MySQL, hanya saja dijalankan dengan menggunakan SQLite3.

- - -

👨‍💻 Kontributor
-----

Terima kasih atas semua kontribusi Anda dan menjaga proyek ini tetap terkini. :heart:

Jika Anda memiliki ide, jenis perbaikan, atau hanya ingin berkolaborasi, Anda dipersilakan untuk berkontribusi dan berpartisipasi dalam Proyek ini. Jangan ragu untuk mengirimkan permintaan tautan (PR) Anda.
<p align="center">
<a href="https://github.com/digininja/DVWA/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=digininja/DVWA&max=500">
</a>
</p>

- - -

## Tautan

Beranda Proyek: <https://github.com/digininja/DVWA>

*Dibuat oleh tim DVWA*

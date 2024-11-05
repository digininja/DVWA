# DAMN VULNERABLE WEB APPLICATION - ỨNG DỤNG WEB DỄ BỊ TẤN CÔNG

Damn Vulnerable Web Application (DVWA) là một ứng dụng web PHP/MySQL cực kỳ dễ bị tấn công. Mục tiêu chính của ứng dụng này là hỗ trợ các chuyên gia bảo mật kiểm tra kỹ năng và công cụ của họ trong môi trường pháp lý, giúp các web dev hiểu rõ hơn về quy trình bảo mật ứng dụng web và hỗ trợ cả học sinh/sinh viên và giáo viên tìm hiểu về bảo mật ứng dụng web trong một môi trường được kiểm soát.

Mục đích của DVWA là **thực hành với một số lỗ hổng web phổ biến nhất**, với **mức độ khó khác nhau** và giao diện đơn giản, dễ hiểu.
Xin lưu ý, có **cả lỗ hổng được ghi lại và không** với phần mềm này. Đây là có chủ đích. Bạn nên thử và khám phá càng nhiều vấn đề càng tốt.

- - -

## Cảnh báo!

Damn Vulnerable Web Application dễ bị tấn công! **Không tải nó lên folder public của nhà cung cấp dịch vụ lưu trữ của bạn hoặc bất kỳ máy chủ nào có kết nối Internet**, vì chúng sẽ bị xâm phạm. Bạn nên sử dụng máy ảo (vd như [VirtualBox](https://www.virtualbox.org/) hoặc [VMware](https://www.vmware.com/)), để sử dụng chế độ NAT networking. Trên máy khác, bạn tải và cài đặt [XAMPP](https://www.apachefriends.org/) cho web server và database.

### Tuyên bố miễn trừ trách nhiệm

Chúng tôi không chịu trách nhiệm về cách thức mà bất kỳ ai sử dụng ứng dụng này (DVWA). Chúng tôi đã nêu rõ mục đích của ứng dụng và không nên sử dụng ứng dụng này cho mục đích xấu. Chúng tôi đã đưa ra cảnh báo và thực hiện các biện pháp để ngăn người dùng cài đặt DVWA trên máy chủ web thực tế. Nếu máy chủ web của bạn bị xâm phạm thông qua cài đặt DVWA, đó không phải là trách nhiệm của chúng tôi, mà đó là trách nhiệm của những người đã tải lên và cài đặt.

- - -

## Giấy phép

File này là một phần của Damn Vulnerable Web Application (DVWA).

Damn Vulnerable Web Application (DVWA) là phần mềm miễn phí: bạn có thể phân phối lại và/hoặc sửa đổi nó
nó theo các điều khoản của Giấy phép GNU General Public được xuất bản bởi
Tổ chức Phần mềm Tự do, phiên bản 3 của Giấy phép, hoặc
(theo lựa chọn của bạn) bất kỳ phiên bản mới hơn.

Damn Vulnerable Web Application (DVWA) được phân phối với hy vọng là nó sẽ hữu ích,
nhưng KHÔNG CÓ BẤT KỲ SỰ ĐẢM BẢO NÀO; thậm chí không có sự bảo đảm ngụ ý của
KHẢ NĂNG THƯƠNG MẠI hoặc SỰ PHÙ HỢP CHO MỘT MỤC ĐÍCH CỤ THỂ. Xem
Giấy phép GNU General Public để biết thêm chi tiết.

Bạn hẳn đã nhận được một bản sao Giấy phép GNU General Public
cùng với Damn Vulnerable Web Application (DVWA). Nếu như không, hãy xem <https://www.gnu.org/licenses/>.

- - -

## Internationalisation

File này đã được dịch ra nhiều ngôn ngữ:

- Tiếng Ả Rập: [العربية](README.ar.md)
- Tiếng Trung Quốc: [简体中文](README.zh.md)
- Tiếng Pháp: [Français](README.fr.md)
- Tiếng Hàn: [한국어](README.ko.md)
- Tiếng Ba Tư: [فارسی](README.fa.md)
- Tiếng Bồ Đào Nha: [Português](README.pt.md)
- Tiếng Tây Ban Nha: [Español](README.es.md)
- Tiếng Thổ Nhĩ Kỳ: [Türkçe](README.tr.md)
- Tiếng Indonesia: [Indonesia](README.id.md)
- Tiếng Việt: [Vietnamese](README.vi.md)

Nếu bạn muốn đóng góp bản dịch, vui lòng tạo PR. Tuy nhiên, xin lưu ý rằng điều này không có nghĩa là chỉ dịch nó bằng Google Dịch và gửi, những nội dung như vậy sẽ bị từ chối. Gửi bản dịch của bạn bằng cách thêm file 'README.xx.md' mới trong đó xx là mã gồm hai chữ cái đại diện của ngôn ngữ bạn muốn (dựa vào [ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes)).

- - -

## Download

Mặc dù có nhiều phiên bản DVWA khác nhau nhưng phiên bản được hỗ trợ duy nhất là từ repo GitHub chính thức này. Bạn có thể clone nó từ repo:

```
git clone https://github.com/digininja/DVWA.git
```

Hoặc [tải file zip](https://github.com/digininja/DVWA/archive/master.zip).

- - -

## Cài đặt

### Cài đặt tự động 🛠️

**Lưu ý, đây không phải là script chính thức của DVWA, nó được viết bởi [IamCarron](https://github.com/iamCarron/). Rất nhiều nỗ lực đã được thực hiện để tạo script và khi nó được tạo, nó không làm bất cứ điều gì độc hại, tuy nhiên, để đề phòng, bạn nên xem lại script trước khi chạy nó một cách mù quáng trên hệ thống của mình. Vui lòng báo cáo bất kỳ lỗi nào cho [IamCarron](https://github.com/iamCarron/), chứ không phải reong repo này.**

Script cấu hình tự động cho DVWA trên các máy dựa trên Debian, bao gồm Kali, Ubuntu, Kubuntu, Linux Mint, Zorin OS...

**Lưu ý: Script này yêu cầu quyền root và được điều chỉnh cho các distro dựa trên Debian. Đảm bảo bạn đang chạy nó với quyền root user.**

#### Yêu cầu cài đặt

- **Hệ điều hành:** Distro trên Debian (Kali, Ubuntu, Kubuntu, Linux Mint, Zorin OS).
- **Đặc quyền:** Sử dụng root user.

#### Các bước cài đặt

##### Bằng một lệnh duy nhất (One-liner)

Lệnh này sẽ tải script cài đặt được viết bởi [@IamCarron](https://github.com/IamCarron) xuống và tự động chạy script đó. Điều này sẽ không được đưa vào đây nếu chúng tôi không tin cậy tác giả và kịch bản như khi chúng tôi xem xét nó, nhưng luôn có khả năng ai đó sẽ lừa đảo và vì vậy nếu bạn không cảm thấy an toàn khi chạy code của người khác mà không kiểm tra trước, hãy làm theo quy trình thủ công và bạn có thể xem lại sau khi tải xuống.

```bash
sudo bash -c "$(curl --fail --show-error --silent --location https://raw.githubusercontent.com/IamCarron/DVWA-Script/main/Install-DVWA.sh)"
```

##### Chạy script thủ công

1. **Tải script:**

   ```bash
   wget https://raw.githubusercontent.com/IamCarron/DVWA-Script/main/Install-DVWA.sh
   ```

2. **Sử quyền cho script để có thể chạy:**

   ```bash
   chmod +x Install-DVWA.sh
   ```

3. **Chạy script với quyền root:**
   ```bash
   sudo ./Install-DVWA.sh
   ```

### Video hướng dẫn cài đặt

- [Installing DVWA on Kali running in VirtualBox](https://www.youtube.com/watch?v=WkyDxNJkgQ4)
- [Installing DVWA on Windows using XAMPP](https://youtu.be/Yzksa_WjnY0)
- [Installing Damn Vulnerable Web Application (DVWA) on Windows 10](https://www.youtube.com/watch?v=cak2lQvBRAo)

### Windows + XAMPP

Cách dễ nhất để cài đặt DVWA là tải xuống và cài đặt [XAMPP](https://www.apachefriends.org/) nếu bạn chưa thiết lập.

XAMPP là một bản phân phối Apache rất dễ cài đặt cho Linux, Solaris, Windows và Mac OS X. Gói này bao gồm máy chủ web Apache, MySQL, PHP, Perl, máy chủ FTP và phpMyAdmin.

[Video](https://youtu.be/Yzksa_WjnY0) này sẽ hướng dẫn bạn quy trình cài đặt cho Windows nhưng quy trình này sẽ tương tự đối với các hệ điều hành khác.

### Docker

Cảm ơn sự giúp đỡ từ [hoang-himself](https://github.com/hoang-himself) và [JGillam](https://github.com/JGillam), mọi commit với nhánh `master` đều khiến Docker image được build và sẵn sàng để kéo xuống từ GitHub Container Register.

Để biết thêm thông tin, hãy duyệt qua [Docker image dựng sẵn](https://github.com/digininja/DVWA/pkgs/container/dvwa).

#### Bắt đầu

Điều kiện: Cần Docker và Docker Compose.

- Nếu bạn đang sử dụng Docker Desktop thì cả hai đã được cài đặt sẵn.
- Nếu bạn thích Docker Engine trên Linux, hãy nhớ làm theo [hướng dẫn cài đặt](https://docs.docker.com/engine/install/#server) của họ.

**Chúng tôi cung cấp hỗ trợ cho bản phát hành Docker mới nhất như ở trên.**
Nếu bạn đang sử dụng Linux và package Docker đi kèm với package manager của mình, nó có thể cũng hoạt động nhưng chỉ dừng lại ở việc hỗ trợ.

Việc nâng cấp Docker từ package manager lên phiên bản upstream yêu cầu bạn gỡ cài đặt các phiên bản cũ như trong hướng dẫn sử dụng dành cho [Ubuntu](https://docs.docker.com/engine/install/ubuntu/#uninstall-old-versions), [Fedora](https://docs.docker.com/engine/install/fedora/#uninstall-old-versions) và các distro khác.
Dữ liệu Docker (containers, images, volumes, etc.) sẽ không bị ảnh hưởng nhưng nếu như có lỗi xảy ra, hãy [báo cáo cho Docker](https://www.docker.com/support) và tìm kiếm cách để sửa lỗi.

Hãy bắt đầu:

1. Chạy `docker version` và `docker compose version` để xem bạn đã cài đặt Docker và Docker Compose đúng cách chưa. Bạn sẽ có thể xem phiên bản của chúng trong output.

   Ví dụ:

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

   Nếu bạn không thấy gì hoặc gặp lỗi không tìm thấy lệnh, hãy làm theo các điều kiện tiên quyết để cài đặt Docker và Docker Compose.

2. Clone hoặc download repo này về và giải nén (xem [Download](#download)).
3. Mở terminal vả tuy cập vào folder (`DVWA`).
4. Chạy `docker compose up -d`.

DVWA sẽ chạy trên `http://localhost:4280`.

**Lưu ý rằng để chạy DVWA trong container, máy chủ web đang lắng nghe trên port 4280 thay vì port 80 thông thường.**
Để biết thêm thông tin về quyết định này, hãy xem [I want to run DVWA on a different port](#i-want-to-run-dvwa-on-a-different-port).

#### Local Build

Nếu bạn đã thực hiện các thay đổi local và muốn xây dựng dự án từ local, hãy vào `compose.yml` và thay đổi `pull_policy: always` thành `pull_policy: build`.

Việc chạy `docker compose up -d` sẽ kích hoạt Docker xây dựng image từ local bất kể những gì có sẵn trong registry.

Xem thêm: [`pull_policy`](https://github.com/compose-spec/compose-spec/blob/master/05-services.md#pull_policy).

### Phiên bản PHP

Lý tưởng nhất là bạn nên sử dụng phiên bản PHP ổn định mới nhất vì đó là phiên bản mà ứng dụng này sẽ được phát triển và thử nghiệm.

Nếu bạn sử dụng PHP 5.x thì sẽ không được hỗ trợ.

Các phiên bản dưới 7.3 có các vấn đề sẽ gây ra lỗi, hầu hết ứng dụng sẽ hoạt động nhưng chuyện gì cũng có thể xảy ra. Trừ khi bạn có lý do chính đáng để sử dụng phiên bản cũ như vậy, nếu không sẽ không được hỗ trợ.

### Linux Packages

Nếu bạn đang sử dụng bản distro Linux dựa trên Debian, bạn sẽ cần cài đặt các gói sau _(hoặc tương đương)_:

- apache2
- libapache2-mod-php
- mariadb-server
- mariadb-client
- php php-mysqli
- php-gd

Bạn nên cập nhật trước đó để đảm bảo rằng bạn sẽ nhận được phiên bản mới nhất của mọi thứ.

```
apt update
apt install -y apache2 mariadb-server mariadb-client php php-mysqli php-gd libapache2-mod-php
```

Trang web sẽ hoạt động với MySQL thay vì MariaDB nhưng chúng tôi đặc biệt khuyên dùng MariaDB vì nó hoạt động tốt trong khi bạn phải thực hiện các thay đổi để MySQL hoạt động chính xác.

## Cấu hình

### File cấu hình

DVWA gửi kèm một bản sao dummy của file cấu hình mà bạn sẽ cần copy rồi thực hiện các thay đổi thích hợp. Trên Linux, giả sử bạn đang ở trong folder DVWA, việc này có thể được thực hiện như sau:

`cp config/config.inc.php.dist config/config.inc.php`

Trên Windows, việc này có thể khó hơn một chút nếu bạn đang ẩn phần file extension. Nếu bạn không chắc chắn về điều này, blog này sẽ giải thích thêm về điều đó:

[How to Make Windows Show File Extensions](https://www.howtogeek.com/205086/beginner-how-to-make-windows-show-file-extensions/)

### Database Setup

Database setup rất đơn giản bằng cách nhấn `Setup DVWA` trên menu chính, sau đó nhấn `Create / Reset Database`. Tanh ấy sẽ tạo/reset database cho bạn với một số dữ liệu.

Nếu bạn gặp lỗi khi cố gắng tạo database, hãy đảm bảo thông tin xác thực database của bạn là chính xác trong `./config/config.inc.php`. _File này khác với config.inc.php.dist (file ví dụ)._

Các biến mặc định như sau:

```php
$_DVWA[ 'db_server'] = '127.0.0.1';
$_DVWA[ 'db_port'] = '3306';
$_DVWA[ 'db_user' ] = 'dvwa';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';
$_DVWA[ 'db_database' ] = 'dvwa';
```

Lưu ý, nếu bạn đang sử dụng MariaDB chứ không phải MySQL (MariaDB là mặc định trong Kali), thì bạn không thể sử dụng root use của database, bạn phải tạo người dùng database mới. Để thực hiện việc này, hãy kết nối với database với tư cách là root user, sau đó sử dụng các lệnh sau:

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

### Tắt Xác Thực (Authentication)

Một số tool không hoạt động tốt với xác thực nên không thể sử dụng với DVWA. Để giải quyết vấn đề này, có một tùy chọn cấu hình để tắt tính năng kiểm tra xác thực. Để thực hiện, bạn chỉ cần đặt thông tin sau trong file cấu hình:

```php
$_DVWA[ 'disable_authentication' ] = true;
```

Bạn cũng sẽ cần đặt mức bảo mật thành mức phù hợp với thử nghiệm bạn muốn thực hiện:

```php
$_DVWA[ 'default_security_level' ] = 'low';
```

Với cấu hình này, bạn có thể truy cập tất cả các tính năng mà không cần đăng nhập và đặt bất kỳ cookie nào.

### Quyền cũa folder

- `./hackable/uploads/` - Dịch vụ web cần có khả năng ghi được (đối với tải file lên).

### Cấu hình PHP

Trên Linux, hãy vào `/etc/php/x.x/fpm/php.ini` hoặc `/etc/php/x.x/apache2/php.ini`.

- Để cho phép Bao gồm file remote (Remote File Inclusions - RFI):

  - `allow_url_include = on` [[allow_url_include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
  - `allow_url_fopen = on` [[allow_url_fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]

- Để đảm bảo PHP hiển thị tất cả các thông báo lỗi:
  - `display_errors = on` [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]
  - `display_startup_errors = on` [[display_startup_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-startup-errors)]

Đảm bảo bạn khởi động lại dịch vụ php hoặc Apache sau khi thực hiện các thay đổi.

### reCAPTCHA

Nước này chỉ cần cho lab "Insecure CAPTCHA", nếu bạn không làm lab thì có thể bỏ qua

Đã tạo một cặp API key từ <https://www.google.com/recaptcha/admin/create>.

Sau đó copy vào phần`./config/config.inc.php`:

- `$_DVWA[ 'recaptcha_public_key' ]`
- `$_DVWA[ 'recaptcha_private_key' ]`

### Thông tin xác thực mặc định (Default credentials)

**Default username = `admin`**

**Default password = `password`**

_...có thể dễ bị brute forced ;)_

Login URL: http://127.0.0.1/login.php

_Lưu ý: URl này sẽ khác nếu bạn cài đặt DVWA vào một folder khác._

- - -

## Troubleshooting

Hướng dẫn này giả sử bạn đang sử dụng distro dựa trên Debian, chẳng hạn như Debian, Ubuntu và Kali. Đối với các distro khác, hãy tiếp tục làm theo, nhưng hãy cập nhật lệnh khi cần.

### Containers

#### Tôi muốn xem logs

Nếu bạn đang sử dụng Docker Desktop, logs có thể được truy cập từ ứng dụng.
Một số chi tiết nhỏ có thể thay đổi với các phiên bản mới hơn, nhưng cơ bản là giống nhau.

![Tổng quan của DVWA compose](./docs/graphics/docker/overview.png)
![Xem DVWA logs](docs/graphics/docker/detail.png)

Logs có thể được xem từ terminal.

1. Mở terminal vào vào folder DVWA
2. Xem logs

   ```shell
   docker compose logs
   ```

   Nếu bạn muốn export logs ra file riêng, e.g. `dvwa.log`

   ```shell
   docker compose logs >dvwa.log
   ```

#### Tôi muốn chạy DVWA trên port khác

Chúng tôi không sử dụng port 80 như mặc định vì một số lý do:

- Một số người dùng có thể đã chạy gì đó trên port 80.
- Một số người dùng có thể đang sử dụng rootless container engine (như Podman) và 80 là cổng đặc quyền (< 1024). Cấu hình thêm (e.g. cài đặt `net.ipv4.ip_unprivileged_port_start`) là cần thiết nhưng bạn phải tự tìm hiểu.

bạn có thể expose DVWA trên port khác bằng cách sử port binding trong `compose.yml`.
Ví dụ, bạn có thể thay đổi:

```yml
ports:
  - 127.0.0.1:4280:80
```

thành

```yml
ports:
  - 127.0.0.1:8806:80
```

DVWA sẽ chạy trên `http://localhost:8806`.

Trong trường hợp bạn muốn DVWA không chỉ có thể truy cập được từ thiết bị của riêng bạn mà còn
trên mạng local của bạn (ví dụ: vì bạn đang thiết lập máy thử nghiệm cho workshop), bạn
có thể xóa `127.0.0.1:` khỏi port mapping (hoặc thay thế nó bằng IP LAN của bạn). Bằng cách này
sẽ nghe trên tất cả các thiết bị có sẵn. Mặc định an toàn phải luôn là chỉ listen trên
thiết bị loopback local. Xét cho cùng, đây là một ứng dụng web dễ bị tấn công, chạy trên máy của bạn.

#### DVWA tự động chạy khi Docker chạy

File [`compose.yml`](./compose.yml) sẽ tự động chạy DVWA và database khi Docker chạy.

Nếu bạn không muốn, xóa hoặc comment dòng `restart: unless-stopped` trong [`compose.yml`](./compose.yml).

Nếu bạn muốn tắt tạm thời, bạn có thể chạy `docker compose stop`, hoặc xài Docker Desktop, tìm `dvwa` và nhấn Stop.
Thêm vào đó, bạn có thể xóa containers, hoặc chạy `docker compose down`.

### Log files

Trên Linux, Apache tạo 2 file log mặc định, `access.log` và `error.log` và trên hện thống với nền tảng Debian, các file log thường nằm trong `/var/log/apache2/`.

Khi gửi báo cáo lỗi, sự cố hoặc bất kỳ điều gì tương tự, vui lòng bao gồm ít nhất năm dòng cuối cùng từ mỗi file này. Trên các distro dựa trên Debian, bạn có thể nhận được những thứ như thế này:

```
tail -n 5 /var/log/apache2/access.log /var/log/apache2/error.log
```

### Truy cập vào site nhưng nhận 404

Nếu bạn gặp lỗi này thi bạn cần hiểu rõ về vị trí của file. Mặc định, folder gốc của tài liệu Apache (nơi bắt đầu tìm kiếm nội dung web) là `/var/www/html`. Nếu bạn đặt file `hello.txt` trong folder này, để truy cập nó bạn cần duyệt đến `http://localhost/hello.txt`.

Nếu bạn đã tạo một folder và đặt file vào đó - `/var/www/html/mydir/hello.txt` - sau đó bạn sẽ cần phải duyệt đến `http://localhost/mydir/hello.txt`.

Linux theo mặc định có phân biệt chữ hoa chữ thường, trong ví dụ trên, nếu bạn cố duyệt đến bất kỳ trang nào trong số này, bạn sẽ nhận được một `404 Not Found`:

- `http://localhost/MyDir/hello.txt`
- `http://localhost/mydir/Hello.txt`
- `http://localhost/MYDIR/hello.txt`

Điều này ảnh hưởng đến DVWA như thế nào? Hầu hết mọi người sử dụng git để checkout DVWA vào `/var/www/html`, bạn sẽ được đưa tới `/var/www/html/DVWA/` với tất cả các file DVWA bên trong nó. Sau đó họ duyệt đến `http://localhost/` và nhận được `404` hoặc trang welcome mặc định của Apache. Vì file nằm trong DVWA, bạn phải duyệt tới `http://localhost/DVWA`.

Một lỗi phổ biến khác là duyệt đến `http://localhost/dvwa` sẽ dẫn đến `404` vì `dvwa` không phải `DVWA` liên quan đến việc khớp folder trong Linux.

Vì vậy, sau khi thiết lập, nếu bạn cố truy cập trang web và nhận được `404`, hãy nghĩ xem bạn đã cài đặt các file vào đâu, vị trí của chúng có liên quan đến folder gốc của tài liệu và trường hợp của folder bạn đã sử dụng là gì.

### "Access denied" khi setup

Nếu bạn thấy thông báo sau khi chạy script thiết lập, điều đó có nghĩa là tên người dùng hoặc mật khẩu trong file cấu hình không khớp với tên người dùng hoặc mật khẩu được định cấu hình trên database:

```
Database Error #1045: Access denied for user 'notdvwa'@'localhost' (using password: YES).
```

Lỗi cho bạn biết rằng bạn đang sử dụng tên người dùng `notdvwa`.

Lỗi sau đây cho biết bạn đã trỏ file cấu hình vào database sai.

```
SQL: Access denied for user 'dvwa'@'localhost' to database 'notdvwa'
```

Lỗi báo rằng bạn đang sử dụng người dùng `dvwa` và đang cố gắng kết nối với database `notdvwa`.

Điều đầu tiên cần làm là kiểm tra kỹ xem bạn nghĩ mình đã đặt gì trong file cấu hình có thực sự ở đó không.

Nếu như bạn đã chắc chắn, việc tiếp theo cần làm là kiểm tra xem bạn có thể đăng nhập với tư cách người dùng trên command line hay không. Giả sử bạn có người dùng database là `dvwa` và mật khẩu là `p@ssw0rd`, hãy chạy lệnh sau:

```
mysql -u dvwa -pp@ssw0rd -D dvwa
```

_Lưu ý: Không có khoảng trắng sau -p_

Nếu bạn thấy như sau thì mật khẩu là chính xác:

```
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 14
Server version: 10.3.22-MariaDB-0ubuntu0.19.10.1 Ubuntu 19.10

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [dvwa]>
```

Vì bạn có thể kết nối trên dòng lệnh, nên có thể đã xảy ra lỗi trong file cấu hình, hãy kiểm tra kỹ và sau đó nêu vấn đề nếu bạn vẫn không thể làm mọi thứ hoạt động.

Nếu bạn thấy thông báo sau thì tên người dùng hoặc mật khẩu bạn đang sử dụng không đúng. Thử lại bước [Database Setup](#database-setup) và đảm bảo bạn sử dụng cùng tên người dùng và mật khẩu trong suốt quá trình.

```
ERROR 1045 (28000): Access denied for user 'dvwa'@'localhost' (using password: YES)
```

Nếu bạn nhận được thông tin sau thì thông tin đăng nhập của người dùng là chính xác nhưng người dùng không có quyền truy cập vào database. Một lần nữa, hãy lặp lại các bước thiết lập và kiểm tra tên database bạn đang sử dụng.

```
ERROR 1044 (42000): Access denied for user 'dvwa'@'localhost' to database 'dvwa'
```

Lỗi cuối cùng bạn có thể gặp phải là:

```
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
```

Đây không phải là lỗi xác thực mà là máy chủ database không chạy. Hãy thử:

```sh
sudo service mysql start
```

### Từ chối kết nối

Một lỗi tương tự như lỗi này:

```
Fatal error: Uncaught mysqli_sql_exception: Connection refused in /var/sites/dvwa/non-secure/htdocs/dvwa/includes/dvwaPage.inc.php:535
```

Có nghĩa là máy chủ database của bạn không chạy hoặc bạn đã nhập sai địa chỉ IP trong file cấu hình.

Kiểm tra dòng này trong file cấu hình để xem máy chủ database dự kiến sẽ ở đâu:

```
$_DVWA[ 'db_server' ]   = '127.0.0.1';
```

Sau đó đi đến máy chủ này và kiểm tra xem nó có đang chạy không. Trong Linux, chạy:

```
systemctl status mariadb.service
```

Và bạn đang tìm kiếm thứ gì đó như sau, quan trọng là nó ghi `active (running)`.

```
● mariadb.service - MariaDB 10.5.19 database server
     Loaded: loaded (/lib/systemd/system/mariadb.service; enabled; preset: enabled)
     Active: active (running) since Thu 2024-03-14 16:04:25 GMT; 1 week 5 days ago
```

Nếu nó không chạy, bạn có thể khởi động nó bằng:

```
sudo systemctl stop mariadb.service
```

Lưu ý `sudo` và đảm bảo bạn nhập mật khẩu người dùng Linux của mình nếu được yêu cầu.

Trong Windows, hãy kiểm tra trạng thái trong bảng điều khiển XAMPP.

### Phương thức xác thực không xác định

Với các phiên bản mới nhất của MySQL, PHP không còn có thể giao tiếp với database ở cấu hình mặc định của nó nữa. Nếu bạn chạy script thiết lập và nhận được thông báo sau thì là bạn đã cấu hình nó.

```
Database Error #2054: The server requested authentication method unknown to the client.
```

Bạn có hai lựa chọn, đơn giản nhất là gỡ cài đặt MySQL và cài đặt MariaDB. Sau đây là hướng dẫn chính thức từ project MariaDB:

<https://mariadb.com/resources/blog/how-to-migrate-from-mysql-to-mariadb-on-linux-in-five-steps/>

Ngoài ra, hãy làm theo các bước sau:

1. Với quyền root, chỉnh sửa file: `/etc/mysql/mysql.conf.d/mysqld.cnf`
1. Dưới dòng `[mysqld]`, thêm vào như sau:
   `default-authentication-plugin=mysql_native_password`
1. Restart database: `sudo service mysql restart`
1. Kiểm tra phương thức xác thực cho người dùng database của bạn:

   ```sql
   mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
   +- - -- - -- - - - -+- - -- - -- - -- - -- - -- - -+- - -- - -- - -- - -- - -- - -- - - - -+
   | Host      | User             | plugin                |
   +- - -- - -- - - - -+- - -- - -- - -- - -- - -- - -+- - -- - -- - -- - -- - -- - -- - - - -+
   | localhost | dvwa             | caching_sha2_password |
   +- - -- - -- - - - -+- - -- - -- - -- - -- - -- - -+- - -- - -- - -- - -- - -- - -- - - - -+
   1 rows in set (0.00 sec)
   ```

1. Bạn sẽ thấy `caching_sha2_password`. Nếu có , hãy chạy:

   ```sql
   mysql> ALTER USER dvwa@localhost IDENTIFIED WITH mysql_native_password BY 'p@ssw0rd';
   ```

1. Chạy lại check, bạn sẽ thấy `mysql_native_password`.

   ```sql
   mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
   +- - -- - -- - - - -+- - -- - -+- - -- - -- - -- - -- - -- - -- - - - -+
   | Host      | User | plugin                |
   +- - -- - -- - - - -+- - -- - -+- - -- - -- - -- - -- - -- - -- - - - -+
   | localhost | dvwa | mysql_native_password |
   +- - -- - -- - - - -+- - -- - -+- - -- - -- - -- - -- - -- - -- - - - -+
   1 row in set (0.00 sec)
   ```

Sau cùng, quá trình thiết lập sẽ hoạt động như bình thường.

Nếu bạn muốn biết thêm thông tin, hãy xem trang sau: <https://www.php.net/manual/en/mysqli.requirements.php>.

### Lỗi Database #2002: No such file or directory.

Máy chủ database không chạy. Nếu như bạn sử dụng distro dựa trên Debian, hảy chạy:

```sh
sudo service mysql start
```

### Lỗi "MySQL server has gone away" và "Packets out of order"

Có một số lý do khiến bạn gặp phải những lỗi này, nhưng rất có thể là phiên bản máy chủ database bạn đang chạy không tương thích với phiên bản PHP.

Điều này thường thấy nhất khi bạn đang chạy phiên bản MySQL mới nhất dưới dạng PHP và nó không hoạt động tốt. Lời khuyên tốt nhất là hãy bỏ MySQL và cài đặt MariaDB vì đây không phải là thứ chúng tôi có thể hỗ trợ.

Nếu bạn muốn biết thêm thông tin, hãy xem trang sau

<https://www.ryadel.com/en/fix-mysql-server-gone-away-packets-order-similar-mysql-related-errors/>

### Command Injection không thể hoạt động

Apache có thể không có đặc quyền đủ cao để chạy lệnh trên máy chủ web. Nếu bạn đang chạy DVWA trên Linux, hãy đảm bảo bạn đã đăng nhập bằng quyền root. Trong Windows đăng nhập với tư cách Administrator

### Tại sao databse không thể kết nối với CentOS?

Có thể bạn đang gặp vấn đề với SELinux. Tắt SELinux hoặc chạy lệnh này để cho phép máy chủ web giao tiếp với database:

```
setsebool -P httpd_can_network_connect_db 1
```

### Một số thứ khác

Để biết thông tin troubleshooting mới nhất, vui lòng đọc cả ticket mở và đã đóng trong repo:

<https://github.com/digininja/DVWA/issues>

Trước khi gửi ticket, vui lòng đảm bảo rằng bạn đang chạy phiên bản code mới nhất từ repo. Đây không phải là bản phát hành mới nhất, đây là code mới nhất từ master branch.

Nếu gửi ticket, vui lòng gửi ít nhất các thông tin sau:

- Hệ điều hành
- 5 dòng cuối cùng từ lỗi máy chủ web sẽ ghi trực tiếp sau khi xảy ra bất kỳ lỗi nào bạn đang báo cáo
- Nếu đó là lỗi xác thực database, hãy thực hiện các bước trên và chụp ảnh màn hình từng bước. Gửi những thứ này cùng với ảnh chụp màn hình của phần file cấu hình hiển thị người dùng và mật khẩu database.
- Mô tả đầy đủ về những gì đang xảy ra, những gì bạn mong đợi sẽ xảy ra và những gì bạn đã cố gắng làm để khắc phục nó.

- - -

## Hướng dẫn chi tiết

Tôi sẽ cố gắng tập hợp một số video hướng dẫn tìm hiểu một số lỗ hổng và chỉ ra cách phát hiện chúng cũng như cách khai thác chúng. Đây là những cái tôi đã thực hiện cho đến nay:

[Finding and Exploiting Reflected XSS](https://youtu.be/V4MATqtdxss)

- - -

## SQLite3 SQL Injection

_Hỗ trợ cho vấn đề này còn hạn chế, trước khi nêu ra vấn đề, vui lòng đảm bảo rằng bạn đã debug, không chỉ đơn giản là "nó không hoạt động"._

Theo mặc định, SQLi và Blind SQLi sẽ giao tiếp với máy chủ MariaDB/MySQL được web sử dụng nhưng thay vào đó, bạn có thể chuyển sang thực hiện kiểm tra SQLi đối với SQLite3.

Tôi sẽ không đề cập đến cách để SQLite3 hoạt động với PHP, nhưng nó sẽ là một trường hợp đơn giản là cài đặt package `php-sqlite3` và đảm bảo rằng nó được kích hoạt.

Để thực hiện chuyển đổi, chỉ cần chỉnh sửa file cấu hình và thêm hoặc chỉnh sửa các dòng sau:

```
$_DVWA["SQLI_DB"] = "sqlite";
$_DVWA["SQLITE_DB"] = "sqli.db";
```

Mặc định nó xài `database/sqli.db`, nếu bạn lỡ mess up, chỉ cần sao chép `database/sqli.db.dist` đè lên.

Các thử thách hoàn toàn giống với MySQL, thay vào đó chúng chỉ chạy với SQLite3.

- - -

## 👨‍💻 Những người đóng góp

Cảm ơn tất cả những đóng góp của bạn và giữ cho dự án này được cập nhật. :heart:

Nếu bạn có ý tưởng, cải tiến nào đó hoặc chỉ đơn giản là muốn cộng tác, bạn có thể đóng góp và tham gia vào dự án, vui lòng gửi PR của mình.

<p align="center">
<a href="https://github.com/digininja/DVWA/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=digininja/DVWA&max=500">
</a>
</p>

- - -

## Báo cáo lỗ hổng

Nói một cách ngắn gọn là LÀN ƠN ĐỪNG GỬI GÌ HẾT!

cỨ KHOẢNG HẰNG NĂM, MỘT ai đó sẽ gửi báo cáo về lỗ hổng mà họ tìm thấy trong ứng dụng, một số được viết rất tốt, đôi khi tốt hơn những gì tôi thấy trong các báo cáo pen test có trả phí, một số chỉ là "bạn đang thiếu header kìa bạn ơi, hãy trả tiền cho tôi đê".

Vào năm 2023, vấn đề đã nâng lên một tầm cao mới khi ai đó quyết định yêu cầu CVE về một lỗ hổng bảo mật, họ đã nhận được [CVE-2023-39848](https://nvd.nist.gov/vuln/detail/CVE-2023-39848). Nhiều sự cố khá vui nhộn đã xảy ra sau đó và kha khá thời gian lãng phí để sửa lỗi này.

Ứng dụng này có lỗ hổng và đó là cố ý. Hầu hết là những tài liệu được ghi chép đầy đủ mà bạn xem qua như những bài học, một số khác là những tài liệu "ẩn", những tài liệu bạn có thể tự tìm thấy. Nếu bạn thực sự muốn thể hiện kỹ năng tìm kiếm các tính năng bổ sung ẩn của mình, hãy viết một bài đăng trên blog hoặc tạo video vì có thể có những người ở ngoài đó sẽ quan tâm đến việc tìm hiểu về chúng và cách bạn tìm thấy chúng. Nếu bạn gửi liên kết cho chúng tôi, chúng tôi thậm chí có thể đưa liên kết đó vào phần references.

## Link

Trang chủ dự án: <https://github.com/digininja/DVWA>

_Được tạo ra bởi DVWA team_

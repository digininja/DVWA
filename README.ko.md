# DAMN VULNERABLE WEB APPLICATION

Damn Vulnerable Web Application (DVWA)은 매우 취약한 PHP/MySQL 웹 애플리케이션입니다. DVWA의 주요 목표는 보안 전문가들이 자신의 기술과 도구를 합법적인 환경에서 테스트하고, 웹 개발자들이 웹 애플리케이션 보안 프로세스를 더 잘 이해하도록 돕는 것입니다. 또한, 학생과 교사들이 통제된 교실 환경에서 웹 애플리케이션 보안에 대해 배우는 데 도움을 주고자 합니다.

DVWA의 목표는 **가장 흔한 웹 취약점들에 대한 연습**을 **다양한 난이도로 제공하는 것**입니다. 간단하고 직관적인 인터페이스를 갖추고 있습니다. 이 소프트웨어에는 **문서화된 취약점과 문서화되지 않은 취약점**이 모두 존재합니다. 이는 의도적인 것으로, 가능한 많은 문제를 발견해 보시기를 권장합니다.
- - -

## 주의!

Damn Vulnerable Web Application은 매우 취약합니다! **호스팅 제공자의 공개 html 폴더나 인터넷에 노출된 서버에 업로드하지 마십시오.** 그렇지 않으면 서버가 침해될 수 있습니다. 가상 머신(예: [VirtualBox](https://www.virtualbox.org/) or [VMware](https://www.vmware.com/))을 사용하고 NAT 네트워킹 모드로 설정하는 것이 좋습니다. 게스트 머신 내에서 [XAMPP](https://www.apachefriends.org/)를 다운로드하여 웹 서버와 데이터베이스를 설치할 수 있습니다.

### 면책 조항

우리는 DVWA의 사용 방식에 대해 책임을 지지 않습니다. 애플리케이션의 목적은 명확히 설명되어 있으며, 악의적으로 사용해서는 안 됩니다. 사용자가 DVWA를 라이브 웹 서버에 설치하지 않도록 경고와 조치를 취했습니다. DVWA 설치를 통해 웹 서버가 침해된 경우, 이는 설치한 개인의 책임입니다.

- - -

## 라이선스

이 파일은 Damn Vulnerable Web Application (DVWA)의 일부입니다.

Damn Vulnerable Web Application (DVWA)은 자유 소프트웨어입니다: 귀하는 이를 재배포하거나 수정할 수 있으며, GNU 일반 공중 사용 허가서(GNU General Public License) 버전 3 또는 (옵션으로) 그 이후 버전의 조건에 따라 이를 사용할 수 있습니다.

Damn Vulnerable Web Application (DVWA)은 유용할 것이라는 희망으로 배포되지만, 어떠한 형태의 보증도 제공하지 않습니다. 상업성이나 특정 목적에의 적합성에 대한 묵시적인 보증도 포함되지 않습니다. 자세한 내용은 GNU 일반 공중 사용 허가서를 참조하십시오.

Damn Vulnerable Web Application (DVWA)와 함께 GNU 일반 공중 사용 허가서 사본을 받았어야 합니다. 그렇지 않다면 <https://www.gnu.org/licenses/>에서 확인하십시오.

- - -

## 국제화
이 파일은 여러 언어로 제공됩니다:
- 스페인어: [Espaol](README.es.md)
- 아랍어: [العربية](README.ar.md)
- 영어: [English](README.md)
- 인도네시아어: [Indonesia](README.id.md)
- 중국어: [简体中文](README.zh.md)
- 터키어: [Trke](README.tr.md)
- 페르시아어: [فارسی](README.fa.md)
- 포르투갈어: [Portugus](README.pt.md)
- 프랑스어: [Franais](README.fr.md)
- 한국어: [한국어](README.ko.md)

번역에 기여하고자 한다면 PR을 제출해 주십시오. 단, 구글 번역기를 사용한 번역은 거부됩니다. 번역본을 제출하려면, 번역된 'README.xx.md' 파일을 추가하십시오. 여기서 xx는 원하는 언어의 두 글자 코드([ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) 기반)입니다.

## 다운로드

DVWA에는 여러 가지 버전이 있지만, 유일하게 지원되는 버전은 공식 GitHub 저장소에서 제공하는 최신 소스입니다. 저장소에서 클론할 수 있습니다:

```
git clone https://github.com/digininja/DVWA.git
```

또는 [파일의 ZIP을 다운로드](https://github.com/digininja/DVWA/archive/master.zip)할 수 있습니다.

- - -

## 설치

### 자동 설치 🛠️

**참고: 이것은 공식 DVWA 스크립트가 아니며, [IamCarron](https://github.com/iamCarron/)에 의해 작성되었습니다. 스크립트를 작성하는 데 많은 노력이 들어갔으며, 작성 당시에는 악의적인 행동을 하지 않았지만, 시스템에서 무작정 실행하기 전에 스크립트를 검토하는 것이 좋습니다. 버그가 발견되면 이곳이 아닌 [IamCarron](https://github.com/iamCarron/)에게 보고해 주세요.**

Debian 기반 머신(Kali, Ubuntu, Kubuntu, Linux Mint, Zorin OS 등)에서 DVWA를 자동으로 구성하는 스크립트입니다.

**참고: 이 스크립트는 루트 권한이 필요하며 Debian 기반 시스템에 맞춰져 있습니다. 반드시 루트 사용자로 실행하세요.**

#### 설치 요구 사항

- **운영 체제:** Debian 기반 시스템 (Kali, Ubuntu, Kubuntu, Linux Mint, Zorin OS)
- **권한:** 루트 사용자로 실행

#### 설치 단계

1. **스크립트 다운로드:**
   ```bash
   wget https://raw.githubusercontent.com/IamCarron/DVWA-Script/main/Install-DVWA.sh
   ```

2. **스크립트 실행 권한 부여:**
   ```bash
   chmod +x Install-DVWA.sh
   ```

3. **루트로 스크립트 실행:**
   ```bash
   sudo ./Install-DVWA.sh
   ```

### 설치 동영상

- [VirtualBox에서 실행 중인 Kali에 DVWA 설치](https://www.youtube.com/watch?v=WkyDxNJkgQ4)
- [Windows에서 XAMPP를 사용하여 DVWA 설치](https://youtu.be/Yzksa_WjnY0)
- [Windows 10에 Damn Vulnerable Web Application (DVWA) 설치](https://www.youtube.com/watch?v=cak2lQvBRAo)

### Windows + XAMPP

가장 쉬운 DVWA 설치 방법은 [XAMPP](https://www.apachefriends.org/)를 다운로드하여 설치하는 것입니다. 이미 웹 서버가 설정되어 있지 않은 경우에 유용합니다.

XAMPP는 Linux, Solaris, Windows 및 Mac OS X용으로 설치하기 쉬운 Apache 배포판입니다. 이 패키지에는 Apache 웹 서버, MySQL, PHP, Perl, FTP 서버 및 phpMyAdmin이 포함되어 있습니다.

이 [비디오](https://youtu.be/Yzksa_WjnY0)는 Windows에 대한 설치 과정을 안내하지만, 다른 OS에서도 유사할 것입니다.

### Docker

[hoang-himself](https://github.com/hoang-himself)와 [JGillam](https://github.com/JGillam) 덕분에 `master` 브랜치에 대한 모든 커밋은 Docker 이미지를 빌드하고 GitHub Container Registry에서 내려받을 수 있게 합니다.

얻을 수 있는 것에 대한 자세한 내용은 [사전 빌드된 Docker 이미지](https://github.com/digininja/DVWA/pkgs/container/dvwa)를 참조하십시오.

#### 시작하기

선행 요건: Docker 및 Docker Compose.

- Docker Desktop을 사용하는 경우, 두 가지가 이미 설치되어 있어야 합니다.
- Linux에서 Docker Engine을 선호하는 경우, [설치 가이드](https://docs.docker.com/engine/install/#server)를 따라 설치하세요.

**위에서 언급한 최신 Docker 릴리스에 대한 지원을 제공합니다.**
Linux에서 패키지 관리자를 통해 제공된 Docker 패키지를 사용하는 경우에도 작동할 가능성이 있지만, 지원은 최선의 노력을 다합니다.

패키지 관리자 버전에서 업스트림으로 Docker를 업그레이드하려면 [Ubuntu](https://docs.docker.com/engine/install/ubuntu/#uninstall-old-versions), [Fedora](https://docs.docker.com/engine/install/fedora/#uninstall-old-versions) 등에서 설명하는 대로 이전 버전을 제거해야 합니다.
Docker 데이터(컨테이너, 이미지, 볼륨 등)는 영향을 받지 않아야 하지만 문제가 발생하면 [Docker에 알리고](https://www.docker.com/support) 검색 엔진을 사용하는 것이 좋습니다.

그런 다음 시작하려면:

1. `docker version` 및 `docker compose version`을 실행하여 Docker 및 Docker Compose가 제대로 설치되었는지 확인하세요. 출력에서 해당 버전을 확인할 수 있어야 합니다.

    예를 들어:

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

    아무것도 표시되지 않거나 명령어를 찾을 수 없다는 오류가 발생하면 Docker 및 Docker Compose를 설정하기 위한 필수 조건을 따르세요.

2. 이 리포지토리를 클론하거나 다운로드하여 압축을 풉니다 (참조 [다운로드](#download)).
3. 선택한 터미널을 열고 작업 디렉터리를 이 폴더(`DVWA`)로 변경합니다.
4. `docker compose up -d`를 실행합니다.

이제 DVWA는 `http://localhost:4280`에서 사용할 수 있습니다.

**컨테이너에서 DVWA를 실행할 때, 웹 서버는 일반적인 포트 80 대신 포트 4280에서 수신 대기하고 있습니다.**
이 결정에 대한 자세한 내용은 [다른 포트에서 DVWA를 실행하고 싶습니다](#i-want-to-run-dvwa-on-a-different-port)를 참조하세요.

#### 로컬 빌드

로컬에서 변경 사항을 적용하고 프로젝트를 빌드하려면 `compose.yml` 파일에서 `pull_policy: always`를 `pull_policy: build`로 변경하십시오.

`docker compose up -d`를 실행하면 레지스트리에 무엇이 있든 상관없이 Docker가 로컬에서 이미지를 빌드하도록 트리거됩니다.

참조: [`pull_policy`](https://github.com/compose-spec/compose-spec/blob/master/05-services.md#pull_policy).

### PHP 버전

이상적으로는 최신 안정 버전의 PHP를 사용하는 것이 좋습니다. 이는 이 앱이 개발 및 테스트되는 버전입니다.

PHP 5.x를 사용하려는 사람에게는 지원이 제공되지 않습니다.

7.3 미만의 버전은 문제가 발생할 수 있는 알려진 이슈가 있으며, 대부분의 앱은 작동하겠지만 무작위로 문제가 발생할 수 있습니다. 아주 좋은 이유가 없는 한, 그렇게 오래된 버전을 사용하는 경우 지원이 제공되지 않습니다.

### Linux 패키지

Debian 기반 Linux 배포판을 사용하는 경우, 다음 패키지 _(또는 이에 상응하는 것)_ 를 설치해야 합니다:

- apache2
- libapache2-mod-php
- mariadb-server
- mariadb-client
- php
- php-mysqli
- php-gd

모든 최신 버전을 받기 위해 설치 전에 업데이트를 수행하는 것이 좋습니다.

```
apt update
apt install -y apache2 mariadb-server mariadb-client php php-mysqli php-gd libapache2-mod-php
```

이 사이트는 MariaDB 대신 MySQL로도 작동하지만, MySQL을 올바르게 작동시키기 위해 변경해야 하는 반면, MariaDB는 별다른 설정 없이 바로 작동하므로 MariaDB를 강력히 추천합니다.

## 구성

### 설정 파일

DVWA에는 해당 위치에 복사한 다음 적절한 변경 사항을 가할 필요가 있는 설정 파일의 더미 복사본이 함께 제공됩니다. 리눅스에서는 DVWA 디렉토리에 있는 것으로 가정하면 다음과 같이 수행할 수 있습니다:

`cp config/config.inc.php.dist config/config.inc.php`

Windows에서는 파일 확장자를 숨기는 경우 조금 더 어려울 수 있습니다. 이에 대해 자세히 알아보려면 다음 블로그 포스트를 참조하세요:

[Windows에서 파일 확장자 표시하는 방법](https://www.howtogeek.com/205086/beginner-how-to-make-windows-show-file-extensions/)

### 데이터베이스 설정

데이터베이스를 설정하려면, 단순히 주 메뉴의 `Setup DVWA` 버튼을 클릭한 다음 `Create / Reset Database` 버튼을 클릭하면 됩니다. 이렇게 하면 데이터베이스가 생성되거나 재설정되며 일부 데이터가 포함됩니다.

데이터베이스를 생성하는 동안 오류가 발생하면 `./config/config.inc.php` 내의 데이터베이스 자격 증명이 올바른지 확인하십시오. *이는 예제 파일인 config.inc.php.dist와 다릅니다.*

변수는 다음과 같이 기본값으로 설정됩니다:

```php
$_DVWA[ 'db_server'] = '127.0.0.1';
$_DVWA[ 'db_port'] = '3306';
$_DVWA[ 'db_user' ] = 'dvwa';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';
$_DVWA[ 'db_database' ] = 'dvwa';
```

참고로, MySQL 대신 MariaDB를 사용하는 경우 (Kali의 기본값은 MariaDB입니다), 데이터베이스 루트 사용자를 사용할 수 없으므로 새 데이터베이스 사용자를 생성해야 합니다. 이를 위해 루트 사용자로 데이터베이스에 연결한 다음 다음 명령을 사용하십시오:

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

### 인증 비활성화

일부 도구는 인증을 사용할 수 없으므로 DVWA와 함께 사용할 수 없습니다. 이를 해결하기 위해 인증 확인을 비활성화하는 구성 옵션이 있습니다. 이를 위해 설정 파일에서 다음을 설정하면 됩니다:

```php
$_DVWA[ 'disable_authentication' ] = true;
```

또한 테스트하려는 내용에 적합한 보안 수준으로 보안 수준을 설정해야 합니다:

```php
$_DVWA[ 'default_security_level' ] = 'low';
```

이 상태에서는 로그인할 필요 없이 모든 기능에 액세스할 수 있습니다.

### 폴더 권한

* `./hackable/uploads/` - 웹 서비스에 의해 쓰기 가능해야 합니다 (파일 업로드를 위해).

### PHP 구성

리눅스 시스템에서는 일반적으로 `/etc/php/x.x/fpm/php.ini` 또는 `/etc/php/x.x/apache2/php.ini`에서 찾을 수 있습니다.

* 원격 파일 포함 (RFI)을 허용하려면:
    * `allow_url_include = on` [[allow_url_include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
    * `allow_url_fopen = on` [[allow_url_fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]

* PHP가 모든 오류 메시지를 표시하도록 하려면:
    * `display_errors = on` [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]
    * `display_startup_errors = on` [[display_startup_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-startup-errors)]

변경 사항을 적용한 후 php 서비스 또는 Apache를 다시 시작하는지 확인하세요.

### reCAPTCHA

이는 "Insecure CAPTCHA" 랩에서만 필요하며 해당 랩을 사용하지 않는다면 이 섹션을 무시할 수 있습니다.

<https://www.google.com/recaptcha/admin/create>에서 API 키 쌍을 생성합니다.

그런 다음 이 키는 `./config/config.inc.php`의 다음 섹션에 들어갑니다:

* `$_DVWA[ 'recaptcha_public_key' ]`
* `$_DVWA[ 'recaptcha_private_key' ]`

### 기본 자격 증명

**기본 사용자 이름 = `admin`**

**기본 암호 = `password`**

_... 쉽게 브루트 포스될 수 있음 ;)_

로그인 URL: http://127.0.0.1/login.php

_참고: DVWA를 다른 디렉토리에 설치한 경우 이 URL이 다를 수 있습니다._


- - -

## Troubleshooting
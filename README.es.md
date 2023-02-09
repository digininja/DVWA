# DAMN VULNERABLE WEB APPLICATION

Damn Vulnerable Web Application (DVWA) es una aplicación web hecha en PHP/MySQL que es extremadamente vulnerable. Su principal objetivo es ayudar a profesionales de seguridad a poner a prueba sus habilidades y herramientas en un entorno legal, ayudar a desarrolladores web a comprender mejor los procesos de asegurar aplicaciones web y ayudar tanto a estudiantes como a profesores a aprender sobre seguridad de aplicaciones web en un entorno de clase controlado.

El objetivo de DVWA es **practicar algunas de las vulnerabilidades web más comunes**, con **varios niveles de dificultad**, con una interfaz sencilla y directa.
Tener en cuenta que hay **tanto vulnerabilidades documentadas como no documentadas** en este software. Esto es intencional. Le animamos a que intente descubrir tantos problemas como sea posible.
- - -

## ¡AVISO!

¡Damn Vulnerable Web Application es extremadamente vulnerable! **No la suba a la carpeta html pública de su proveedor de alojamiento ni a ningún servidor expuesto a Internet**, ya que se verán comprometidos. Se recomienda utilizar una máquina virtual (como [VirtualBox](https://www.virtualbox.org/) o [VMware](https://www.vmware.com/)), que esté configurada en modo de red NAT. Dentro de una máquina huésped, puede descargar e instalar [XAMPP](https://www.apachefriends.org/) para montar el servidor web y la base de datos.

### Descargo de responsabilidad

No nos hacemos responsables de la forma en que cualquier persona utilice esta aplicación (DVWA). Hemos dejado claros los propósitos de la aplicación y no debe usarse de forma malintencionada. Hemos advertido y tomado medidas para evitar que los usuarios instalen DVWA en servidores web activos. Si su servidor web se ve comprometido por una instalación de DVWA, no es responsabilidad nuestra, sino de la persona o personas que lo subieron e instalaron.

- - -

## Licencia

Este archivo es parte de Damn Vulnerable Web Application (DVWA).

Damn Vulnerable Web Application (DVWA) es software libre: puede redistribuirlo y/o modificarlo bajo los términos de la Licencia Pública General GNU publicada por la Free Software Foundation, ya sea la versión 3 de la Licencia, o (a su elección) cualquier versión posterior.

Damn Vulnerable Web Application (DVWA) se distribuye con la esperanza de que sea útil, pero SIN NINGUNA GARANTÍA; ni siquiera la garantía implícita de
COMERCIABILIDAD o IDONEIDAD PARA UN PROPÓSITO PARTICULAR. Consulte la Licencia Pública General GNU para más detalles.

Debería haber recibido una copia de la Licencia Pública General GNU junto con Damn Vulnerable Web Application (DVWA). Si no es así, consulte <https://www.gnu.org/licenses/>.

- - -

## Internacionalización

Este archivo está disponible en varios idiomas:
- Árabe: [العربية](README.ar.md)
- Chino: [简体中文](README.zh.md)
- Español: [Español](README.es.md)
- Francés: [Français](README.fr.md)
- Persa: [فارسی](README.fa.md)
- Turco: [Türkçe](README.tr.md)

Si desea contribuir con una traducción, envíe una PR (Pull Request). Tenga en cuenta, sin embargo, que esto no significa que sólo tiene que usar Google Translate y enviar el resultado de traducción de la herramienta, pues será rechazado. Envíe su versión traducida añadiendo un nuevo archivo 'README.xx.md' donde xx es el código de dos letras del idioma deseado (basado en [ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes)).

- - -

## Descarga

Aunque existen varias versiones de DVWA, la única versión con soporte es la última del repositorio oficial de GitHub. Usted puede clonarlo desde el repositorio:

```
git clone https://github.com/digininja/DVWA.git
```

O [descargar un ZIP con todos los archivos](https://github.com/digininja/DVWA/archive/master.zip).

- - -

## Instalación

### Videos de Instalación

- [Instalando DVWA en Kali corriendo en VirtualBox](https://www.youtube.com/watch?v=WkyDxNJkgQ4)
- [Instalación de DVWA en Windows usando XAMPP](https://youtu.be/Yzksa_WjnY0)
- [Instalación de Damn Vulnerable Web Application (DVWA) en Windows 10](https://www.youtube.com/watch?v=cak2lQvBRAo)

### Windows + XAMPP

La forma más fácil de instalar DVWA es descargar e instalar [XAMPP](https://www.apachefriends.org/) si aún no tiene un servidor web configurado.

XAMPP es una distribución de Apache muy fácil de instalar para Linux, Solaris, Windows y Mac OS X. El paquete incluye el servidor web Apache, MySQL, PHP, Perl, un servidor FTP y phpMyAdmin.

Este [video](https://youtu.be/Yzksa_WjnY0) le guiará a través del proceso de instalación para Windows, pero debería ser similar para otros sistemas operativos.

### Archivo de configuración

DVWA se entrega con una plantilla del archivo de configuración que tendrá que copiar en su lugar y luego hacer los cambios apropiados. En Linux, suponiendo que se encuentra en el directorio DVWA, esto se puede hacer de la siguiente manera:

```bash
cp config/config.inc.php.dist config/config.inc.php
```

En Windows, esto puede ser un poco más difícil si está ocultando las extensiones de archivo, si no está seguro acerca de esto, esta publicación de blog explica más sobre eso:

[Cómo hacer que Windows muestre las extensiones de archivo](https://www.howtogeek.com/205086/beginner-how-to-make-windows-show-file-extensions/)

### Paquetes Linux

Si utiliza una distribución de Linux basada en Debian, necesitará instalar los siguientes paquetes _(o sus equivalentes)_:

- apache2
- libapache2-mod-php
- mariadb-server
- mariadb-client
- php php-mysqli
- php-gd

Se recomienda hacer una actualización antes de esto, sólo para asegurarse de que va a obtener la última versión de todos los paquetes.

```
apt update
apt install -y apache2 mariadb-server mariadb-client php php-mysqli php-gd libapache2-mod-php
```

El sitio funcionará con MySQL en lugar de MariaDB, pero recomendamos MariaDB, ya que funciona con su instalación por defecto y sin cambio alguno, mientras que usted tendrá que hacer cambios para hacer que para MySQL funcione correctamente.

### Configuración de la base de datos

Para configurar la base de datos, simplemente haga clic en el botón `Setup DVWA` en el menú principal, a continuación, haga clic en el botón `Create / Reset Database`. Esto creará / reiniciará la base de datos e insertará algunos datos de ejemplo.

Si recibe un error al intentar crear su base de datos, asegúrese de que sus credenciales de la base de datos dentro de `./config/config.inc.php` están correctamente escritas.  *Esto difiere de config.inc.php.dist, que es un archivo de ejemplo.*

Las variables son las siguientes por defecto:

```php
$_DVWA[ 'db_server'] = '127.0.0.1';
$_DVWA[ 'db_port'] = '3306';
$_DVWA[ 'db_user' ] = 'dvwa';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';
$_DVWA[ 'db_database' ] = 'dvwa';
```

Nota, si está usando MariaDB en lugar de MySQL (MariaDB viene por defecto en Kali), entonces no podrá usar el usuario root de la base de datos, por tanto, debe crear un nuevo usuario de base de datos. Para hacer esto, debe conectarse a la base de datos como usuario root y usar los siguientes comandos:

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

### Desactivar Autenticación

Algunas herramientas no funcionan bien con mecanismos de autenticación, por lo que no se pueden utilizar con DVWA si la autenticación está habilitada. Para resolver esto, existe una opción de configuración para desactivar la verificación de autenticación. Para ello, simplemente establezca lo siguiente en el archivo de configuración:

```php
$_DVWA[ 'disable_authentication' ] = true;
```

También tendrá que establecer el nivel de seguridad a uno que sea apropiado para las pruebas que desea hacer:

```php
$_DVWA[ 'default_security_level' ] = 'low';
```

En este estado, puede acceder a todas las funciones sin necesidad de iniciar sesión y tampoco tener que configurar cookies.

### Otras Configuraciones

Dependiendo de su sistema operativo, así como la versión de PHP, es posible que desee modificar la configuración por defecto. La ubicación de los archivos será diferente para cada máquina.

**Permisos de carpeta**:

* `./hackable/uploads/` - El servicio web necesita tener permisos de escritura en esta carpeta (para la subida de archivos).
* `./external/phpids/0.6/lib/IDS/tmp/phpids_log.txt` - El servicio web necesita tener permisos de escritura en esta carpeta (si desea usar PHPIDS).

**Configuración de PHP**:
* Para permitir la inclusión remota de archivos (RFI):
    * `allow_url_include = on` [[allow_url_include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
    * `allow_url_fopen = on` [[allow_url_fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]
* Para reducir opcionalmente la verbosidad ocultando los mensajes de advertencia de PHP:
    * `display_errors = off` [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]

**Archivo: `config/config.inc.php`**:

* `$_DVWA[ 'recaptcha_public_key' ]` & `$_DVWA[ 'recaptcha_private_key' ]` - Estos valores deben ser generados desde: https://www.google.com/recaptcha/admin/create

### Credenciales por defecto

**Nombre de usuario por defecto = `admin`**

**Contraseña por defecto = `password`**

_...puede ser fácilmente crackeada con fuerza bruta ;)_

URL de Acceso: http://127.0.0.1/login.php

Nota: La URL de acceso será diferente si ha instalado DVWA en un directorio distinto.

- - -

## Contenedor Docker

Esta sección del readme ha sido añadida por @thegrims, para soporte en temas Docker, por favor contactar con él o con @opsxcq que es quien mantiene la imagen Docker y el repositorio. Cualquier ticket de incidencia será probablemente referenciado a esto y cerrado.

- [Página DockerHub](https://hub.docker.com/r/vulnerables/web-dvwa/)
`docker run --rm -it -p 80:80 vulnerables/web-dvwa`

Por favor, asegúrese de que está utilizando aufs debido a problemas anteriores con MySQL. Ejecute `docker info` para comprobar su controlador de almacenamiento. Si no es aufs, por favor cámbielo. Hay guías para cada sistema operativo sobre cómo hacerlo, pero son bastante diferentes por lo que no lo cubriremos aquí.

- - -

## Solución de problemas

Esta sección supone que está usando una distribución basada en Debian, como Debian, Ubuntu y Kali. Para otras distribuciones, siga el mismo procedimiento, pero actualice el comando donde corresponda.

### He navegado hasta el sitio web y he obtenido un Error 404

Si está teniendo este problema, necesita entender la ubicación correcta de los archivos. Por defecto, el directorio raíz de los documentos de Apache (el lugar donde empieza a buscar contenido web) es `/var/www/html`. Si coloca el archivo `hello.txt` en este directorio, para acceder a él deberá navegar a `http://localhost/hello.txt`.

Si crea un directorio y pone el archivo allí - `/var/www/html/mydir/hello.txt` - tendrá que navegar a `http://localhost/mydir/hello.txt`.

Linux distingue por defecto entre mayúsculas y minúsculas, por lo que en el ejemplo anterior, si intentara navegar a cualquiera de estos sitios, obtendría un mensaje `404 Not Found`:

- http://localhost/MyDir/hello.txt
- http://localhost/mydir/Hello.txt
- http://localhost/MYDIR/hello.txt

¿Cómo afecta esto al DVWA? La mayoría de la gente utiliza git para obtener el DVWA en `/var/www/html`, esto les da el directorio `/var/www/html/DVWA/` con todos los archivos DVWA dentro de él. Entonces navegan a `http://localhost/` y obtienen un `404` o la página de bienvenida por defecto de Apache. Como los archivos están en DVWA, debe navegar a `http://localhost/DVWA`.

Otro error común es navegar a `http://localhost/dvwa` que dará un `404` porque `dvwa` no es `DVWA` en lo que se refiere a la correspondencia de directorios de Linux.

Así que después de la instalación, si intenta visitar el sitio y obtiene un `404`, piense dónde instaló los archivos, dónde están en relación con el directorio raíz de documentos, y recuerde si utilizó mayúsculas o minúsculas en ese directorio.

### "Access denied" ejecutando setup

Si ve lo siguiente al ejecutar el script de instalación significa que el nombre de usuario o la contraseña en el archivo de configuración no coinciden con los configurados en la base de datos:

```
Database Error #1045: Access denied for user 'notdvwa'@'localhost' (using password: YES).
```

El error le está diciendo que está usando el nombre de usuario `notdvwa`.

El siguiente error indica que en el archivo de configuración ha escrito un nombre de base de datos equivocado.

```
SQL: Access denied for user 'dvwa'@'localhost' to database 'notdvwa'
```

Está diciendo que está usando el usuario `dvwa` y tratando de conectarte a la base de datos `notdvwa`.

Lo primero que hay que hacer es comprobar que lo que se cree que ha puesto en el fichero de configuración es realmente lo que está ahí.

Si coincide con lo que se espera, lo siguiente es comprobar que se puede iniciar sesión como el usuario en cuestión a través de la línea de comandos. Asumiendo que tiene un usuario de base de datos `dvwa` y una contraseña `p@ssw0rd`, ejecute el siguiente comando:

```
mysql -u dvwa -pp@ssw0rd -D dvwa
```

*Nota: No hay espacio después de -p*

Si ve lo siguiente, la contraseña es correcta:

```
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 14
Server version: 10.3.22-MariaDB-0ubuntu0.19.10.1 Ubuntu 19.10

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [dvwa]>
```

Como puede conectarse en la línea de comandos, es probable que haya algo mal en el archivo de configuración, compruebe dos veces y luego plantee un Issue si todavía no puede hacer que las cosas funcionen.

Si ve lo siguiente, el nombre de usuario o la contraseña que está utilizando son incorrectos. Repita los pasos de [Database Setup](#database-setup) y asegúrese de usar el mismo nombre de usuario y contraseña durante todo el proceso.

```
ERROR 1045 (28000): Access denied for user 'dvwa'@'localhost' (using password: YES)
```

Si obtiene lo siguiente, las credenciales del usuario son correctas pero el usuario no tiene acceso a la base de datos. De nuevo, repita los pasos de configuración y compruebe el nombre de la base de datos que está utilizando.

```
ERROR 1044 (42000): Access denied for user 'dvwa'@'localhost' to database 'dvwa'
```

El último error que puede obtener es el siguiente:

```
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
```

Esto no es un problema de autenticación, sino que indica que el servidor de base de datos no se está ejecutando. Puede iniciar el servidor con lo siguiente:

```sh
sudo service mysql start
```

### Método de autenticación desconocido

Con las versiones más recientes de MySQL, PHP ya no puede comunicarse con la base de datos en su configuración por defecto. Si intenta ejecutar el script de instalación y obtiene el siguiente mensaje significa que tiene la configuración por defecto.

```
Database Error #2054: The server requested authentication method unknown to the client.
```

Tiene dos opciones, la más fácil es desinstalar MySQL e instalar MariaDB. La siguiente es la guía oficial del proyecto MariaDB:

<https://mariadb.com/resources/blog/how-to-migrate-from-mysql-to-mariadb-on-linux-in-five-steps/>

Alternativamente, siga estos pasos:

1. Como root, edite el siguiente archivo `/etc/mysql/mysql.conf.d/mysqld.cnf`.
2. Bajo la línea `[mysqld]`, añada lo siguiente:
  `default-authentication-plugin=mysql_native_password`.
3. Reinicie el servidor de base de datos: `sudo service mysql restart`
4. Compruebe el método de autenticación del usuario de la base de datos:

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------------------+-----------------------+
    | Host      | User             | plugin                |
    +-----------+------------------+-----------------------+
    | localhost | dvwa             | caching_sha2_password |
    +-----------+------------------+-----------------------+
    1 rows in set (0.00 sec)
    ```

5. Es probable que vea `caching_sha2_password`. Si es así, ejecute el siguiente comando:

    ```sql
    mysql> ALTER USER dvwa@localhost IDENTIFIED WITH mysql_native_password BY 'p@ssw0rd';
    ```

6. Al volver a ejecutar la verificación, ahora debería ver `mysql_native_password`.

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------+-----------------------+
    | Host      | User | plugin                |
    +-----------+------+-----------------------+
    | localhost | dvwa | mysql_native_password |
    +-----------+------+-----------------------+
    1 row in set (0.00 sec)
    ```

Después de todo esto, el proceso de configuración debería funcionar con normalidad.

Si desea más información consulte la siguiente página: <https://www.php.net/manual/en/mysqli.requirements.php>.

### Database Error #2002: No such file or directory.

El servidor de base de datos no se está ejecutando. En una distro basada en Debian esto se puede hacer con:

```sh
sudo service mysql start
```

### Errores "MySQL server has gone away" y "Packets out of order"

Hay algunas razones por las que podría estar obteniendo estos errores, pero la más probable es que la versión del servidor de base de datos que está ejecutando no sea compatible con la versión de PHP.

Esto se encuentra de forma más común cuando se está ejecutando la última versión de MySQL y PHP, y estás no se llevan bien. El mejor consejo, deshágase de MySQL e instale MariaDB ya que esto no es algo con lo que podamos ayudarte.

Para más información, vea:

<https://www.ryadel.com/en/fix-mysql-server-gone-away-packets-order-similar-mysql-related-errors/>

### La Inyección de Comandos no funcionará

Es posible que Apache no tenga privilegios suficientes para ejecutar comandos en el servidor web. Si está ejecutando DVWA en Linux asegúrese de que ha iniciado sesión como root. Bajo Windows inicie sesión como Administrador.

### ¿Por qué no se puede conectar la base de datos en CentOS?

Puede estar teniendo problemas con SELinux.  Desactive SELinux o ejecute este comando para permitir que el servidor web se comunique con la base de datos:

```
setsebool -P httpd_can_network_connect_db 1
```

### Cualquier otra cosa

Para obtener la información más reciente sobre solución de problemas, lea los tickets abiertos y cerrados en el repositorio git:

<https://github.com/digininja/DVWA/issues>

Antes de enviar un ticket, por favor asegúrese de que está ejecutando la última versión del código del repositorio. No se trata de la última versión liberada (released), sino del último código disponible en la rama master.

Si desea enviar un ticket, por favor envíe al menos la siguiente información:

- Sistema operativo
- Las últimas 5 líneas del log de errores del servidor web justo después de que se produzca el error del que está informando.
- Si se trata de un problema de autenticación de base de datos, siga los pasos anteriores y haga una captura de pantalla de cada paso. Envíelas junto con una captura de pantalla de la sección del archivo de configuración que muestra el usuario y la contraseña de la base de datos.
- Una descripción completa de lo que está fallando, lo que espera que ocurra y lo que ha intentado hacer para solucionarlo. "inicio de sesión roto" no es suficiente para que entendamos su problema y le ayudemos a solucionarlo.

- - -

## Inyección SQL en SQLite3

_El soporte para esto es limitado, antes de abrir tickets en Issues, por favor asegúrese de que está preparado para trabajar en la depuración del problema, no se limite a decir "no funciona"._

Por defecto, SQLi y Blind SQLi se hacen contra el servidor MariaDB/MySQL utilizado por el sitio, pero es posible cambiar la configuración para hacer las pruebas SQLi contra SQLite3 en su lugar.

No se va a cubrir cómo hacer que SQLite3 funcione con PHP, pero debería ser un simple caso de instalar el paquete `php-sqlite3` y asegurarse de que está habilitado.

Para hacer el cambio, simplemente edite el archivo de configuración y añada o edite estas líneas:

```
$_DVWA["SQLI_DB"] = "sqlite";
$_DVWA["SQLITE_DB"] = "sqli.db";
```

Por defecto se utiliza el fichero `database/sqli.db`, si lo estropea al archivo por error, simplemente copiar el archivo `database/sqli.db.dist` y sobreescribir el existente estropeado.

Los retos son exactamente los mismos que para MySQL, sólo que se ejecutan contra SQLite3 en su lugar.

- - -

👨‍💻 Contribuyentes
-----

Gracias por todas sus contribuciones y por mantener este proyecto actualizado. :heart:

Si tienes una idea, algún tipo de mejora o simplemente quieres colaborar, eres bienvenido de contribuir y participar en el proyecto, siéntete libre de enviar tu PR.

<p align="center">
<a href="https://github.com/digininja/DVWA/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=digininja/DVWA&max=500">
</a>
</p>

- - -

## Enlaces

Inicio del proyecto: <https://github.com/digininja/DVWA>

*Creado por el Equipo de DVWA*

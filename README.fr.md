# Translation / Traduction

Philibert Gentil：@[Philibert-Gentil](https://github.com/Philibert-Gentil)
Vous pouvez me contacter en cas d'erreur de traduction / d'interprétation.

- - -

# DAMN VULNERABLE WEB APPLICATION / BORDEL D'APPLICATION WEB VULNÉRABLE

Bordel d'application web vulnérable (BAWV, traduit DVWA) est une application web PHP/MySQL vulnérable. Son but principal est d'être une aide pour les experts en sécurité pour tester leurs compétences et outils dans un environnement légal, aider les développeurs web à mieux comprendre la sécurisation des applications web et d'aider les élèves et professeurs à apprendre la sécurité des applications web dans un environnement d'études.

L'objectif de BAWV est **d'expérimenter les vulnérabilités web les plus communes**, avec **différents niveaux de difficulté**, avec une interface intuitive.
Notez qu'il existe des **vulnérabilités documentées ou non** avec ce programme. C'est intentionnel. Vous êtes encourragés à essayer et découvrir autant de failles que possible.
- - -

## ATTENTION !

Bordel D'application web vulnérable est vachement vulnérable ! **Ne la publiez pas sur le dossier html public de votre hébergeur ni aucun serveur visible sur internet**, ou ils seront compromis. Il est recommendé d'utiliser une machine virtuelle (comme [VirtualBox](https://www.virtualbox.org/) ou [VMware](https://www.vmware.com/)), réglé sur le mode réseau NAT. Dans une machine invitée, vous pouvez télécharger et installer [XAMPP](https://www.apachefriends.org/) pour le serveur web et la base de données.

### Non-responsabilité

Nous ne sommes pas responsables de la manière dont vous utilisez BAWV. Nous avons clairement défini les objectifs de l'application et elle ne dois pas être utilisée de manière malveillante. Nous vous avons averti et avons pris les mesures nécessaires pour informer les utilisateurs de BAWV à propos de son installation sur un serveur. Si votre serveur est compromis à cause d'une installation de BAWV, il n'en découle pas de notre responsabilité, mais de celle de la/les personne(s) qui l'a/ont téléchargé ou installé, envoyé.
- - -

## License

Ce fichier fait parie du bordel d'application web vulnérable (BAWV)

Bordel d'application web vulnérable (BAWV) est un logiciel libre: vous pouvez le re-distribuer et/ou le modifier en respectant les termes de la licence publique générale GNU (GNU General Public License) tel que publié par
La fondation des logiciels libres (the Free Software Foundation),
soit la troisième version de la licence, soit une version ultérieure.

Bordel d'application web vulnérable (BAWV) est distribué dans l'espoir qu'il vous sera utile,
mais SANS GARANTIE; sans même la garantie implicite de qualité professionnelle ou particulière.
Voyez la license publique générale GNU pour plus de détails.

Vous devriez avoir reçu une copie de la license publique générale GNU
en même temps que le bordel d'application web vulnérable (BAVW). Sinon, consultez <https://www.gnu.org/licenses/>.
- - -

## Internationalisation

Ce fichier est disponibles dans diverses langues ci-dessous :
- Chinois: [简体中文](README.zh.md)
- Turque: [Türkçe](README.tr.md)
- Anglais: [English](README.md)

Si vous souhaitez contribuer à la traduction, faite une demande d'extraction (pull request, PR). Par contre, ça ne doit pas être juste du Google Trad, ou ce sera rejeté.

- - -

## Téléchargement

Même s'il y a diverses versions de BAVW, la seule version soutenue via cette source du dépôt GitHub est celle-ci. Vous pouvez la cloner depuis le dépôt suivant :

```
git clone https://github.com/digininja/DVWA.git
```

Ou [télécharger le fichier zippé](https://github.com/digininja/DVWA/archive/master.zip).
- - -

## Installation

**Soyez sûrs que le fichier config/config.inc.php existe. Avoir le fichier config.inc.php.dist ne suffira pas, vous devrez le modifier par rapport à votre environnement et le renommer config.inc.php. [Windows cachera peut-être l'extension](https://lecrabeinfo.net/afficher-extensions-noms-de-fichiers-dans-windows.html)**

## Vidéos d'installation
- (en anglais) [Installing DVWA on Kali running in VirtualBox](https://www.youtube.com/watch?v=WkyDxNJkgQ4)
- (En anglais) [Installing Damn Vulnerable Web Application (DVWA) on Windows 10](https://www.youtube.com/watch?v=cak2lQvBRAo) \[12:39 minutes\]

### Win + XAMPP

La solution la plus facile pour installer BAVW est de télécharger et d'installer [XAMPP](https://www.apachefriends.org/) si vous n'avez pas déjà de serveur web.

XAMPP est une distribution apache pour Linux, Solaris, Windows et MacOS très facile d'installation. Le paquet inclut le serveur web apache, MySQL, PHP, Perl, un serveur FTP et phpMyAdmin.

XAMPP peut être téléchargé depuis :
<https://www.apachefriends.org/>

Dézippez simplement dvwa.zip, placez le fichier décompressé dans votre fichier HTML public, puis allez avec votre navigateur sur `http://localhost/dvwa/setup.php`

### Paquets Linux

Si vous utilisez une distribution basée sur Debian (Debian, ubuntu, kali, parrot, Rapberry pi OS etc), vous devez installer les paquets suivants _(ou leurs équivalents)_:

- apache2
- libapache2-mod-php
- mariadb-server
- mariadb-client
- php
- php-mysql
- php-gd
- php-mbstring

La commande suivante vous permet de les installer.

`apt install apache2 mariadb-server php php-mysqli php-gd php-mbstring libapache2-mod-php`

Note: php-mbstring permet de gérer les caractères européens, arabes, caligraphiques et caetera, notamment la table de caractères UTF-8. Ne pas la télécharger pourrait entrainez des problèmes d'affichage si vous n'avez pas un langage germanique comme l'anglais ou l'allemand.

Le site fonctionnera avec MySQL à la place de MariaDB mais nous recommendons fortement MariaDB car cela fonctionne directement, contrairement à MySQL que vous devrez modifier.

### Paramétrage de la base de données

Pour créer une base de données (BDD), cliquez simplement sur le bouton `Setup DVWA` (configurer BAWV) dans le menu principal puis cliquez sur le bouton `Create / Reset Database` (créez / réinitialisez la BDD). Cela créera / réinitialisera la BDD pour vous avec des données dedans.

Si vous rencontrez une erreur en essayant de créer la BDD, soyez sûrs que les identifiants de la BDD soient corrects dans `./config/config.inc.php`. *Elles diffèrent de config.inc.php.dist, qui est un fichier bateau*.

Les variables sont définies comme ceci par défaut:

```php
$_DVWA[ 'db_server'] = '127.0.0.1'; //l'IP du serveur
$_DVWA[ 'db_port'] = '3306'; //Le port pour accéder à la BDD
$_DVWA[ 'db_user' ] = 'dvwa'; //L'utilisateur de la BDD
$_DVWA[ 'db_password' ] = 'p@ssw0rd'; //Le mdp
$_DVWA[ 'db_database' ] = 'dvwa'; //Le nom de la BDD
```

PS: si vous utilisez MariaDB plutôt que MySQL (MariaDB est là par défaut sur Kali), vous ne pouvez utilisez la BDD en tant que root, vous devez créer un nouvel utilisateur. Pour faire cela, connectez vous à la BDD en tant que super-administrateur (root) dans un terminal et tapez les commandes suivantes:

```mysql
mysql> create database dvwa; //On crée la BDD
Query OK, 1 row affected (0.00 sec)

mysql> create user dvwa@localhost identified by 'p@ssw0rd'; //On crée l'utilisateur
Query OK, 0 rows affected (0.01 sec)

mysql> grant all on dvwa.* to dvwa@localhost;//On lui donne toute les permissions dans la BDD dvwa
Query OK, 0 rows affected (0.01 sec)

mysql> flush privileges;//On actualise les privilèges (en gros)
Query OK, 0 rows affected (0.00 sec)
```

### Autres configurations

En fonction de votre système d'exploitaiton (SE), tout comme la version de PHP, vous devrez peut-être modifier la configuration initiale. La localisation des fichiers sera éventuellement différente, selon votre machine.

**Permissions du fichier**

* `./hackable/uploads/` - Doit être disponible en écriture par le serveur web (des fichiers y seront uploadés).
* `./external/phpids/0.6/lib/IDS/tmp/phpids_log.txt` - Doit être disponible en écriture par le serveur web (si vous voulez utiliser les PHPIDS).

**Configuration PHP**

* `allow_url_include = on` - Autorise l'utilisation du gestionnaire des URL par certaines fonctions (RFI)   [[allow_url_include](https://secure.php.net/manual/fr/filesystem.configuration.php#ini.allow-url-include)]
* `allow_url_fopen = on` -  Autorisation pour l'accès au fichiers (RFI)    [[allow_url_fopen](https://secure.php.net/manual/fr/filesystem.configuration.php#ini.allow-url-fopen)]
* `safe_mode = off` - (Si PHP <= v5.4) Autorise l'injection SQL (SQLi) [[safe_mode](https://secure.php.net/manual/fr/features.safe-mode.php)]
* `magic_quotes_gpc = off` - (Si PHP <= v5.4) Autorise l'injection SQL (SQLi) [[magic_quotes_gpc](https://secure.php.net/manual/fr/security.magicquotes.php)]
* `display_errors = off` - (Optional) Cache les messages d'avertissement PHP [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]

**Fichier: `config/config.inc.php`**:

* `$_DVWA[ 'recaptcha_public_key' ]` & `$_DVWA[ 'recaptcha_private_key' ]` - Ces valeurs doivent être générées depuis: https://www.google.com/recaptcha/admin/create

### Identifiants par défaut

**Identifiant par défaut = `admin`**

**Clef par défaut = `password`**

_... peut être facilement craqué ;)_
URL de connection: http://127.0.0.1/login.php
_PS: Ce sera différent si vous installez BAWV dans un autre fichier._
- - -

## Container Docker
_Cette section du fichier à été ajouté par @thegrims, pour de l'aide à propos d'erreurs docker, veuillez le contacter ou contactez @opsxcq, qui est le maître du dépôt et de l'image docker. Un signalement d'erreur lui sera sûrement adressé et celle-ci sera collematée_

- [page dockerhub](https://hub.docker.com/r/vulnerables/web-dvwa/)
`docker run --rm -it -p 80:80 vulnerables/web-dvwa`

Soyez sûrs d'utiliser AUFS à cause d'erreurs antérieurs de MySQL. Lancez `docker info` pour vérifier le stockage de votre lecteur. Si il n'est pas en AUFS, veuillez le changer. Il y a un manuel pour chaque SE (OS), mais il sont tellement différents que nous n'aborderons pas ce sujet.

### Construction locale

Si vous avez fais des changements et voulez construire le projet à partir de votre version locale, editez le fichier `compose.yml` et changez `pull_policy: always` par `pull_policy: build`.

Exécuter la commande `docker compose up -d` va déclancher Docker à construire une image à partir de votre version locale, sans regard sur ce qui est disponible dans le registre.

Pour plus d'informations (En anglais): [`pull_policy`](https://github.com/compose-spec/compose-spec/blob/master/05-services.md#pull_policy).

### Servir les fichiers locaux

Si vous faites des changements et ne voulez pas avoir à reconstruire l'image après chaque changement :

1. Éditer le fichier `compose.yml` et décommenter :
    ```
        # volumes:
        #   - ./:/var/www/html
    ```
2. Exécuter `cp config/config.inc.php.dist config/config.inc.php` pour copier le fichier de configuration par défaut.
3. Exécuter `docker compose up -d` et les changements au fichiers locaux seront réfléchies sur le conteneur.

- - -

## Dépannage

Nous considérons que vous êtes sur une distribution basée sur Debian, comme Debian, Ubuntu, Kali ou Raspberry pi OS. Pour les autres distributions, suivez les instructions en adaptant les commandes à votre distribution.

### Le site me donne une erreur 404

Si vous avez ce problème, vous devez comprendre la localistaion des fichiers. Par défaut, le fichier racine apache (l'endroit où il cherche le contenu du site) est `/var/www/html`. Si vous mettez un fichier nommé `salut.txt` dans ce dossier, vous devrez, pour y accéder, noter `http://localhost/salut.txt`.

Si vous créer un dossier et que vous y mettez un fichier - `/var/www/html/mondossier/salut.txt` - vous devrez écrire `http://localhost/mondossier/salut.txt`.

Linux est sensible à la casse (par exemps, "é" n'est pas la même lettre que "e"; et "E" n'est pas lettre que "e"), donc vous pourriez tomber sur un 404 si vous n'y prenez pas garde.
Les URL suivantes vous donneront une erreur 404 :
- `http://localhost/MonDossier/salut.txt`
- `http://localhost/mondossier/Salut.txt`
- `http://localhost/MONDOSSIER/salut.txt`

Pourquoi cela affecte BAWV ? La plupart des gens utilisent Git intégrer BAWV dans leur répertoire `/var/www/html`, cela leur donne donc le chemin `/var/www/html/DVWA` avec tous les fichiers de BAWV dedans. Du coup si vous cherchez `http://localhost/` ça vous retourne une erreur 404, ou la page par défaut d'apache. Comme les fichiers sont dans le dossier DVWA, ous devez rechercher `http://localhost/DVWA/`.

L'autre erreur commune est de rechercher `http://localhost/dvwa` ce qui retourne une erreur 404 parce que `dvwa` n'est pas pareil que `DVWA`, à cause de la casse.

Après la configuration, si vous obtenez une erreur 404, pensez à là où vous avez enregistré vos fichiers, qui est accessible par un chemin relatif, et quelle est l'orthographe des dossiers dans lesquels il se situe.

### "Accès refusé" pendant la configuration

Si vous avez l'erreur suivante en exécutant le programme de configuration, cela veut dire que l'ID et la clef que vous avez défini ne correspond pas à celle de la BDD.

```
Database Error #1045: Access denied for user 'nimporte'@'localhost' (using password: YES).
//signifie: "Erreur de la BDD #1045: accès refusé pour l'utilisateur 'nimporte'@'localhost' (utilise un mdp: OUI)"
```

Cette erreur signifie que vous utilisez le nom d'utilisateur `nimporte`.

L'erreur suivante dit que vous demandez une mauvaise base de donnée.

```
SQL: Access denied for user 'dvwa'@'localhost' to database 'notdvwa'
//signifie : "SQL: Accès refusé pour l'utilisateur 'dvwa'@'localhost' à la BDD `nimporte`."
```

Ça dit que vous vous connectez en tant que `dvwa` et que vous essayez de vous connecter à la BDD `nimporte`.

La première chose à faire est de revérifier ce que vous avez renseigné dans le fichier de configuration.

Si les informations semblent être correctes, la chose à revérifier est de regarder les journaux systèmes à propos de l'utilisateur en ligne de commande. Considérons que vous avez une BDD dénominée `dvwa` et un mot de passe `p@ssw0rd`, lancez la commande suivante.

```
mysql -u dvwa -p -D dvwa
//puis tapez votre mot de passe dans le champ qui apparaît
```

Si le texte suivant apparaît (peut varier), les identifiants sont corrects:
```
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 14
Server version: 10.3.22-MariaDB-0ubuntu0.19.10.1 Ubuntu 19.10

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [dvwa]>
```
Puisque vous pouvez vous connecter en ligne de commande, il y a quelque chose qui cloche dans le fichier de configuration, re-vérifiez-le et signaler nus une erreur si vous n'arrivez pas à la trouver (sur github).

Si le texte qui est apparu est le suivant, les identifiants sont incorrects. Répétez la [configuration de la base de données](#Paramétrage de la base de données) et soyez sûrs d'utiliser toujours les même identifiants durant la procédure.
```
ERROR 1045 (28000): Access denied for user 'dvwa'@'localhost' (using password: YES)
//signifie: "Erreur 1045 (28000): Accès refusé pour l'utilisateur 'dvwa'@'localhost' (clé renseignée: OUI)"
```

Si vous obtenez l'erreur suivante, les identifiants sont corrects mais l'utilisateur n'a pas accès à la database.
Réitérez aussi le paramétrage de la base de données et vérifiez le nom de la base de données.
```
ERROR 1044 (42000): Access denied for user 'dvwa'@'localhost' to database 'dvwa'
//signifie: "Erreur 1044 (42000): Accès refusé pour l'utilisateur 'dvwa'@'localhost' à la BDD 'dvwa'."
```

La dèrnière erreur peut être celle-ci:
```
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
```
Ça n'est pas une erreur d'authentification, c'est juste que le système de gestion de la BDD n'est pas activée. Démarrez-le en tapant :
```sh
sudo service mysql start
```

### Méthode d'identification inconnue

Avec les versions les plus récentes de MySQL, PHP ne peut plus échanger avec la BDD dans sa configuration initiale. Si vous obtenez cette erreur, c'est que vous possédez cette configuration :(.
```
Database Error #2054: The server requested authentication method unknown to the client.
//Signification: "Erreur de BDD  #2045: la méthode authentification utilisée est inconnue."
```

Vous avez deux options, la première étant de désinstaller MySQL et d'installez MariaDB. Ce lien vous envoie vers le manuel officiel de MariaDB.
<https://mariadb.com/resources/blog/how-to-migrate-from-mysql-to-mariadb-on-linux-in-five-steps/> (en anglais)

Sinon, suivez ces étapes:

1. En tant que root, éditez le fichier `/etc/mysql/mysql.conf.d/mysqld.cnf`
1. sous la ligne `[mysqld]`, ajoutez
   `default-authentication-plugin=mysql_native_password`
1. redémarrez MySQL: `sudo service mysql restart`
1. Vérifiez le méthode de connexion pour votre utilisateur:
   ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------------------+-----------------------+
    | Host      | User             | plugin                |
    +-----------+------------------+-----------------------+
    | localhost | dvwa             | caching_sha2_password |
    +-----------+------------------+-----------------------+
    1 rows in set (0.00 sec)
    ```
1. Vous verrez probablement `caching_sha2_password`. Si c'est le cas, tapez:
   ```sql
    mysql> ALTER USER dvwa@localhost IDENTIFIED WITH mysql_native_password BY 'p@ssw0rd';
    ```
1. Relancez la vérification, vous devriez voir `mysql_native_password`.
    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------+-----------------------+
    | Host      | User | plugin                |
    +-----------+------+-----------------------+
    | localhost | dvwa | mysql_native_password |
    +-----------+------+-----------------------+
    1 row in set (0.00 sec)
    ```
Après tout ça, le processus de configuration devrait fonctionner.
Pour plus d'infos, voyez: <https://www.php.net/manual/fr/mysqli.requirements.php>.

### Database Error #2002: No such file or directory.

Le serveur de BDD est inactif. Sur une distro basée Debian, tapez:
```sh
sudo service mysql start
```
### Erreurs "MySQL server has gone away" et "Packets out of order"

Vous pourriez rencontrer cette erreur pour maintes raisons, mais la plus plausible est que la version de votre SGBDD est incompatible avec PHP.
Cela ce produit généralement quand vous utilisez la dernière version de MySQL, mais pas de PHP et que ça ne foncitonne pas oufement bien. Notre meilleur conseil est de désinstaller MySQL et d'installer MariaDB, sinon nous ne pouvons pas vous aider.

### L'injection de commande ne fonctionne pas

Apache n'a peut être pas assez de privilèges sur le serveur web. Si vous utilisez BAWV sur linux, veillez à être connecté en tant que root et sous windows, en tant qu'administrateur.

### Pourquoi ne puis-je pas me connecter à ma BDD sous CentOS

Vous avez sûrement des prolèmes avec SELinux. Désinstaller SELinux ou lancez cette commande pour autoriser le serveur web à discutter avec la base de donnée:
```
setsebool -P httpd_can_network_connect_db 1
```

### Autre chose

Si vous avez besoin d'aide, lisez les rapports d'erreurs ouvert et/ou fermés dans le dépôt git:
<https://github.com/digininja/DVWA/issues>

Avant d'envoyer un rapport, soyez-sûr que vous utilisez la dernière version du code du dépôt. Pas que la dernière version, mais aussi les derniers codes de la branche maîtresse (master).

Si vous envoyez un rapport, renseignez ces informations:
- Système d'exploitation
- Les cinq dernières lignes du journal (log) du serveur web juste après la déclaration de votre erreur
- Si c'est un problème de connection à la base de données, effectuées les étapes renseignées au dessus et faites une capture d'écran de chacune d'entre elles, et du fichier de configuration contenant vos identifiants.
- une description détaillée de ce qui ne va pas, ce que vous éspèreriez qu'il arrive, et comment vous avez essayé de résoudre le problème. "problème de connection" n'est pas assez détaillé pour nous aidez à résoudre votre problème.
- - -

## Injection SQL SQLite3

_Le support pour cette section est limitée, avant d'envoyer un rapport d'erreur, soyez préparé à faire un déboguage, ne déclarez pas juste "ça marche pas !"._

Par défaut, SQLi et Blind SQLi sont exécutés sur les serveurs MariaDB/MySQL utilisés par le site mais il est possible de basculer vers des tests SQLi sur SQLite3 à la place.

Je ne vais pas vous montrer comment fonctionne SQLite3 avec PHP, mais juste un exemple d'installation de `php-sqlite3` et d'être sûr qu'il soit actif.
Pour faire ce changement éditez le fichier de configuration et éditez ces lignes:

```
$_DVWA["SQLI_DB"] = "sqlite";
$_DVWA["SQLITE_DB"] = "sqli.db";
```
Par défaut, ça utilise le fichier `database/sqli.db`, si vous vous gourrez, copiez `database/sqli.db.dist` par dessus.

Le challenge est le même pour MySQL, il sont juste antagoniste à SQLite3.
- - -

## Liens

Dépôt GitHub: <https://github.com/digininja/DVWA>

Créé par l'équipe BAWV.

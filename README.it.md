# DAMN VULNERABLE WEB APPLICATION

Damn Vulnerable Web Application (DVWA) è un'applicazione web PHP/MariaDB che è dannatamente vulnerabile. Il suo principale obiettivo è: essere d'aiuto a professionisti di sicurezza informatica per testare le loro skill e i loro strumenti in un ambiente legale,aiutare i web developers a mettere in sicurezza le web applications e aiutare sia studenti che docenti a capire la sicurezza delle web applications in un'ambiente controllato.

L'obiettivo di DVWA è **praticare alcune tra le più comuni vulnerabilità web** con **vari livelli di difficoltà**e con una semplice e diretta interfaccia. È opportuno notare che sono presenti sia **vulnerabilità documentate e non documentate** con questo software. Questo è stato fatto intenzionalmente. Si è pregati di provare a scoprire più vulnerabilità possibili.
- - -

## ATTENZIONE!

Damn Vulnerable Web Application è dannatamente vulnerabile! **Non caricarla nella cartella contentente i file html del tuo provider di hosting né su alcun server accessibile da Internet** perché saranno compromessi. È raccomandato utilizzare una macchina virtuale (ad esempio [VirtualBox](https://www.virtualbox.org/) oppure [VMware](https://www.vmware.com/)), che sia settata su NAT modalità networking. Dentro una macchina guest, bisogna scaricare ed installare [XAMPP](https://www.apachefriends.org/) per il server web ed il database.

### Disclaimer

Non ci assumiamo la responsabilità del modo in cui verrà utilizzata questa applicazione (DVWA). L'obiettivo di questa applicazione è stato dichiarato in modo chiaro e non dovrebbe essere usata per altri scopi. Abbiamo avvisato e dato misure di sicurezza per fare in modo che gli utenti non installino DVWA nei loro live web servers. Se il tuo live web server è compromesso da un'installazione di DVWA, non è nostra responsabilità, è responsabilità della persona che ha caricato e installato il software.

---

## Licensa

Questo file è parte di Damn Vulnerable Web Application (DVWA).

Vulnerable Web Application (DVWA). è un software libero:
si può ridistribuire e/o modificarlo sotto i termini del GNU General Public LIcense come pubblicato dalla Free Software Foundation, sia la versione 3 della licensa o qualsiasi versione posteriore.

Damn Vulnerable Web Application (DVWA) è distribuito nella speranza che sarà utile, ma senza ALCUNA GARANZIA; neppure la garanzia implicita di COMMERCIABILITÀ o IDONEITÀ PER UNO SCOPO PARTICOLARE. Vedi la GNU General Public License per ulteriori dettagli.

È necessario avere una copia del GNU General Public License assieme a Damn Vulnerable Web Application (DVWA). Altrimenti, vedere <https://www.gnu.org/licenses/>.

- - -

## Internazionalizzazione

Questo file è disponibile in diverse lingue:

- Arabo: [العربية](README.ar.md)
- Cinese: [简体中文](README.zh.md)
- Francese: [Français](README.fr.md)
- Coreano: [한국어](README.ko.md)
- Persiano: [فارسی](README.fa.md)
- Polacco: [Polski](README.pl.md)
- Portoghese: [Português](README.pt.md)
- Spagnolo: [Español](README.es.md)
- Turco: [Türkçe](README.tr.md)
- Indonesiano: [Indonesia](README.id.md)
- Vietnamita: [Vietnamese](README.vi.md)
- Italiano: [Italiano](README.it.md)

Se si desidera contribuire ad una traduzione, si invii per favore una PR. Nota però: questo non significa semplicemente passare il testo su Google Translate e inviarlo, tali traduzioni verranno rifiutate. Invia la tua versione tradotta aggiungendo un nuovo file chiamato README.xx.md, dove xx è il codice a due lettere della lingua desiderata (basato sullo standard ISO 639-1).

- - -

## Download

Anche se ci sono varie versione di DVWA in circolazione, l'unica supportata è l'ultima dal repository ufficiale di GitHub. Si può sia clonare dal repo:

```sh
git clone https://github.com/digininja/DVWA.git
```

O [scaricare un ZIP dei file](https://github.com/digininja/DVWA/archive/master.zip).

- - -

## Installazione

### Installazione Automatica 🛠️
**Nota, questo non è uno script ufficiale di DVWA, è stato scritto da [IamCarron](https://github.com/iamCarron/). Creare questo script è costato molto lavoro e, quando è stato creato, era sicuro, tuttavia è consigliato leggere lo script prima di eseguirlo alla cieca, per sicurezza. Per favore segnalare qualsiasi bug a [IamCarron](https://github.com/iamCarron/), non qui.**

Uno script di configurazione automatica per DVWA su macchine basate su Debian, inclusa Kali, Ubunut, Kubuntu, Linux Mint, Zorin OS...

**Nota: Questo script richiede i permessi di root ed è pensato per sistemi basati su Debian. È necessario assicurarsi che si è utente root.**

#### Requisiti per l'installazione

- **Sistema operativo:** Sistemi basati su Debian (Kali, Ubuntu, Kubuntu, Linux Mint, Zorin Os)
- **Privilegi-** Eseguire come utente root

#### Step dell'installazione

#####  One-Liner

Questo comando scarica lo script scritto da [@IamCarron](https://github.com/iamCarron/) e lo esegue automaticamente. Questo non sarebbe incluso qui se non avessimo fiducia nell'autore e nello script così com'era al momento della revisione, ma esiste sempre la possibilità che qualcuno agisca in modo malevolo. Pertanto, se non ti senti al sicuro nell'eseguire codice di terzi senza prima esaminarlo personalmente, segui la procedura manuale e potrai rivedere lo script una volta scaricato.

```sh
sudo bash -c "$(curl --fail --show-error --silent --location https://raw.githubusercontent.com/IamCarron/DVWA-Script/main/Install-DVWA.sh)"
```

##### Esecuzione manuale dello Script

1. **##Scarica lo script:** 

    ```sh
   wget https://raw.githubusercontent.com/IamCarron/DVWA-Script/main/Install-DVWA.sh
   ```

2. **Rendi lo script eseguibile:**

   ```sh
   chmod +x Install-DVWA.sh
   ```

3. **Esegui lo script come utente root:**

   ```sh
   sudo ./Install-DVWA.sh
   ```

### Video di installazione

- [Installa DVWA su Kali utilizzando Virtualbox](https://www.youtube.com/watch?v=WkyDxNJkgQ4)
- [Installa DVWA su Windows utilizzando XAMPP](https://youtu.be/Yzksa_WjnY0)
- [Installa Damn Vulnerable Web Application (DVWA) on Windows 10](https://www.youtube.com/watch?v=cak2lQvBRAo)

### Windows + XAMPP

Il modo più semplice per installare DVWA è scaricare ed installare [XAMPP](https://www.apachefriends.org/) qualora già non si abbia un setup di un Web Server.

XAMPP è una distribuzione Apache per Linux, Solaris, Window e MAC OS X molto semplice da installare. I pacchetti includono il web server Apache, MYSQL, PHP Perl, un server FTP e phpMyAdmin.

Questo [video](https://youtu.be/Yzksa_WjnY0) mostra gli step per l'installazione per Windows. Tuttavia dovrebbe essere simile per altri sistemi operativi.

### Docker

Grazie a [hoang-himself](https://github.com/hoang-himself) e [JGillam](https://github.com/JGillam), ogni commit al `master` branch fa in modo che un'immagine Docker sia buildata per ogni branch e sia pronta da essere pullata dal GitHub Container Registry.

Per più informazioni, si visiti [le Immagini Docker prebuildate](https://github.com/digininja/DVWA/pkgs/container/dvwa).

#### Per iniziare

Prerequisiti: Docker e Docker Compose.

- Se si usa Docker Desktop, entrambi i requisiti dovrebbero essere già installati.
- Se si preferisce l'utilizzo di Docker Engine su Linux, è importante seguire correttamente la [guida d'installazione](https://docs.docker.com/engine/install/#server).

**Forniamo assistenza per l'ultima versione di Docker come discusso sopra.**

Se stai usando Linux e il pacchetto Docker fornito dal tuo gestore di pacchetti, probabilmente funzionerà comunque, ma il supporto sarà fornito solo best-effort.

Aggiornare Docker dalla versione del gestore pacchetti a quella ufficiale ("upstream") richiede la disinstallazione delle vecchie versioni, come indicato nella documentazione per [Ubuntu](https://docs.docker.com/engine/install/ubuntu/#uninstall-old-versions), [Fedora](https://docs.docker.com/engine/install/fedora/#uninstall-old-versions) e altre distribuzioni.

I tuoi dati Docker (container, immagini, volumi, ecc.) non dovrebbero essere influenzati, ma nel caso si presentino problemi, assicurati di segnalarli a [Docker](https://www.docker.com/support) e nel frattempo utilizza i motori di ricerca.

Poi, per iniziare:

1. Esegui `docker version` and `docker compose version` per verificare di avere correttamente installati Docker e Docker Compose. Si dovrebbe essere in grado di vedere le versioni dei pacchetti in output.

    Per esempio:

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

    Se non si vede nulla oppure si ha un "errore: comando non trovato", seguire i prerequisiti per fare il setup di Docker e Docker Compose.

2. Clonare e scaricare questo repository ed estrarre (vedere [Download](#download)).
3. Aprire un terminale di tua scelta e cambiare la cartella di lavoro in questa cartella (`DVWA`).
3. Eseguire `docker compose up -d`.

DVWA è ora disponibile all'indirizzo `http://localhost:4280`.


**Nota che per eseguire DVWA nei container, il web server è in ascolto sulla porta 4280 invece che la solita porta 80.**
Per più informazioni su questa decisione, vedere [Voglio eseguire DVWA su una porta differente](#Voglio-eseguire-DVWA-su-una-porta-differente)

#### Build locale

Se si sono fatti cambiamente e si vuole buildare il progetto da locale, andare a `compose.yml` e cambiare `pull_policy: always` in `pull_policy: build`.

Eseguire `docker compose up -d` dovrebbe spingere Docker a buildare un'immagine da locale indipendentemente da ciò che è disponibile nel registro.

See also: [`pull_policy`](https://github.com/compose-spec/compose-spec/blob/master/05-services.md#pull_policy).

#### Serve local files

Se si stanno facendo cambiamenti in locale e non si vuole effettuare una build per ogni cambiamente:
1. Andare a `compose.yml` e rimuovare il commento da :
    ```
        # volumes:
        #   - ./:/var/www/html
    ```
2. Eseguire `cp config/config.inc.php.dist config/config.inc.php` per copiare il file di configurazione di default.
3. Eseguire `docker compose up -d` e i cambiamenti sui file locale si rifletteranno sul container.

### Versioni PHP

Idealmente, dovresti utilizzare l'ultima versione stabile di PHP, poiché è su quella versione che questa applicazione verrà sviluppata e testata.

Non verrà fornito supporto a chi tenta di utilizzare PHP 5.x.

Le versioni inferiori alla 7.3 presentano problemi noti che possono causare malfunzionamenti: gran parte dell'app funzionerà, ma alcune funzionalità potrebbero comportarsi in modo imprevedibile. A meno che tu non abbia un motivo davvero valido per utilizzare una versione così obsoleta, il supporto non sarà garantito.

### Pacchetti Linux

Se stai usando una distribuzione Linux basata su Debian, saranno necessari i seguenti pacchetti _(o loro equivalenti)_:

- apache2
- libapache2-mod-php
- mariadb-server
- mariadb-client
- php php-mysqli
- php-gd

Raccomanderei di fare un update prima di scaricarli, in modo tale da avere sicuramente l'ultima versione di tutto.

```sh
apt update
apt install -y apache2 mariadb-server mariadb-client php php-mysqli php-gd libapache2-mod-php
```

Il sito funzionerà con MySQL invece di MariaDB ma incoraggiamo vivamente MariaDB poiché funziona out of the box. Con MYSQL è necessario fare dei cambiamenti affinché funzioni.

### Moduli Apache

Se si vuole usare il lab API è necessario avere il modulo Apache `mod_rewrite` abilitato. Per fare questo in linux eseguire:

```
a2enmod rewrite
```

Poi riavviare Apache con:

```
apachectl restart
```

### File sellers

Se si vuole usare il modulo API sarà necessario scaricare un insieme di file sellers usando [Composer](https://getcomposer.org/).

In primo luogo, assicurarsi di avere Composer installato. Sembrano esserci problemi di incompatibilità tra le versioni. Io ottengo le versioni più recenti da qui:

https://getcomposer.org/doc/00-intro.md

Seguire le istruzioni del sito per installare Composer.

Poi andare dentro la cartella `vulnerabilities/api` ed eseguire:

```
composer.phar install
```

Se non si è scaricato Composer nella cartella di sistema, assicurarsi di avere come riferimento il percorso completo.

## Configurations

### Config File

DVWA viene fornito con una copia fittizia del suo file di configurazione, che dovrai copiare nella posizione corretta e poi modificare opportunamente. Su Linux, assumendo che tu sia nella directory di DVWA, questo può essere fatto come segue:


`cp config/config.inc.php.dist config/config.inc.php`

Su Windows, questo può essere un po’ più complicato se le estensioni dei file sono nascoste. Se non si è sicuri di questo aspetto, questo articolo del blog lo spiega più nel dettaglio:

[Come mostrare l'estensione dei file su Windows](https://www.howtogeek.com/205086/beginner-how-to-make-windows-show-file-extensions/)

### Configurazione con variabili di ambiente

Invece di modificare il file di configurazione, puoi anche impostare la maggior parte delle opzioni utilizzando delle variabili d'ambiente. In un deployment Docker o Kubernetes, questo ti permette di modificare la configurazione senza dover creare una nuova immagine Docker. Troverai le variabili nel [config/config.inc.php.dist](config/config.inc.php.dist) file.

Se si desidera impostare il livello di sicurezza su "basso", aggiungere semplicemente la seguente linea al file [compose.yml](./compose.yml):

```yml
environment:
  - DB_SERVER=db
  - DEFAULT_SECURITY_LEVEL=low
```

### Setup Del Database

Per fare il setup del database, semplicemente cliccare sul bottone `Setup DVWA` nel menu principale, poi cliccare il bottone `Create / Reset Database`. Questo creerà / resetterà il database per te con un po' di dati al suo interno.

Qualora si riceva un errore durante la creazione del database, assicurarsi di aver inserito delle credenziali corrette dentro `./config/config.inc.php`. _Questo è diverso da config.inc.php.dist, è solo un file di esempio._

Le variabili sono settate ai seguenti valori di default:

```php
$_DVWA[ 'db_server'] = '127.0.0.1';
$_DVWA[ 'db_port'] = '3306';
$_DVWA[ 'db_user' ] = 'dvwa';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';
$_DVWA[ 'db_database' ] = 'dvwa';
```

Nota, se si sta usando MariaDB invece di MySQL (MariaDB è il default in Kali), non si può usare l'utente root del database, bisogna creare un nuovo utente del database. Per fare questo, connettersi al database come utente root e usare i seguenti comandi:

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

### Disabilitare L'Autenticazione
Alcuni strumenti non funzionano bene con l'autenticazione quindi non possono essere usati con DVWA. Per risolvere questo, c'è un'impostazione di configurazione per disabilitare il controllo dell'autenticazione. Per farlo, bisogna semplicemente settare il seguente flag nel file di configurazione

```php
$_DVWA[ 'disable_authentication' ] = true;
```
Sarà anche necessario impostare il livello di sicurezza su uno che sia appropriato sul livello di test che si vuole fare:

```php
$_DVWA[ 'default_security_level' ] = 'low';
```

In questo caso, si può accedere a tutte le features senza il bisogno di essere loggati o senza alcun cookie.

### Cartella Permessi

- `./hackable/uploads/` - È necessario che sia scrivibile dal Web Service (per il File Upload).

### Configurazione PHP

Sui sistemi Linux, probabilmente si trova in `/etc/php/x.x/fpm/php.ini` o `/etc/php/x.x/apache2/php.ini`.

- Per abilitare il Remote File Inclusions (RFI):
  - `allow_url_include = on` [[allow_url_include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
  - `allow_url_fopen = on` [[allow_url_fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]

- Per essere sicuri che PHP mostri tutti i messaggi di errore:
  - `display_errors = on` [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]
  - `display_startup_errors = on` [[display_startup_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-startup-errors)]

È necessario riavviare il servizio php o Apache dopo aver effettuato i cambiamenti.

### reCAPTCHA

È necessario eseguire quanto riportato qui sotto solo per il laboratorio "CAPTCHA insicuro", se non si vuole usare con quel laboratorio, si può ignorare questo capitolo.

Generare un paio di chiavi da <https://www.google.com/recaptcha/admin/create>.

Poi andare nelle seguenti sezioni di `./config/config.inc.php`:

- `$_DVWA[ 'recaptcha_public_key' ]`
- `$_DVWA[ 'recaptcha_private_key' ]`

### Credenziali Di Default

**Default username = `admin`**

**Default password = `password`**

_...può essere bruteforzato facilmente ;)_

URL DI LOGIN: <http://127.0.0.1/login.php>

_Nota: Questo sarà differente se si è installato DVWA in una cartella differente._

- - -

## Risoluzione Di Problemi

Si presume che si stia utilizzando una distribuzione basata su Debian, come Debian, Ubuntu o Kali. Per altre distribuzioni, si può comunque seguire la guida, aggiornando i comandi dove opportuno.

Se si preferisce guardare un video anziché leggere, i problemi più comuni vengono trattati nel video [Fixing DVWA Setup Issues](https://youtu.be/C-kig5qrPSA?si=_a4Bop505-1tXb_F).

### Containers

#### Vorrei accedere i log

Se si sta usando Docker Desktop, i log possono essere acceduti dall'applicazione grafica. Alcuni cambiamenti minori ci possono essere nelle versioni più recenti ma il metodo di accesso dovrebbe essere il medesimo.

![Overview of DVWA compose](./docs/graphics/docker/overview.png)
![Viewing DVWA logs](docs/graphics/docker/detail.png)

I log possono essere anche acceduti dal terminale.

1. Aprire un terminale e cambiare la cartella di lavoro di DVWA.
2. Mostrare i log uniti

    ```sh
    docker compose logs
    ```

   In caso si vogliano esportare i log, i.e. `dvwa.log`

   ```sh
   docker compose logs > dvwa.log
   ```

#### Voglio eseguire DVWA in un'altra porta

Non si utilizza la porta 80 come predefinita per alcuni motivi:

- Alcuni utenti potrebbero avere già qualcosa in esecuzione sulla porta 80.
- Alcuni utenti potrebbero usare un container senza privilegi (come Podman), e la porta 80 è una porta privilegiata (< 1024). Sarebbe necessaria una configurazione aggiuntiva (ad esempio impostare `net.ipv4.ip_unprivileged_port_start`) è necessario, ma su questo bisognerà informarsi autonomamente.

Si può esporre DVWA su una porta diversa modificando l'associazione della porta nel file `compose.yml`.
Per esempio, si può cambiare

```yml
ports:
  - 127.0.0.1:4280:80
```

in

```yml
ports:
  - 127.0.0.1:8806:80
```

DVWA è ora accessibile a `http://localhost:8806`.

Nei casi in cui si desideri che DVWA sia accessibile non solo dal proprio dispositivo, ma anche dalla rete locale (ad esempio perché si sta configurando una macchina di test per un workshop), si può rimuovere il `127.0.0.1:` dall’associazione delle porte (oppure sostituirlo con l’indirizzo IP della propria LAN). In questo modo, l'applicazione ascolterà su tutte le interfacce di rete disponibili.

La scelta predefinita e più sicura dovrebbe comunque essere quella di limitare l’ascolto al solo dispositivo locale (loopback). In fin dei conti, si tratta di un'applicazione web deliberatamente vulnerabile, in esecuzione sulla propria macchina.

437

Il file incluso [`compose.yml`](./compose.yml) esegue automaticamente DVWA e il suo database quando Docker viene inizializzato.

Per disabilitare questa funzione, si può eliminare o commentare la linea `restart: unless-stopped` nel file [`compose.yml`](./compose.yml)

Se si vuole disabilitare questo comportamento temporaneamente, si può eseguire `docker compose stop`, o usare Docker Desktop, trovare `dvwa` e cliccare Stop.
Addizionalmente, si possono eliminare i container oppure eseguire `docker compose down`.

### File di log

Sui sistemi Linux Apache genera due file di log di default, `access.log` e `error.log` e sui sistemi Debian sono solitamente nella cartella `/var/log/apache2/`.

Durante la segnalazione di errori, problemi o qualsiasi cosa del genere, per favore includere almeno cinque linee da ognuno di questi file. Sui sistemi basati su Debian si possono ottenere questi file così

```sh
tail -n 5 /var/log/apache2/access.log /var/log/apache2/error.log
```

### Navigando sul sito ho ottenuto un errore 404 o la pagina predefinita di Apache2

[Video di aiuto](https://youtu.be/C-kig5qrPSA?si=wTS3Aj8fycW3Idfr&t=141)

Se si sta avendo questo problema bisogna capire come funziona la posizione dei file. Di default, la root dei documenti di Apache (il posto in cui comincia a cercare i documenti) è `/var/www/html`, Se si posiziona il file `hello.txt` in questa cartella, per accedere questo file sarà necessario navigare a `http://localhost/hello.txt`.

Se si è creata una cartella e messi i file lì dentro - `/var/www/html/mydir/hello.txt` - si avrà bisogno di navigare a `http://localhost/mydir/hello.txt`.

Linux è attento alle maiuscole e minuscole, se non si fa attenzione si può ottenere un errore `404 Not Found`:

- `http://localhost/MyDir/hello.txt`
- `http://localhost/mydir/Hello.txt`
- `http://localhost/MYDIR/hello.txt`

Che effetto ha questo su DVWA? Diverse persone usano git per clonare DVWA in `/var/www/html`, questo crea la cartella `/var/www/html/DVWA/` con tutti i file al suo interno. Successivamente navigano `http://localhost/` e ottengono o `404` o la pagina predefinita di Apache. Poiché i file sono in DVWA bisogna navigare in `http://localhost/DVWA`.

L'altro errore comune è quello di navigare su `http://localhost/dvwa`, il che porta a un errore `404` perché `dvwa` non è `DVWA`, dato che in Linux si fa distinzione tra maiuscole e minuscole nei nomi delle directory.

Quindi, dopo l'installazione, se si prova a visitare il sito e si riceve un errore `404`, si deve riflettere su dove sono stati installati i file, su dove si trovano rispetto alla radice del documento e su come siano scritte le lettere della directory usata.

### Navigando la pagina ho ottenuto uno schermo vuoto

[Video d'aiuto](https://youtu.be/C-kig5qrPSA?si=wTS3Aj8fycW3Idfr&t=243)

Questo è di solito un problema di configurazione che ne nasconde un altro. Di default, PHP non mostra gli errori, e quindi, se ci si è dimenticati di attivare la visualizzazione degli errori durante il processo di configurazione, qualsiasi altro problema, come un fallimento nella connessione al database, impedirà all'applicazione di caricarsi, ma il messaggio che dice cosa non va sarà nascosto.

Per sistemare questo, bisogna essere sicuri di impostare `display_errors` e `display_startup_errors` come discusso in [Configurazione di PHP](#configurazione-php) e poi riavviare Apache.

### "Access denied" mentre si esegue il setup

Se si vede quanto segue durante l'esecuzione dello script di configurazione, significa che il nome utente o la password nel file di configurazione non corrispondono a quelli configurati nel database.
[Video d'aiuto](https://youtu.be/C-kig5qrPSA?si=_a4Bop505-1tXb_F&t=973)

```mariadb
Database Error #1045: Access denied for user 'notdvwa'@'localhost' (using password: YES).
```

L'errore sta dicendo che si sta usando lo username `notdvwa`.

Il seguente errore dice che si è scritto il file di configurazione al database sbagliato. [Video d'aiuto](https://youtu.be/C-kig5qrPSA?si=_a4Bop505-1tXb_F&t=630)

```mariadb
SQL: Access denied for user 'dvwa'@'localhost' to database 'notdvwa'
```

Questo errore sta invece dicendo che l'utente `dvwa` sta provando a connettersi al database `notdvwa`.

La prima cosa da fare è ricontrollare che ciò che si pensa di aver inserito nel file di configurazione sia effettivamente quello che è presente.

Se corrisponde a quanto ci si aspetta, la cosa successiva da fare è verificare se si riesce ad accedere come l’utente voluto da linea di comando. Supponendo di avere un utente del database chiamato `dvwa` e una password di `p@ssw0rd`, eseguire il seguente comando:

```sh
mysql -u dvwa -pp@ssw0rd -D dvwa
```

_Nota: non c'è uno spazio dopo -p_

Se si vede il seguente output, il codice è corretto:

```mariadb
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 14
Server version: 10.3.22-MariaDB-0ubuntu0.19.10.1 Ubuntu 19.10

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [dvwa]>
```



Poiché si riesce a connettersi dalla riga di comando, è probabile che ci sia qualcosa di sbagliato nel file di configurazione, lo si ricontrolli attentamente e poi si apra un issue se ancora non si riesce a far funzionare le cose.

Se si vede quanto segue, il nome utente o la password che si sta usando è sbagliato. Si ripetano i passaggi della [Database Setup](#database-setup) e ci si assicuri di usare lo stesso nome utente e la stessa password per tutto il processo.

```mariadb
ERROR 1045 (28000): Access denied for user 'dvwa'@'localhost' (using password: YES)
```

Se si ottiene quanto segue, le credenziali dell'utente sono corrette ma l'utente non ha accesso al database. Anche in questo caso, si ripetano i passaggi di configurazione e si controlli il nome del database che si sta usando.

```mariadb
ERROR 1044 (42000): Access denied for user 'dvwa'@'localhost' to database 'dvwa'
```

L'errore finale che si potrebbe ottenere è questo:

```mariadb
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
```

Questo non è un errore di autenticazione ma dice che il server del database non è in esecuzione. Si può avviarlo con il seguente comando.

```sh
sudo service mysql start
```

### Connessione rifiutata

[Video d'aiuto](https://youtu.be/C-kig5qrPSA?si=_a4Bop505-1tXb_F&t=444)

Un errore simile a questo:

```mariadb
Fatal error: Uncaught mysqli_sql_exception: Connection refused in /var/sites/dvwa/non-secure/htdocs/dvwa/includes/dvwaPage.inc.php:535
```

Significa che il server del database non è in esecuzione oppure si ha l'indirizzo ip errato nel file di configurazione.

Controllare questa linea nel file di configurazione per vedere dove il server del database dovrebbe essere:

```php
$_DVWA[ 'db_server' ]   = '127.0.0.1';
```

Poi andare a questo server e controllare che sia in esecuzione. In linux si può fare questo con:

```sh
systemctl status mariadb.service
```

E si deve cercare qualcosa del genere, la cosa importante è che dica qualcosa come `active (running)`.

```sh
● mariadb.service - MariaDB 10.5.19 database server
     Loaded: loaded (/lib/systemd/system/mariadb.service; enabled; preset: enabled)
     Active: active (running) since Thu 2024-03-14 16:04:25 GMT; 1 week 5 days ago
```

Se non è in esecuzione, si può avviare con:

```sh
sudo systemctl stop mariadb.service 
```

Nota `sudo` e assicurarsi di mettere la password di Linux se richiesto.

In Windows, controllare lo status nella console XAMPP.

### Metodo Di Autenticazione Sconosciuto

Con le versioni più recenti di MySQL, non è più possibile far comunicare PHP con il database nella sua configurazione predefinita. Se si prova a eseguire lo script di installazione e viene visualizzato il seguente messaggio, significa che è stata configurata una configurazione.


```mariadb
Database Error #2054: The server requested authentication method unknown to the client.
```

Si hanno due opzioni, la più semplice è disinstallare MySQL e installare MariaDB. La seguente è la guida ufficiale dal progetto di MariaDB:

<https://mariadb.com/resources/blog/how-to-migrate-from-mysql-to-mariadb-on-linux-in-five-steps/>

Alternativamente, seguire questi passi:

1. Da utente root, modificare il seguente file: `/etc/mysql/mysql.conf.d/mysqld.cnf`
1. Sotto la linea `[mysqld]`, aggiungere:
  `default-authentication-plugin=mysql_native_password`
1. Riavviare il database: `sudo service mysql restart`
1. Controllare il metodo di autenticazione per l'utente del database:

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------------------+-----------------------+
    | Host      | User             | plugin                |
    +-----------+------------------+-----------------------+
    | localhost | dvwa             | caching_sha2_password |
    +-----------+------------------+-----------------------+
    1 rows in set (0.00 sec)
    ```
1. Si vedrà verosimilmente `caching_sha2_password`. Se sì, eseguire eseguire i seguenti comandi:

    ```sql
    mysql> ALTER USER dvwa@localhost IDENTIFIED WITH mysql_native_password BY 'p@ssw0rd';
    ```

1. Ri-eseguendo i controlli, si dovrebbe ora vedere
`mysql_native_password`.

    ```sql
    mysql> select Host,User, plugin from mysql.user where mysql.user.User = 'dvwa';
    +-----------+------+-----------------------+
    | Host      | User | plugin                |
    +-----------+------+-----------------------+
    | localhost | dvwa | mysql_native_password |
    +-----------+------+-----------------------+
    1 row in set (0.00 sec)
    ```

Dopo tutto, il processo di inizializzazione dovrebbe funzionare normalmente.

Se si vogliono più informazioni vedere la seguente pagina:
<https://www.php.net/manual/en/mysqli.requirements.php>.

### Errore Del Database #2002: No such file or directory

Il server del database non è in esecuzione. In un sistema basato su Debian questo si può risolvere con:

```sh
sudo service mysql start
```

### Errori "MySQL server has gone away" e "Packets out of order"

Ci sono diverse ragioni per cui si può ricevere questi errori, ma la più probabile è che la versione del server database che si sta utilizzando non è compatibile con la versione di PHP.

Questo problema si riscontra più comunemente quando si utilizza l’ultima versione di MySQL, poiché PHP e MySQL non funzionano bene insieme. Il consiglio migliore è abbandonare MySQL e installare MariaDB, poiché questo è un problema che non possiamo supportare.

Per più informazioni, vedere:

<https://www.ryadel.com/en/fix-mysql-server-gone-away-packets-order-similar-mysql-related-errors/>

### Perché il database non si connette a CentOS?

Si potrebbero star riscontrando problemi con SELinux. Si può sia disabilitare SELinux oppure eseguire il seguente comando che consente di far comunicare il web server con il database:

```sh
setsebool -P httpd_can_network_connect_db 1
```

### Qualsiasi Altra Cosa


Per le informazioni più aggiornate sulla risoluzione dei problemi, si prega di leggere sia i ticket aperti che quelli chiusi nel repository Git:

<https://github.com/digininja/DVWA/issues>

Prima di inviare un ticket, assicurati di utilizzare l’ultima versione del codice dal repository. Questa non è l’ultima release, ma l’ultima versione del codice dal ramo master.

Quando si apre un ticket, si prega di fornire almeno le seguenti informazioni:

-Sistema operativo
-Le ultime 5 righe del log degli errori del server web subito dopo che si è verificato l’errore che stai segnalando
-Se si tratta di un problema di autenticazione al database, segui i passaggi indicati sopra e fai uno screenshot di ogni passaggio. Invia questi screenshot insieme a uno screenshot della sezione del file di configurazione che mostra l’utente e la password del database.
-Una descrizione completa di cosa sta andando storto, cosa ti aspetti che accada e cosa hai provato a fare per risolverlo. "Login non funziona" non è sufficiente per permetterci di capire il tuo problema e aiutarti a risolverlo.

- - -

## Tutorials

Cercherò di realizzare alcuni video tutorial che illustrino alcune vulnerabilità e mostrino come individuarle e poi come sfruttarle. Ecco quelli che ho realizzato finora:

[Trovare e Sftruttare Reflected XSS](https://youtu.be/V4MATqtdxss)

- - -

## SQLite3 SQL Injection

Il supporto per questo è limitato; prima di segnalare problemi, assicurati di essere pronto a lavorare sul debug, non limitarti a dire “non funziona”.

Per impostazione predefinita, gli attacchi SQLi e Blind SQLi vengono eseguiti contro il server MariaDB/MySQL utilizzato dal sito, ma è possibile passare a eseguire i test SQLi su SQLite3.

Non spiegherò come far funzionare SQLite3 con PHP, ma dovrebbe essere sufficiente installare il pacchetto `php-sqlite3` e assicurarsi che sia abilitato.

Per effettuare il cambio, modifica semplicemente il file di configurazione aggiungendo o modificando queste righe:

```php
$_DVWA["SQLI_DB"] = "sqlite";
$_DVWA["SQLITE_DB"] = "sqli.db";
```

Di default viene usato il file `database/sqli.db`, qualora si abbiano problemi, semplicemente copiare `database/sqli.db.dist` in alto.

Le challenge sono esattamente quelle come per MariaDB, tuttavia ora utilizzano SQLite3.

- - -

👨‍💻 Contributors
-----

Grazie per tutti i vostri contributi e per mantenere aggiornato questo progetto. :heart:

Se hai un'idea, qualche tipo di miglioramento o semplicemente vuoi collaborare, sei il benvenuto a contribuire e partecipare al progetto. Sentiti libero di inviare una pull request.

<p align="center">
<a href="https://github.com/digininja/DVWA/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=digininja/DVWA&max=500">
</a>
</p>

- - -

## Segnalare Vulnerabilità

Per essere semplici, non fatelo!

Circa una volta all’anno, qualcuno invia un report riguardo a una vulnerabilità trovata nell’applicazione. Alcuni di questi report sono ben scritti, a volte persino meglio di quelli che ho visto in test di penetrazione a pagamento; altri si limitano a dire “mancano degli header, pagatemi”.

Nel 2023, la cosa ha raggiunto un nuovo livello quando qualcuno ha deciso di richiedere un CVE per una delle vulnerabilità, ottenendo [CVE-2023-39848](https://nvd.nist.gov/vuln/detail/CVE-2023-39848). Ne è seguito molto divertimento e si è perso tempo per sistemare la questione.

L’applicazione contiene vulnerabilità, ed è voluto. La maggior parte sono quelle ben documentate che si affrontano come esercizi, altre sono vulnerabilità “nascoste”, da scoprire autonomamente. Se vuoi davvero dimostrare le tue capacità trovando quelle extra, scrivi un post sul blog o crea un video: probabilmente ci sono persone interessate ad apprendere come le hai individuate. Se ci mandi il link, potremmo anche includerlo tra i riferimenti.

## Link

Home del progetto: <https://github.com/digininja/DVWA>

_Creato dal team DVWA_



# Kubernetes Support

## Build Docker Container

```bash
make docker
```

## Push to Docker Registry

***Note no version management right now, just using latest image in chart.***

```bash
make push
```

***Assuming you are using [Helm-3](https://helm.sh/docs/)***

## Run on Kubernetes
Here we demonstrate deployment on 
[minikube](https://kubernetes.io/docs/tasks/tools/install-minikube/) but chart can be deployed on any cluster:

```bash
minikube start
```

```bash
cd dvwa-chart
helm dependency update
kubectl create namespace dvwa
helm install -n dvwa seizadi-dvwa .
```

You can list the minikube services:
```bash
minikube service list
```
Select the dvwa service:
```bash
|-------------|-----------------|--------------|----------------------------|
|  NAMESPACE  |      NAME       | TARGET PORT  |            URL             |
|-------------|-----------------|--------------|----------------------------|
....
| dvwa        | seizadi-dvwa    | http/80      | http://192.168.64.40:31022 |
....
|-------------|-----------------|--------------|----------------------------|
```

Delete the deployment
```bash
helm uninstall seizadi-dvwa
```

## Update to PHP7

Upgrade to Ubuntu (18.04) is running PHP (7.2), built against master.

## Change Database to use a Chart and Separate Deployment

The database is set in 
```php
function dvwaDatabaseConnect() {
        global $_DVWA;
....
        if( !@($GLOBALS["___mysqli_ston"] = mysqli_connect( $_DVWA[ 'db_server' ],  $_DVWA[ 'db_user' ],  $_DVWA[ 'db_password' ] ))
```

These values flow from the dvwa config file config.inc.php which is seeded with values
from config.inc.php.dist from the DVWA repo:
```php
$_DVWA[ 'db_server' ]   = '127.0.0.1';
$_DVWA[ 'db_database' ] = 'dvwa';
$_DVWA[ 'db_user' ]     = 'dvwa';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';
$_DVWA[ 'db_port'] = '3306';
```

TODO -- change db_server initially but change all database parameters to lookup from environment.

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
start minikube
```

```bash
cd dvwa-chart
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

## Update to PHP7

Upgrade to Ubuntu (18.04) is running PHP (7.2), built against master.

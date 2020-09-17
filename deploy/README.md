# Kubernetes Support

## Build Docker Container

```bash
make docker
```

## Push to Docker Registry

***Note no version management right now, just using latest***

```bash
make push
```

***Assuming you are using [Helm-3](https://helm.sh/docs/)***

## Run on Kubernetes
For this I will demonstrate deployment on 
[minikube](https://kubernetes.io/docs/tasks/tools/install-minikube/) but chart can be deployed on any cluster:

```bash
start minikube
```

```bash
cd dvwa-chart
kubectl create namespace dvwa
helm install -n dvwa seizadi-dvwa .
```

You cna now list the minikube services:
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

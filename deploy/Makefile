IMAGE_VERSION  ?= latest
IMAGE_REGISTRY ?= soheileizadi
SERVER_IMAGE      := $(IMAGE_REGISTRY)/dvwa
MYSQL_IMAGE       := $(IMAGE_REGISTRY)/mysql-generallog
DOCKERFILE_PATH := $(CURDIR)
SERVER_DOCKERFILE := $(DOCKERFILE_PATH)/Dockerfile
MYSQL_DOCKERFILE  := $(DOCKERFILE_PATH)/Dockerfile.mysql

.PHONY: docker
docker:
	@docker build -f $(SERVER_DOCKERFILE) -t $(SERVER_IMAGE):$(IMAGE_VERSION) .
	@docker build -f $(MYSQL_DOCKERFILE) -t $(MYSQL_IMAGE):latest .
	@docker tag $(SERVER_IMAGE):$(IMAGE_VERSION) $(SERVER_IMAGE):latest
	@docker image prune -f --filter label=stage=server-intermediate

.PHONY: push
push:
	@docker push $(SERVER_IMAGE)
	@docker push $(MYSQL_IMAGE)

run:
	@docker run --rm -it -p 80:80 -e RECAPTCHA_PUBLIC_KEY=PUBLIC_KEY -e RECAPTCHA_PRIVATE_KEY=PRIVATE_KEY $(SERVER_IMAGE):$(IMAGE_VERSION)

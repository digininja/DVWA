FROM nginx:latest

RUN apt update && apt upgrade -y
RUN rm /etc/nginx/nginx.conf
RUN rm /etc/nginx/conf.d/default.conf
COPY ./nginx.conf /etc/nginx/
COPY ./config/main.conf /etc/nginx/conf.d

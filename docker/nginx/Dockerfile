FROM nginx:1.19.2-alpine
# https://github.com/nginxinc/docker-nginx/blob/master/mainline/alpine/Dockerfile

# COPY ./src /var/www
COPY ./docker/nginx/remote.conf /etc/nginx/conf.d/default.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

VOLUME /var/www/public

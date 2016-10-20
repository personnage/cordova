FROM nginx:stable
MAINTAINER Kirill Lyubaev <lubaev.ka@gmail.com>

RUN { \
    echo 'server {'; \
    echo '  listen 80;'; \
    echo '  return 444;'; \
    echo '}'; \
} > /etc/nginx/conf.d/default.conf

COPY ./vhost.conf /etc/nginx/conf.d

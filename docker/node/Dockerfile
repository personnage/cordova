FROM node
MAINTAINER Kirill Lyubaev <k.lyubaev@404-group.info>

# https://laracasts.com/discuss/channels/general-discussion/gulp-notify-error-in-notifier-error-in-plugin-gulp-notify-not-found-notify-send
RUN apt-get update -y \
    && apt-get install -y --no-install-recommends \
        libnotify-bin \
    && rm -r /var/lib/apt/lists/*

RUN npm install -g \
    bower \
    gulp \
    gulp-notify

ENV bower_interactive 0
ENV bower_allow_root 1

RUN mkdir -p /var/www/html
WORKDIR /var/www/html

CMD ["/bin/true"]

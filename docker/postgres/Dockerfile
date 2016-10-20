FROM postgres:latest
MAINTAINER Kirill Lyubaev <lubaev.ka@gmail.com>

ENV POSTGIS_MAJOR 2.3

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
       postgis \
       postgresql-$PG_MAJOR-postgis-$POSTGIS_MAJOR-scripts \
       postgresql-$PG_MAJOR-pgrouting \
  && rm -rf /var/lib/apt/lists/*

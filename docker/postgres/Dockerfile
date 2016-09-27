FROM postgres
MAINTAINER Kirill Lyubaev <k.lyubaev@404-group.info>

ENV POSTGIS_MAJOR 2.2

RUN apt-get update \
      && apt-get install -y --no-install-recommends \
           postgresql-$PG_MAJOR-postgis-$POSTGIS_MAJOR \
           postgresql-$PG_MAJOR-pgrouting \
      && rm -rf /var/lib/apt/lists/*

FROM php:7-fpm
MAINTAINER Kirill Lyubaev <k.lyubaev@404-group.info>

RUN apt-get update -y \
    && apt-get install -y --no-install-recommends \
        git \
        zlib1g-dev \
        libghc-postgresql-libpq-dev \
    && rm -r /var/lib/apt/lists/*

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/include/postgresql/ \
    && docker-php-ext-install -j$(nproc) zip pgsql pdo_pgsql

RUN { \
        echo "short_open_tag=Off"; \
        echo "date.timezone=${PHP_TIMEZONE:-UTC}"; \
    } > $PHP_INI_DIR/conf.d/custom-settings.ini


ENV COMPOSER_HASH "e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae"

RUN php -r "copy('https://getcomposer.org/installer', sys_get_temp_dir() . '/composer-setup.php');" \
    && php -r "if (hash_file('SHA384', sys_get_temp_dir() . '/composer-setup.php') !== getenv('COMPOSER_HASH')) { echo 'Installer corrupt'; unlink('composer-setup.php'); exit(1); } echo PHP_EOL;" \
    && php /tmp/composer-setup.php \
        --filename=composer \
        --install-dir=/usr/local/bin \
        --no-ansi \
        --snapshot \
    && php -r "unlink(sys_get_temp_dir() . '/composer-setup.php');"

# https://getcomposer.org/doc/03-cli.md#environment-variables
ENV COMPOSER_HOME /composer
ENV COMPOSER_PROCESS_TIMEOUT 45
ENV COMPOSER_NO_INTERACTION 1
ENV COMPOSER_DISABLE_XDEBUG_WARN 1
ENV COMPOSER_ALLOW_SUPERUSER 1

# https://github.com/laravel/framework/issues/11192#issuecomment-161934620
# RUN composer global require "phpunit/phpunit:~4.0" --prefer-source
# RUN composer global require "friendsofphp/php-cs-fixer" --prefer-source
# RUN composer global require "laravel/installer" --prefer-source

ENV PATH "/composer/vendor/bin:$PATH"

ADD ./ ./
RUN chown -R www-data:www-data storage bootstrap/cache

# https://github.com/boot2docker/boot2docker/issues/587#issuecomment-114868208
RUN usermod -u ${DOCKER_USER_ID:-1000} www-data

# forward laravel error logs to docker log collector
# RUN ln -sf /dev/stderr /var/www/html/storage/logs/laravel.log

VOLUME ["/var/www/html"]

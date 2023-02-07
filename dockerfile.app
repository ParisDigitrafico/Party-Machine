FROM alpine:3.8

# php-7.2 + apache + extras

USER root

WORKDIR /var/www/localhost/htdocs/app

ENV PHP_INI_DIR /etc/php7
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY ./.docker /.docker

ARG DEPS="\
  bash \
  nano \
  curl \
  ca-certificates \
  openssl \
  wget \
  zip \
  unzip \
  dos2unix \
  apache2 \
  php7 \
  php7-apache2 \
  php7-bcmath \
  php7-bz2 \
  php7-cli \
  php7-common \
  php7-ctype \
  php7-curl \
  php7-dom \
  php7-exif \
  php7-fileinfo \
  php7-gd \
  php7-gettext \
  php7-gmp \
  php7-iconv \
  php7-imagick \
  php7-imap \
  php7-intl \
  php7-json \
  php7-ldap \
  php7-mbstring \
  php7-mcrypt \
  php7-openssl \
  php7-pdo \
  php7-pdo_mysql \
  php7-phar \
  php7-posix \
  php7-redis \
  php7-session \
  php7-simplexml \
  php7-sockets \
  php7-tokenizer \
  php7-xml \
  php7-xmlreader \
  php7-xmlrpc \
  php7-xmlwriter \
  php7-zip \
  php7-zlib \
"

RUN set -ex \
  && echo "@php http://dl-cdn.alpinelinux.org/alpine/v3.8/community" >> /etc/apk/repositories \
  && apk update \
  && apk add --no-cache $DEPS \
  && mkdir -p /run/apache2 \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=2.1.9 \
  && cp /.docker/configs/php.ini $PHP_INI_DIR/php.ini \
  && cp /.docker/configs/vhost.conf /etc/apache2/conf.d/vhost.conf \
  && cp /.docker/scripts/docker-entrypoint.sh /docker-entrypoint.sh \
  && dos2unix /docker-entrypoint.sh \
  && chmod +x /docker-entrypoint.sh \
  && rm -fr /var/cache/apk/*

ENTRYPOINT ["bash","/docker-entrypoint.sh"]
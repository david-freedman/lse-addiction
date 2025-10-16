FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

VOLUME /var/www/html

# Dependencies
RUN set -ex \
    && apk update \
    && apk add --no-cache \
        bash \
        bash-completion \
        coreutils \
        freetype \
        icu-libs \
        libbz2 \
        libcurl \
        libjpeg-turbo-dev \
        libmcrypt-dev \
        libpng \
        libpq \
        libxml2 \
        libxpm-dev \
        libxslt-dev \
        libzip-dev \
        linux-headers \
        oniguruma \
        nano \
        unzip \
        util-linux \
        util-linux-bash-completion \
        wget \
        zlib \
        zstd-dev

# Install PHP extensions
RUN set -ex \
    && apk add --no-cache --virtual .build-deps \
        autoconf \
        bzip2-dev \
        curl-dev \
        file \
        freetype-dev \
        gcc \
        git \
        krb5-dev \
        krb5-conf \
        libc-dev \
        libpng-dev \
        make \
        oniguruma-dev \
        postgresql-dev \
        zlib-dev \
    && docker-php-ext-configure gd \
       --with-freetype=/usr/include/ \
       --with-jpeg=/usr/include/ \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/include/postgresql/ \
    && docker-php-ext-install \
        bcmath \
        curl \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pgsql \
        pdo \
        pdo_pgsql \
        pdo_mysql \
        soap \
        sockets \
        xsl \
        zip \
    && apk del --no-network .build-deps

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

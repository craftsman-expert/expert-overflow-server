FROM php:8.2-fpm as base

MAINTAINER Igor Popryadukhin <igorpopryadukhin@gmail.com>

ARG UNAME=www-data
ARG UGROUP=www-data
ARG UID=1000
ARG GID=1000
RUN usermod  --uid $UID $UNAME && groupmod --gid $GID $UGROUP

# Download script to install PHP extensions and dependencies
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ libssl-dev

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

RUN DEBIAN_FRONTEND=noninteractive apt-get update -q \
    && DEBIAN_FRONTEND=noninteractive apt-get install -qq -y \
      jpegoptim \
      optipng \
      curl \
      wget \
      nano \
      zip \
      unzip \
    && install-php-extensions \
      bcmath \
      bz2 \
      calendar \
      exif \
      gd \
      intl \
      ldap \
      mysqli \
      opcache \
      pdo_mysql \
      pdo_pgsql \
      xsl \
      zip \
    && mkdir -p /usr/src/php/ext/redis \
    && curl -fsSL https://pecl.php.net/get/redis --ipv4 | tar xvz -C "/usr/src/php/ext/redis" --strip 1  \
    && docker-php-ext-install redis \
    && pecl install mongodb \
    && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/ext-mongodb.ini

RUN wget https://getcomposer.org/composer-stable.phar -O /usr/local/bin/composer && chmod +x /usr/local/bin/composer

WORKDIR /srv/www

FROM base as dev

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
    # PHP-FPM Customizing
    && echo "pm = dynamic" >> "$PHP_INI_DIR/conf.d/www.conf" \
    && echo "pm.max_children = 50" >> "$PHP_INI_DIR/conf.d/www.conf" \
    && echo "pm.start_servers = 6" >> "$PHP_INI_DIR/conf.d/www.conf" \
    && echo "pm.min_spare_servers = 4" >> "$PHP_INI_DIR/conf.d/www.conf" \
    && echo "pm.max_spare_servers = 8" >> "$PHP_INI_DIR/conf.d/www.conf" \
    # XDebug
    && install-php-extensions xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    # Other
    && mkdir /srv/www/var \
    && mkdir /srv/www/vendor \
    && chown -R $UNAME:$UNAME /srv/www

USER $UNAME

FROM base as prod

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    # PHP-FPM Customizing
    && echo "pm = dynamic" >> "$PHP_INI_DIR/conf.d/www.conf" \
    && echo "pm.max_children = 50" >> "$PHP_INI_DIR/conf.d/www.conf" \
    && echo "pm.start_servers = 6" >> "$PHP_INI_DIR/conf.d/www.conf" \
    && echo "pm.min_spare_servers = 4" >> "$PHP_INI_DIR/conf.d/www.conf" \
    && echo "pm.max_spare_servers = 8" >> "$PHP_INI_DIR/conf.d/www.conf"

USER $UNAME

COPY ./ ./
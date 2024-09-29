FROM php:7.4-fpm-alpine3.16 AS base

RUN sed -i 's#https\?://dl-cdn.alpinelinux.org/alpine#https://mirrors.tuna.tsinghua.edu.cn/alpine#g' /etc/apk/repositories

# 宝塔的PHP installed extensions
# Core date libxml openssl pcre sqlite3 zlib bcmath ctype curl dom filter ftp gd gettext hash iconv intl json mbstring SPL session pcntl standard PDO mysqlnd pdo_sqlite Phar posix Reflection pdo_mysql shmop SimpleXML soap sockets sodium mysqli sysvsem tokenizer xml xmlreader xmlrpc xmlwriter igbinary redis fileinfo swoole

# install extensions
RUN apk add --no-cache libzip-dev zip $PHPIZE_DEPS freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev supervisor \
    && pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-install fileinfo pdo_mysql mysqli bcmath zip \
    && docker-php-ext-enable pdo_mysql \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pcntl \
    # Install swoole
    && pecl install http://pecl.php.net/get/swoole-4.8.12.tgz \
    && docker-php-ext-enable swoole \
    # Install GD extension
    && apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd \
    # --with-gd \
    --with-freetype=/usr/include/ \
    # --with-png=/usr/include/ \
    --with-jpeg=/usr/include/ \
    && NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
    && docker-php-ext-install -j${NPROC} gd \
    && apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev \
    # Install composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    # Clear cache
    && rm -rf /var/cache/apk/*

FROM base

WORKDIR /var/www

ADD ./ /var/www

ARG BUILD_ENV=dev

RUN mkdir -p /tmp/business-card

# remove Cache
RUN rm -rf /var/cache/apk/*

# skip install
RUN touch ./install/install.lock
RUN cp ./docker/${BUILD_ENV}/php.env .env
RUN cp ./docker/${BUILD_ENV}/.constant .constant

RUN mkdir -p /etc/supervisor/conf.d
RUN cp ./docker/${BUILD_ENV}/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# create log dir
RUN mkdir -p /var/log/php-fpm
RUN mkdir -p /var/log/supervisor

# 设置入口命令
# CMD ["php", "think", "swoole"]
CMD [ "/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf" ]

# 创建日志文件
RUN touch /var/www/service.err.log /var/www/service.out.log
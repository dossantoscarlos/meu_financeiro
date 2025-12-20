ARG PHP_VERSION=8.4-fpm
FROM php:${PHP_VERSION}

ARG APP_DIR=/var/www/html
ARG REDIS_LIB_VERSION=6.3.0

WORKDIR ${APP_DIR}


# Sistema
RUN apt-get update && apt-get install -y \
    supervisor \
    nginx \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libpq-dev \
    libxml2-dev \
    libicu-dev \
    curl \
    && rm -rf /var/lib/apt/lists/*

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/sites /etc/nginx/sites-available

# PHP extensions (pdo_sqlite é OBRIGATÓRIO)
RUN docker-php-ext-install \
    intl bcmath zip gd pcntl \
    iconv simplexml fileinfo

RUN pecl install redis-${REDIS_LIB_VERSION} \
    && docker-php-ext-enable redis

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# PHP config
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Supervisor
COPY ./docker/supervisord/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./docker/supervisord/conf /etc/supervisord.d/

# Código
COPY . ${APP_DIR}

# Dependências PHP

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN chown -R www-data:www-data ${APP_DIR}

RUN chmod -R 775 * && chown -R www-data:www-data *

# RUN chmod -R 775 storage bootstrap/cache

# RUN php artisan key:generate --force \
#     && php artisan storage:link \
#     && php artisan optimize

EXPOSE 10000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

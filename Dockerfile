# syntax=docker/dockerfile:1

FROM php:8.3-cli-bookworm AS vendor

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git \
        unzip \
        $PHPIZE_DEPS \
        libssl-dev; \
    pecl install mongodb; \
    docker-php-ext-enable mongodb; \
    apt-get purge -y --auto-remove $PHPIZE_DEPS libssl-dev; \
    rm -rf /var/lib/apt/lists/* /tmp/pear

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --no-scripts \
    --optimize-autoloader

COPY app ./app
COPY database ./database
RUN composer dump-autoload \
    --no-dev \
    --no-interaction \
    --optimize


FROM node:20-bookworm-slim AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY . .
RUN npm run build


FROM php:8.3-fpm-bookworm AS app

LABEL maintainer="Konekin"

ARG UID=1000
ARG GID=1000

ENV APP_HOME=/app \
    COMPOSER_ALLOW_SUPERUSER=1 \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=0 \
    QUEUE_WORKERS=2

WORKDIR /app

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        nginx \
        supervisor \
        unzip \
        libfreetype6 \
        libjpeg62-turbo \
        libpng16-16 \
        libpq5 \
        libssl3 \
        libzip4; \
    apt-get install -y --no-install-recommends \
        $PHPIZE_DEPS \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libpq-dev \
        libssl-dev \
        libzip-dev; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gd \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        zip; \
    pecl install mongodb; \
    docker-php-ext-enable mongodb opcache; \
    apt-get purge -y --auto-remove \
        $PHPIZE_DEPS \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libpq-dev \
        libssl-dev \
        libzip-dev; \
    rm -rf /var/lib/apt/lists/* /tmp/pear

RUN set -eux; \
    groupadd --gid "${GID}" app; \
    useradd --uid "${UID}" --gid app --create-home --shell /bin/sh app; \
    mkdir -p \
        /app \
        /tmp/nginx/client_temp \
        /tmp/nginx/proxy_temp \
        /tmp/nginx/fastcgi_temp \
        /tmp/nginx/uwsgi_temp \
        /tmp/nginx/scgi_temp \
        /tmp/supervisor \
        /var/log/nginx \
        /var/log/supervisor \
        /var/lib/nginx \
        /run/nginx; \
    chown -R app:app \
        /app \
        /tmp/nginx \
        /tmp/supervisor \
        /var/log/nginx \
        /var/log/supervisor \
        /var/lib/nginx \
        /run/nginx

COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/sites-available/default
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN set -eux; \
    ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default; \
    chmod +x /usr/local/bin/entrypoint.sh

COPY --chown=app:app . .
COPY --from=vendor --chown=app:app /app/vendor ./vendor
COPY --from=assets --chown=app:app /app/public/build ./public/build

RUN set -eux; \
    php artisan package:discover --ansi; \
    mkdir -p \
        storage/app/public \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache; \
    chown -R app:app storage bootstrap/cache

USER app

EXPOSE 8000

HEALTHCHECK --interval=30s --timeout=10s --start-period=20s --retries=3 \
    CMD curl -fsS http://127.0.0.1:8000/health || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

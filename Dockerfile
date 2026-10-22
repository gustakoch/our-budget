# Imagem da aplicacao (PHP-FPM). O nginx vem do compose, com a mesma raiz em volume.
#
# PHP 8.4 com Laravel 12. A tag de patch fica fixa para o build ser reprodutível;
# atualizar o PHP é trocar a tag aqui.
FROM php:8.4.27-fpm-alpine3.24

RUN apk add --no-cache \
        git \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        postgresql-dev \
        unzip \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        intl \
        pdo_mysql \
        pdo_pgsql \
        zip \
    && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Instala as dependencias antes do codigo para aproveitar cache de camada.
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --no-scripts \
        --prefer-dist

COPY . .

RUN composer dump-autoload --optimize --no-dev \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm", "-F"]

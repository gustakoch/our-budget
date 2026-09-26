# Imagem da aplicacao (PHP-FPM). O nginx vem do compose, com a mesma raiz em volume.
#
# PHP 8.0: o composer.lock trava nette/schema e nette/utils em versoes que
# exigem php < 8.1. Subir a imagem pede atualizar o lock primeiro.
FROM php:8.0-fpm-alpine

RUN apk add --no-cache \
        git \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        unzip \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        intl \
        pdo_mysql \
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

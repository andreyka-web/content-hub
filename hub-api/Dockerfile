FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    composer \
    git \
    curl \
    openssl \
    pcre \
    zlib \
    libpng \
    freetype \
    libjpeg-turbo \
    libwebp \
    libzip \
    nginx \
    php-session \
    php-fileinfo \
    php-tokenizer \
    php-xml \
    php-dom \
    php-xmlwriter \
    php-pear \ 
    supervisor

RUN apk add icu-dev
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-configure intl && docker-php-ext-install intl

ENV COMPOSER_HOME=/composer
ENV PATH=$PATH:${COMPOSER_HOME}/bin

RUN mkdir -p ${COMPOSER_HOME}

# copy app files with and change owner:group
COPY --chown=www-data:www-data ./hub-api /var/www/html
COPY --chown=www-data:www-data ./hub-api/.env.example /var/www/html/.env

WORKDIR /var/www/html 

RUN composer install
RUN php artisan config:clear 
EXPOSE 9000

# dev only
# VOLUME /var/www/html/vendor

# Nginx configuration file
COPY ./hub-api/.docker/nginx/default.conf /etc/nginx/http.d/default.conf

# the supervisor configuration file
COPY --chown=www-data:www-data ./hub-api/.docker/supervisord.conf /etc/supervisord.conf  
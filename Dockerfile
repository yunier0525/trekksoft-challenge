FROM php:8.1-cli

RUN curl -o /usr/local/bin/composer https://getcomposer.org/composer.phar \
    && chmod +x /usr/local/bin/composer

COPY . /console

WORKDIR /console

RUN apt-get update && apt-get install -y git zip unzip
RUN docker-php-ext-install pcntl

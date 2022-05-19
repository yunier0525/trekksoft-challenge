FROM php:8.1-cli

RUN curl -o /usr/local/bin/composer https://getcomposer.org/composer.phar \
    && chmod +x /usr/local/bin/composer

COPY . /console

WORKDIR /console

RUN composer install --no-dev && docker-php-ext-install pcntl

FROM php:8.1-apache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

COPY . .

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp pdo_mysql

RUN apt-get update \
  && apt-get install -y libzip-dev wget --no-install-recommends \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install zip;

RUN a2enmod rewrite

COPY default.conf /etc/apache2/sites-available/000-default.conf

COPY .env.example .env

RUN chown -R www-data:www-data /var/www/var /var/www/vendor

RUN php bin/console cache:clear --env=dev --no-debug
RUN php bin/console cache:warmup --env=dev --no-debug

COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

EXPOSE 80

CMD ["apache2-foreground"]
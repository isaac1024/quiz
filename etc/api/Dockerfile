FROM php:8.2-cli-alpine

RUN apk update && apk add --update --no-cache bash git supervisor rabbitmq-c rabbitmq-c-dev

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp apcu bcmath gd intl pdo_mysql redis xdebug zip sodium

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /app

COPY etc/api/php.ini /usr/local/etc/php/php.ini
COPY etc/api/supervisord.conf /etc/supervisord.conf

CMD ["supervisord", "-c", "/etc/supervisord.conf"]

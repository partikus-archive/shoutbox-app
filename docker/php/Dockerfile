FROM php:7.2-cli

RUN docker-php-ext-install pdo_mysql

RUN apt-get update && apt-get --yes install git unzip && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENTRYPOINT ["/app/docker/php/entrypoint.sh"]

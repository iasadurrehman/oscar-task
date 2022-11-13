FROM oberd/php-8.0-apache
RUN apt-get update && apt-get upgrade -y
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

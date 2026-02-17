FROM php:8.1-cli

# if you need additional packages, install them here
RUN apt-get update && apt-get install -y libpq-dev zip unzip git curl \
    && docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

# copy project into container (use .dockerignore appropriately)
COPY . /var/www/html

# make runner executable
RUN chmod +x /var/www/html/worker/runner.sh

CMD ["/var/www/html/worker/runner.sh"]
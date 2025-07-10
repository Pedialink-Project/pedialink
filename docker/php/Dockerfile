# Use official PHP + Apache image
FROM php:8.1-apache

# Update system, install php extensions and other applications
RUN apt-get update \
    && apt-get install -y libpq-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set Apache to serve /var/www/html/public instead of /var/www/html
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# And allow .htaccess overrides in that directory
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy composer files (for autoload) and install (no deps)
COPY composer.json composer.lock /var/www/html/
RUN composer install --no-scripts --no-dev --prefer-dist --optimize-autoloader

# Copy application code
COPY . /var/www/html

# Ensure permissions
RUN chown -R www-data:www-data /var/www/html/storage

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
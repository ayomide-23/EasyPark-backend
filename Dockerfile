FROM php:8.2-apache

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copying files into the container
COPY ./public /var/www/html/
COPY ./db_connect.php /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Expose port 80 for web traffic
EXPOSE 80

# Start Apache when the container runs
CMD ["apache2-foreground"]
# Use official PHP image with Apache
FROM php:8.2-apache

# Enable mod_rewrite (optional but useful for .htaccess)
RUN a2enmod rewrite

# Copy your app into the container
COPY ./public /var/www/html/
COPY ./db_connect.php /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Expose port 80
EXPOSE 80
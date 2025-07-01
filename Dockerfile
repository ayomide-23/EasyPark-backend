# Use official PHP image with Apache
FROM php:8.2-apache

# Enable mod_rewrite (optional for .htaccess support)
RUN a2enmod rewrite

# Copy files into the container
COPY ./public /var/www/html/
COPY ./db_connect.php /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Expose port 80 for web traffic
EXPOSE 80

# 🧠 Start Apache when the container runs
CMD ["apache2-foreground"]
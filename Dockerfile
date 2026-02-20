# Use official PHP + Apache image
FROM php:8.2-apache

# Copy your project into Apache web root
COPY . /var/www/html/

# Enable URL rewrite
RUN a2enmod rewrite

# Install MySQL extensions for PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Change Apache to listen on port 8080 instead of default 80
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Expose default Railway port
EXPOSE 8080
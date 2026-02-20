# Use official PHP + Apache image
FROM php:8.2-apache

# Copy your project into Apache web root
COPY . /var/www/html/

# Enable URL rewrite
RUN a2enmod rewrite

# Install MySQL extensions for PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Expose default Railway port
EXPOSE 8080
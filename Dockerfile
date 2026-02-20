# Use official PHP + Apache image (natively listens on Port 80)
FROM php:8.2-apache

# Copy your project into Apache web root
COPY . /var/www/html/

# Enable URL rewrite rules (.htaccess support)
RUN a2enmod rewrite

# Install required MySQL extensions for PHP PDO
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Inform Railway to route HTTP traffic to Apache's default port 80
EXPOSE 80
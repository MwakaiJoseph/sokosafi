# Use official PHP + Apache image
FROM php:8.2-apache

# Copy your project into Apache web root
COPY . /var/www/html/

# Enable URL rewrite
RUN a2enmod rewrite

# Install MySQL extensions for PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Configure Apache to listen on the port provided by Railway or fallback to 8080
ENV PORT=8080
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/' /etc/apache2/sites-available/000-default.conf

# Start Apache
CMD ["apache2-foreground"]
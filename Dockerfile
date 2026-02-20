# Use official PHP + Apache image (natively listens on Port 80)
FROM php:8.2-apache

# Copy your project into Apache web root
COPY . /var/www/html/

# Enable URL rewrite rules
RUN a2enmod rewrite

# Install required MySQL extensions for PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Create an entrypoint script to force MPM prefork and bind to Railway's dynamic PORT
RUN echo '#!/bin/bash\n\
    a2dismod mpm_event mpm_worker || true\n\
    a2enmod mpm_prefork\n\
    sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf\n\
    sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf\n\
    exec apache2-foreground' > /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080 80

CMD ["/usr/local/bin/entrypoint.sh"]
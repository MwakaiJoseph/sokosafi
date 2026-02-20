# Use official PHP + Apache image (natively listens on Port 80)
FROM php:8.2-apache

# Copy your project into Apache web root
COPY . /var/www/html/

# Enable URL rewrite rules (.htaccess support)
RUN a2enmod rewrite

# Install required MySQL extensions for PHP PDO
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Explicitly disable conflicting MPMs and ensure prefork is enabled (Fixes Railway crash)
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork

# Configure Apache to use the dynamic PORT environment variable at runtime
RUN echo '#!/bin/bash\n\
    sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf\n\
    sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf\n\
    exec apache2-foreground' > /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

# Run the entrypoint script
CMD ["/usr/local/bin/entrypoint.sh"]
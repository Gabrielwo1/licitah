FROM php:8.2-fpm

# Install PostgreSQL extension + Nginx + supervisor
RUN apt-get update && apt-get install -y \
    libpq-dev \
    nginx \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Supervisor config (runs nginx + php-fpm together)
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Remove example config
RUN rm -f conteudo/config.example.php

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Use config from environment variables at startup
COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

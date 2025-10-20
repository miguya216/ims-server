# Use official PHP image with Composer
FROM php:8.2-cli

# Install needed extensions
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    && docker-php-ext-install pcntl

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install composer dependencies
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev

# Expose port for websocket
EXPOSE 8080

# Command to start Ratchet server
CMD ["php", "server.php"]

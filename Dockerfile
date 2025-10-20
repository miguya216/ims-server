# Use official PHP image with Composer
FROM php:8.2-cli

# Install required packages and PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev \
    && docker-php-ext-install pcntl

# Set working directory
WORKDIR /app

# Copy composer files first (for layer caching)
COPY composer.json composer.lock ./

# Install dependencies (vendor will be created here)
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --no-interaction

# Now copy the rest of your project (server.php, etc.)
COPY . .

# Expose port (Render will override via $PORT)
EXPOSE 8080

# Run the WebSocket server
CMD ["php", "server.php"]

FROM php:8.2-cli

RUN apt-get update && apt-get install -y libzip-dev zip unzip git && docker-php-ext-install pcntl

WORKDIR /app

# Copy everything, including vendor/
COPY . .

# Skip composer install
# RUN composer install --no-dev  <-- REMOVE THIS LINE

EXPOSE 8080
CMD ["php", "server.php"]

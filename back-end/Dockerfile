# PHP 8.2 with Node.js for building frontend assets
FROM laravelsail/php82-composer:latest

# Set the working directory
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --ignore-platform-reqs

# Copy all project files
COPY . .

# Install npm dependencies and build frontend assets
RUN npm install && npm run dev

EXPOSE 80

# Start the PHP application server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
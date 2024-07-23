# Use the official PHP image from Docker Hub
FROM php:7.4-apache

# Install dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        unzip \
        && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd mysqli pdo_mysql zip

# Set working directory
WORKDIR /var/www/html

# Create uploads directory and set permissions
RUN mkdir -p uploads && \
    chown -R www-data:www-data uploads && \
    chmod -R 755 uploads

# Copy PHP application files to the container
COPY . .

# Set permissions for PHP files (adjust as needed)
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expose port 80 (default HTTP port)
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]

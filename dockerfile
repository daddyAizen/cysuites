FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# Install PHP extensions, Node, and other dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip curl \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
  && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get install -y nodejs \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Build frontend assets (if you have a Node/Vue/Nuxt frontend)
# RUN npm install && npm run build

EXPOSE 9000

CMD ["php-fpm"]

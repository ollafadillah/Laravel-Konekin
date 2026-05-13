# Stage 1: Builder
FROM php:8.3-fpm as builder

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    gd \
    zip \
    bcmath

# Install MongoDB driver
RUN apt-get update && apt-get install -y libssl-dev && rm -rf /var/lib/apt/lists/* && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /app

# Copy application files
COPY composer.json composer.lock* ./
COPY package.json package-lock.json* ./
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Install Node dependencies and build assets
RUN npm ci && npm run build

# Generate cache
RUN php artisan config:cache && \
    php artisan route:cache

# Stage 2: Production
FROM php:8.3-fpm

LABEL maintainer="Konekin"

# Install minimal runtime dependencies
RUN apt-get update && apt-get install -y \
    libpq5 \
    libpng6 \
    libjpeg62-turbo \
    libfreetype6 \
    libzip4 \
    ca-certificates \
    supervisor \
    nginx \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    gd \
    zip \
    bcmath

# Install MongoDB driver
RUN apt-get update && apt-get install -y libssl-dev && rm -rf /var/lib/apt/lists/* && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Copy PHP configuration
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Copy Nginx configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Copy Supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create non-root user
RUN useradd -m -u 1000 -s /bin/bash app && \
    mkdir -p /app && \
    chown -R app:app /app

# Set working directory
WORKDIR /app

# Copy application from builder stage
COPY --from=builder --chown=app:app /app .

# Create required directories with proper permissions
RUN mkdir -p storage/logs bootstrap/cache && \
    chown -R app:app storage bootstrap

# Fix ownership for Nginx
RUN chown -R app:app /var/log/nginx /var/run/nginx /var/cache/nginx 2>/dev/null || true

# Switch to non-root user
USER app

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Expose port
EXPOSE 8000

# Run entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

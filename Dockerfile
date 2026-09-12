FROM php:8.2-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-configure pgsql --with-pgsql=/usr/local \
    && docker-php-ext-install pdo_pgsql pgsql zip bcmath \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && git config --global --add safe.directory /var/www \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www

RUN chmod -R 755 /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www

EXPOSE 8000

CMD ["bash", "-lc", "npm install --no-fund --no-audit && npm run build && php artisan serve --host=0.0.0.0 --port=8000"]

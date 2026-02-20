# Gunakan image PHP 8.2 resmi yang super ringan dan stabil
FROM php:8.2-cli

# Instalasi alat pertukangan dan ekstensi PHP yang dibutuhkan Laravel & Filament
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git libzip-dev curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip intl

# Instalasi Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur ruang kerja
WORKDIR /app
COPY . .

# Instalasi Node.js (untuk mem-build Tailwind CSS & Alpine.js)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Eksekusi instalasi dependensi (PHP & Node.js)
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Bersihkan cache Laravel
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Berikan akses izin ke folder penyimpanan
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Eksekusi migrasi database dan nyalakan server saat mesin hidup
CMD php artisan migrate --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=$PORT
# 1. Usa la imagen de PHP optimizada para FPM
FROM php:8.3-fpm

# 2. Se define la carpeta de trabajo dentro del contenedor
WORKDIR /var/www

# 3. Intala las dependencias del sistema operativo para Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip

# Se limpia la caché de apt para que la imagen sea más ligera
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 4. Se instala las extensiones de PHP requeridas por Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 5. Se copia Composer desde su imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Se copia TODO el código fuente local al contenedor
COPY . .

# 7. Se instala las dependencias de Laravel optimizadas para producción
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Se ajustan los permisos de las carpetas críticas de Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 9. Se expone el puerto interno donde escucha PHP-FPM
EXPOSE 9000

# 10. Comando para arrancar el motor PHP
CMD ["php-fpm"]
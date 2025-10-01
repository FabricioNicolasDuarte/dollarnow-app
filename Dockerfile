# Usa una imagen oficial de PHP 8.2
FROM php:8.2-cli-alpine

# Instala dependencias del sistema y extensiones de PHP
RUN apk add --no-cache $PHPIZE_DEPS \
    && apk add --no-cache libzip-dev postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /app

# Copia los archivos de dependencias e instala
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist --optimize-autoloader

# Copia el resto del código de la aplicación <<< ¡ESTE ES EL CAMBIO IMPORTANTE!
COPY . .

# Limpia CUALQUIER configuración o caché existente de forma agresiva <<< AHORA SÍ PUEDE ENCONTRAR ARTISAN
RUN php artisan config:clear && php artisan cache:clear && rm -f bootstrap/cache/config.php

# Copia el script de arranque y hazlo ejecutable
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expone el puerto
EXPOSE 10000

# El comando para iniciar el contenedor ahora es nuestro script
CMD ["entrypoint.sh"]
# Usa una imagen oficial de PHP 8.2
FROM php:8.2-cli-alpine

# Instala dependencias del sistema necesarias para Laravel
# y las extensiones de PHP para la base de datos (pgsql) y zip.
RUN apk add --no-cache $PHPIZE_DEPS \
    && apk add --no-cache libzip-dev postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instala Composer (el gestor de paquetes de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo dentro del contenedor
WORKDIR /app

# Copia los archivos de dependencias primero para aprovechar el cache de Docker
COPY composer.json composer.lock ./
# Instala las dependencias de producción de Composer
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist --optimize-autoloader

# Copia el resto del código de tu aplicación al contenedor
COPY . .

# Expone el puerto que usará la aplicación
EXPOSE 10000

# Comando por defecto para iniciar el servidor de Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000
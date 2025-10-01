# --- ETAPA 1: Construir los archivos de Frontend (Vite) ---
FROM node:20-alpine AS frontend

# Establecer directorio de trabajo
WORKDIR /app

# Copiar archivos de dependencias de Node
COPY package.json package-lock.json ./

# Instalar dependencias de Node
RUN npm install

# Copiar el resto de los archivos para poder construir
COPY . .

# Construir los archivos de producción (CSS, JS)
RUN npm run build


# --- ETAPA 2: Construir la aplicación PHP final ---
FROM php:8.2-cli-alpine

# Instala dependencias del sistema y extensiones de PHP
RUN apk add --no-cache $PHPIZE_DEPS \
    && apk add --no-cache libzip-dev postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /app

# Copia los archivos de dependencias de PHP e instala
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist --optimize-autoloader

# Copia el resto del código de la aplicación
COPY . .

# Copia SOLAMENTE los archivos compilados desde la etapa 'frontend'
COPY --from=frontend /app/public/build ./public/build

# Elimina directamente los archivos de caché si existen
RUN rm -f bootstrap/cache/config.php bootstrap/cache/routes.php

# Copia el script de arranque y hazlo ejecutable
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expone el puerto
EXPOSE 10000

# El comando para iniciar el contenedor ahora es nuestro script
CMD ["entrypoint.sh"]
#!/bin/sh

# Salir inmediatamente si un comando falla
set -e

# Ejecutar las migraciones de la base de datos
echo "Running database migrations..."
php artisan migrate --force

# Iniciar el servidor de Laravel
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=10000

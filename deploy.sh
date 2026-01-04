#!/bin/bash

# Script de despliegue para PetuniaPlay
# Uso: sudo ./deploy.sh

set -e  # Detener si hay errores

echo "Iniciando despliegue de PetuniaPlay..."

cd /var/www/petuniaplay

# Obtener últimos cambios
echo "Descargando cambios del repositorio..."
git pull

# Frontend
echo "Compilando frontend..."
cd frontend
npm install --production=false
npm run build

# Backend
echo "Configurando backend..."
cd ../backend
composer install --no-dev --optimize-autoloader

# Migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

# Limpiar y regenerar cachés
echo "Limpiando cachés..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "Generando cachés..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permisos
echo "Configurando permisos..."
chown -R www-data:www-data /var/www/petuniaplay/backend/storage
chown -R www-data:www-data /var/www/petuniaplay/backend/bootstrap/cache
chmod -R 775 /var/www/petuniaplay/backend/storage
chmod -R 775 /var/www/petuniaplay/backend/bootstrap/cache

# Reiniciar servicios (descomenta según tu configuración)
# echo "Reiniciando servicios..."
# systemctl restart nginx
# systemctl restart php8.2-fpm

echo "Despliegue completado exitosamente!"

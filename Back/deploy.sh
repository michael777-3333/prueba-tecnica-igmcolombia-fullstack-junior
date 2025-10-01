#!/bin/bash

# Script de despliegue para Laravel + Vue SPA
echo "🚀 Iniciando proceso de despliegue..."

# 1. Instalar dependencias de PHP
echo "📦 Instalando dependencias de PHP..."
composer install --optimize-autoloader --no-dev

# 2. Instalar dependencias de Node.js
echo "📦 Instalando dependencias de Node.js..."
npm ci

# 3. Compilar assets de producción
echo "🔨 Compilando assets de producción..."
npm run build

# 4. Optimizar Laravel
echo "⚡ Optimizando Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# 6. Limpiar caché
echo "🧹 Limpiando caché..."
php artisan cache:clear
php artisan config:clear

# 7. Establecer permisos
echo "🔐 Estableciendo permisos..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Despliegue completado exitosamente!"
echo "🌐 La aplicación está lista en: http://localhost:81"

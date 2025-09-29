# 🚀 Guía de Despliegue - Laravel + Vue SPA

## 📋 Requisitos Previos

- Docker y Docker Compose
- Node.js 18+ (para desarrollo local)
- PHP 8.2+ (para desarrollo local)
- Composer (para desarrollo local)

## 🐳 Despliegue con Docker (Recomendado)

### 1. Clonar el repositorio
```bash
git clone <tu-repositorio>
cd prueba-laravel
```

### 2. Configurar variables de entorno
```bash
cp .env.example .env
# Editar .env con tus configuraciones
```

### 3. Ejecutar con Docker
```bash
# Construir y levantar contenedores
docker compose up -d --build

# Instalar dependencias
docker compose exec app composer install
docker compose exec app npm install

# Compilar assets
docker compose exec app npm run build

# Ejecutar migraciones
docker compose exec app php artisan migrate

# Crear usuario de prueba (opcional)
docker compose exec app php artisan tinker
# En tinker: User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password')])
```

### 4. Acceder a la aplicación
- **Frontend**: http://localhost:81
- **API**: http://localhost:81/api
- **Base de datos**: localhost:3306

## 🖥️ Despliegue en Servidor

### 1. Preparar el servidor
```bash
# Actualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar dependencias
sudo apt install nginx php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip composer nodejs npm -y
```

### 2. Configurar Nginx
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /var/www/html/public;
    index index.php;

    # Configuración para SPA
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Configuración para API
    location /api {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Configuración para assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Configuración para PHP
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 3. Ejecutar script de despliegue
```bash
# Hacer ejecutable
chmod +x deploy.sh

# Ejecutar despliegue
./deploy.sh
```

## 🔧 Configuración de Producción

### Variables de entorno importantes
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Para Sanctum
SANCTUM_STATEFUL_DOMAINS=tu-dominio.com
SESSION_DOMAIN=tu-dominio.com
```

### Optimizaciones de rendimiento
```bash
# Cache de configuración
php artisan config:cache

# Cache de rutas
php artisan route:cache

# Cache de vistas
php artisan view:cache

# Optimizar autoloader
composer install --optimize-autoloader --no-dev
```

## 🔒 Seguridad

### 1. Configurar HTTPS
```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx

# Obtener certificado SSL
sudo certbot --nginx -d tu-dominio.com
```

### 2. Configurar firewall
```bash
# Configurar UFW
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

### 3. Configurar permisos
```bash
# Permisos para Laravel
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 775 /var/www/html/storage
sudo chmod -R 775 /var/www/html/bootstrap/cache
```

## 📊 Monitoreo

### 1. Logs de Laravel
```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log
```

### 2. Logs de Nginx
```bash
# Ver logs de acceso
tail -f /var/log/nginx/access.log

# Ver logs de error
tail -f /var/log/nginx/error.log
```

## 🚨 Solución de Problemas

### Error: "Class 'Inertia\Middleware' not found"
```bash
# Remover Inertia si no se usa
composer remove inertiajs/inertia-laravel
```

### Error: "Vite manifest not found"
```bash
# Recompilar assets
npm run build
```

### Error: "Token mismatch"
```bash
# Limpiar caché de configuración
php artisan config:clear
```

### Error: "Route not found"
```bash
# Limpiar caché de rutas
php artisan route:clear
```

## 📈 Optimizaciones Adicionales

### 1. Compresión Gzip
```nginx
# En nginx.conf
gzip on;
gzip_vary on;
gzip_min_length 1024;
gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;
```

### 2. Cache de base de datos
```bash
# Instalar Redis
sudo apt install redis-server

# Configurar en .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 3. CDN para assets
```bash
# Configurar CDN en vite.config.js
export default defineConfig({
  build: {
    rollupOptions: {
      output: {
        assetFileNames: 'assets/[name]-[hash][extname]'
      }
    }
  }
})
```

## 🎯 Checklist de Despliegue

- [ ] Variables de entorno configuradas
- [ ] Base de datos configurada
- [ ] Assets compilados
- [ ] Migraciones ejecutadas
- [ ] Permisos configurados
- [ ] SSL configurado
- [ ] Firewall configurado
- [ ] Logs monitoreados
- [ ] Backup configurado
- [ ] Monitoreo configurado

## 📞 Soporte

Si encuentras problemas durante el despliegue:

1. Revisa los logs de Laravel y Nginx
2. Verifica las variables de entorno
3. Confirma que todos los servicios estén ejecutándose
4. Revisa los permisos de archivos y directorios

¡Tu aplicación SPA está lista para producción! 🎉

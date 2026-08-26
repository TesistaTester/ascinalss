#!/bin/bash
set -e

cd /var/www/html

# Generar APP_KEY si no viene en las variables de entorno
php artisan key:generate --force

# Ejecutar migraciones (--force omite confirmación en producción)
php artisan migrate --force

# Poblar datos solo si la tabla usuarios está vacía
USER_COUNT=$(php artisan tinker --execute="echo App\Models\Usuario::count();" 2>/dev/null | tail -1)
if [ "$USER_COUNT" = "0" ]; then
    echo "BD vacía — ejecutando seeders..."
    php artisan db:seed --force
    php artisan ascinalss:importar-archivos
fi

# Cachear para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar Apache
apache2-foreground
#!/usr/bin/env bash

set -e

# ==============================================================================
# Tareas Simple - Script de Inicialización y Onboarding desde Cero (setup.sh)
# ==============================================================================

echo "=================================================================="
echo "📚  Inicializando Tareas Simple (Entorno Docker & Laravel Sail)  📚"
echo "=================================================================="

# 1. Comprobar que Docker esté instalado y en ejecución
if ! command -v docker &> /dev/null; then
    echo "❌ Error: Docker no está instalado en este equipo."
    echo "Por favor instala Docker Desktop o el motor Docker para continuar."
    exit 1
fi

if ! docker info &> /dev/null; then
    echo "❌ Error: El servicio de Docker no está en ejecución."
    echo "Inicia el demonio de Docker y vuelve a ejecutar este script."
    exit 1
fi

# 2. Configurar archivo de entorno .env
if [ ! -f .env ]; then
    echo "📄 Creando archivo .env a partir de .env.example..."
    cp .env.example .env
else
    echo "✅ Archivo .env existente detectado."
fi

# 3. Instalar dependencias de Composer si no existe vendor/
if [ ! -d "vendor" ]; then
    echo "📦 Instalando dependencias de Composer mediante contenedor temporal..."
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
else
    echo "✅ Directorio vendor/ detectado."
fi

# 4. Asegurar permisos del script ./sail
chmod +x ./sail 2>/dev/null || true

# 5. Levantar contenedores Docker en segundo plano
echo "🐳 Levantando contenedores de Laravel Sail en segundo plano..."
./sail up -d

# Esperar a que la base de datos esté lista para recibir conexiones
echo "⏳ Esperando a que el servicio de MariaDB esté listo..."
until ./sail exec mariadb mariadb-admin ping -ppassword --silent &> /dev/null; do
    sleep 2
done
echo "✅ MariaDB listo."

# 6. Generar App Key si no está configurada
echo "🔑 Verificando clave de aplicación (APP_KEY)..."
./sail artisan key:generate --force

# 7. Ejecutar migraciones y datos iniciales (Seeders)
echo "🗄️  Ejecutando migraciones y seeders de prueba..."
./sail artisan migrate:fresh --seed --force

# 8. Instalar dependencias de Node y compilar assets con Vite
echo "🎨 Instalando dependencias de frontend (NPM)..."
./sail npm install

echo "⚡ Compilando assets de Vite..."
./sail npm run build

echo ""
echo "=================================================================="
echo "🎉 ¡Tareas Simple quedó inicializado y listo para usar! 🎉"
echo "=================================================================="
echo "📍 Aplicación web:      http://localhost"
echo "📍 phpMyAdmin:           http://localhost:8080"
echo ""
echo "Credenciales de prueba generadas por los seeders:"
echo "  • Usuario Demo:        test@example.com / password"
echo ""
echo "Para iniciar el desarrollo diario ejecuta:"
echo "  ./dev.sh"
echo "=================================================================="

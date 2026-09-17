#!/usr/bin/env bash

set -e

# ==============================================================================
# Tareas Simple - Script de Desarrollo Diario (dev.sh)
# ==============================================================================

echo "📚 Iniciando entorno de desarrollo de Tareas Simple..."

# 1. Comprobar Docker
if ! docker info &> /dev/null; then
    echo "❌ Error: Docker no está en ejecución. Por favor inicia Docker."
    exit 1
fi

# 2. Asegurar que los contenedores de Sail estén arriba
if ! ./sail ps | grep -q "Up"; then
    echo "🐳 Levantando contenedores de Laravel Sail en segundo plano..."
    ./sail up -d
else
    echo "✅ Contenedores de Laravel Sail ya están en ejecución."
fi

echo "⚡ Iniciando servidor de desarrollo de Vite..."
echo "💡 Presiona Ctrl+C para detener Vite (los contenedores de Sail seguirán activos)."
echo ""

./sail npm run dev

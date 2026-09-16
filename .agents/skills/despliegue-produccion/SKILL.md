---
name: despliegue-produccion
description: Guía y flujo operativo paso a paso para ejecutar despliegues seguros y automatizados de develop hacia main en UniTask con Gabriel. Usar ante frases como 'Modo Deploy' o 'Vamos a desplegar a producción'.
---

# 🚀 Flujo Operativo de Despliegue a Producción (UniTask)

Esta habilidad se activa cuando Gabriel solicita desplegar los cambios de `develop` a `main` mediante comandos como:
- `"Modo Deploy"`
- `"Vamos a desplegar a producción"`

---

## 📋 FASE 1: Verificaciones y Preparación Pre-Deploy

1. **Confirmar Rama y Estado Local**:
   ```bash
   git checkout develop
   git pull origin develop
   git status
   ```
2. **Ejecutar Suite Global de Tests**:
   ```bash
   ./vendor/bin/sail artisan test
   ```
   *Regla*: Exigir 100% de tests en verde antes de continuar.
3. **Guardia Anti-Destructiva**:
   ```bash
   grep -rnE --exclude-dir={vendor,node_modules,.git,storage,docs} "migrate:fresh|db:wipe" app/ database/ routes/ config/ scripts/
   ```
4. **Revisión de Variables Pendientes de Entorno**:
   - Leer `.env.production.pending` y presentar a Gabriel las variables a copiar en Hostinger.
5. **Oferta de Respaldo Local**:
   - Ofrecer a Gabriel ejecutar el script:
     ```bash
     bash scripts/backup_production_db.sh
     ```

---

## 📦 FASE 2: Creación y Fusión Automática de la Pull Request `develop ➔ main`

1. **Generar la Pull Request en GitHub**:
   ```bash
   gh pr create --base main --head develop --title "Release vX.Y.Z: Despliegue a Producción" --body "..."
   ```
2. **Estructura Obligatoria del Cuerpo de la PR**:
   - Resumen técnico de cambios y tareas incluidas.
   - Lista de nuevas migraciones a ejecutar con `php artisan migrate --force`.
   - Tabla de variables `.env` agregadas o modificadas.
   - Checklist de verificación.
3. **Fusión Automática hacia `main`**:
   - Comprobar ausencia de conflictos y fusionar automáticamente:
     ```bash
     gh pr merge <id> --merge
     ```

---

## 🚀 FASE 3: Despliegue CI/CD y Monitoreo con GitHub MCP

1. Tras la fusión en `main`, el workflow `.github/workflows/deploy.yml` ejecuta automáticamente:
   - **Job 1 (Quality Gate)**: Tests automatizados en PHP 8.3, linter y comprobación anti-`migrate:fresh`.
   - **Job 2 (Deploy)**: Build Vite + FTPS Sync + SSH Post-Deploy (Modo mantenimiento, backup MariaDB remoto, migraciones, cachés y reactivación).
2. **Monitoreo en Tiempo Real**: Antigravity supervisa la ejecución del workflow mediante el servidor MCP de GitHub (o `gh run watch`), informando a Gabriel el avance de cada job hasta su finalización exitosa.
3. Una vez completado, verificar Smoke Test HTTP 200 en la URL pública.

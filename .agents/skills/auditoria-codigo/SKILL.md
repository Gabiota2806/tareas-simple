---
name: auditoria-codigo
description: Guía y flujo operativo paso a paso para auditar, revisar y validar tareas y Pull Requests creadas por otros desarrolladores del equipo (Matías, Valeria, etc.). Usar ante frases como "Modo Auditoría" o "Vamos a auditar la tarea X".
---

# 🕵️ Skill: Auditoría y Revisión de Código de Compañeros

Este skill guía a Antigravity en la ejecución paso a paso de la auditoría técnica de tareas externas junto a Gabriel.

## 🔄 Flujo Operativo Paso a Paso

### 1. Datos Requeridos, Detección de Modo y Posicionamiento en Rama Remota
* **Detección de Modo de Trabajo (Local vs. Remoto)**:
  - Antigravity identifica si Gabriel validará la auditoría en **Modo Local** (en la pc) o en **Modo Remoto** (desde el celular).
  - Si Gabriel no lo especificó en su solicitud, Antigravity **debe consultarlo antes de presentar el Informe de Auditoría**: *"¿Validarás la auditoría en Modo Local o en Modo Remoto?"*.
  - El modo se declara en el encabezado del Informe de Auditoría.
* Solicitar o recibir:
  1. Identificador de la tarea en Jira (ej. `TASK-05` / `UT-21`).
  2. Nombre de la rama remota (ej. `UT-21-TASK-05-...`) o simplemente la clave para búsqueda automática.
  3. Desarrollador asignado: Matías Nuñez.
* Comandos de sincronización y checkout:
  ```bash
  git fetch origin
  # Si solo se dispone de la clave, localizar la rama remota automáticamente:
  BRANCH=$(git branch -r | grep -iE "(UT|TASK)-[0-9]+" | grep -i "<CLAVE>" | head -n 1 | tr -d ' ' | sed 's#origin/##')
  git checkout -b "$BRANCH" "origin/$BRANCH"
  ```

### 2. Inspección Minuciosa de Código (Git Diff & Laravel Boost MCP)
* Ejecuta `git diff develop..HEAD` y evalúa:
  - **Aislamiento Multi-Tenant**: `tenant_id` en modelos, consultas y eventos (`BelongsToTenant`).
  - **Rendimiento SQL**: Consultas agrupadas vs N+1 queries, eager loading correcto.
  - **Inspección de Esquema (`database-schema`)**: Validar que nuevas tablas/columnas contengan claves foráneas, índices y tipos correctos.
  - **Consultas Seguras (`database-query`)**: Ejecutar SELECTs de solo lectura para auditar comportamiento de datos y scope multi-tenant sin mutar datos.
  - **Lógica de Negocio y Licenciamiento**: Respeto de feature flags y supresión condicional.
  - **Estándares UI/UX e Iconografía**: Componentes Blade/Alpine responsivos y uso obligatorio de **SVGs de Heroicons con Tailwind** (rechazando emojis o iconos default). Antigravity **debe utilizar `/browser` o Playwright** para navegar a la interfaz y validar visualmente el apego al Design System.

### 3. Suite de Pruebas TDD
* Ejecuta `./vendor/bin/sail artisan test <archivo-test-tarea>`
* Ejecuta la suite global `./vendor/bin/sail artisan test` (debe estar 100% verde).

### 4. Manejo de Hallazgos (Estrategia Híbrida)
* **Caso A (Ajustes Menores o Tests Faltantes)**:
  - Antigravity implementa la corrección directamente en la rama.
  - Crea el test Pest correspondiente.
  - Comitea en español (`test: ...`, `fix: ...`) y pushea (`git push origin <rama>`).
* **Caso B (Errores Graves de Lógica o Arquitectura)**:
  - Antigravity emite informe de rechazo en el chat.
  - Reabre la tarea en Jira con comentario técnico detallado para el autor.

### 5. Pre-Verificación Técnica en Navegador (/browser / Playwright), Reporte y QA Condicional
* Si la tarea auditada afecta frontend, vistas Blade, componentes Livewire o estilos:
  - **Pre-Verificación Técnica Obligatoria (Smoke Testing con Boost MCP)**:
    Antes de emitir el informe y entregar la guía a Gabriel:
    1. Navegar con `/browser` o Playwright a la URL local de la pantalla auditada.
    2. Comprobar que no existan excepciones HTTP 500, pantallas rojas ni caídas de Livewire.
    3. Ejecutar `browser-logs` y corroborar que la consola JavaScript permanezca 100% limpia de errores o advertencias de Alpine.js y Livewire.
    4. Ejecutar `last-error` o `read-log-entries` para certificar que `laravel.log` no tenga excepciones registradas durante la prueba.
    5. Probar clics en modales, switches y botones principales.
    6. 🚫 **BLOQUEO**: Si se detectan anomalías en `browser-logs` o `last-error`, deben resolverse antes de proceder.
  - 🚫 **GUARDIA ESTRICTA DE DOBLE VERIFICACIÓN**: La pre-verificación técnica del agente **NUNCA** sustituye la prueba manual de Gabriel. La pausa para validación humana es obligatoria e inviolable.
  - **Preparación del Entorno de QA**:
    - **En Modo Local**:
      Emitir el **Informe de Auditoría Técnica** y la **Guía de QA Manual Paso a Paso** apuntando directamente a `http://localhost:80` (o `http://localhost`). No se compila ni se levanta el túnel.
    - **En Modo Remoto**:
      1. Compilar assets para que se rendericen de forma autónoma en dispositivos móviles:
         ```bash
         ./vendor/bin/sail npm run build
         ```
      2. Iniciar el túnel seguro de Cloudflare:
         ```bash
         ./scripts/tunnel.sh start
         ```
      3. Redactar el Informe y la Guía encabezados por el enlace del túnel:
         ```markdown
         🌐 **Túnel Activo para Pruebas (Móvil / Remoto)**:
         🔗 https://<subdominio>.trycloudflare.com
         ```
    - **Alternancia Dinámica**: Si se inició en Local y Gabriel solicita en cualquier momento *"activa el túnel"* o *"lo pruebo en el celular"*, Antigravity compila y levanta el túnel al instante (y viceversa para apagarlo).
* 🚫 **PAUSA OBLIGATORIA**: Detente. No comitees ni fusiones directamente.
* 🚫 **PAUSA**: Espera el "OK" explícito de Gabriel tras sus pruebas en el navegador (local o remoto).

### 6. Ciclo Iterativo ante Observaciones o Ajustes de Gabriel
* Si Gabriel solicita ajustes o cambios tras probar en el navegador:
  1. Redacta y presenta el **Plan Técnico de Ajuste**.
  2. 🚫 **Pausa**: Espera el "OK" explícito de Gabriel sobre el plan antes de tocar código.
  3. Implementa el código y asegura 100% de tests automatizados en verde.
  4. Recompilar assets (`sail npm run build`) únicamente si se está en Modo Remoto y hubo cambios visuales.
  5. 🚫 **Prohibición de avanzar solo**: NO hagas commits ni modifiques changelog.
  6. Presenta la **Guía de QA Manual Paso a Paso (Re-validación)** confirmando la disponibilidad del entorno.
  7. 🚫 **Pausa**: Espera el "OK" definitivo de Gabriel confirmando sus pruebas manuales.

### 7. Documentación, Cierre de Túnel (Si Aplica), Merge y Limpieza Total
* Recién tras el "OK" definitivo de Gabriel:
  1. Si el túnel estaba activo (Modo Remoto), apagarlo:
     ```bash
     ./scripts/tunnel.sh stop
     ```
  2. Registra la entrada en `docs/changelog.md` bajo `## [Unreleased]`, actualiza `docs/api_contract.md` y `docs/database_dictionary.md` si aplica, haz commit y push a la rama.
  3. Fusiona la Pull Request hacia `develop` (`gh pr merge <id> --merge`).
  4. Ejecuta la limpieza local y **elimina la rama remota en GitHub ("rama basura")**:
     ```bash
     git checkout develop
     git pull origin develop
     git branch -d <nombre-rama>
     git push origin --delete <nombre-rama>
     git fetch --prune
     ```

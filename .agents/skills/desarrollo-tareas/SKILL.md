---
name: desarrollo-tareas
description: Guía y flujo operativo paso a paso para el desarrollo e implementación de tareas técnicas en clothes-system con Gabriel. Usar al iniciar o trabajar en cualquier tarea (TASK-X).
---

# 🚀 Skill: Desarrollo e Implementación de Tareas
 
Este skill guía a Antigravity en la ejecución paso a paso del ciclo de vida de desarrollo de cualquier tarea técnica junto a Gabriel.

## 🔄 Flujo Operativo Paso a Paso

### 1. Etapa de Planificación, Detección de Modo y Creación de Rama
* **Detección de Modo de Trabajo (Local vs. Remoto)**:
  - Antigravity detecta si Gabriel trabajará en **Modo Local** (ej. "en local", "en la pc") o en **Modo Remoto** (ej. "en remoto", "desde el celular").
  - Si Gabriel no lo especificó en su mensaje inicial, Antigravity **debe consultarlo explícitamente antes de generar el plan**: *"¿Trabajaremos en Modo Local o en Modo Remoto?"*.
  - El modo acordado se declara explícitamente en el encabezado del Plan Técnico.
* **Consulta en Tiempo Real de Jira Cloud**: Antigravity recibe la clave de la tarea (`UT-xxx` o `TASK-xxx`) y consulta vía API REST v3 (`.env.jira`) el título y alcance actualizado.
* **Inspección Obligatoria con Laravel Boost MCP**:
  - Ejecutar `database-schema` para consultar tablas, tipos de datos, índices y foreign keys antes de proponer cambios estructurales.
  - Ejecutar `database-query` (SELECT) para corroborar datos reales y el aislamiento `tenant_id`.
  - Usar `search-docs` para confirmar la sintaxis oficial de Laravel, Livewire y Pest antes de redactar el código.
* Formula un Plan Técnico descriptivo y estructurado:
  1. Modo de Trabajo acordado (Local o Remoto).
  2. Nombre de la rama generada a partir de la incidencia de Jira.
  3. Componentes, modelos y vistas a modificar o crear.
  4. Modificaciones a la base de datos o rutas (verificar `docs/database_dictionary.md` y `docs/api_contract.md`).
  5. Estrategia de pruebas TDD en Pest (unitarias, integración, multi-tenant).
* 🚫 **PAUSA**: Espera el "OK" explícito de Gabriel antes de codificar o crear ramas.
* **Creación y Push de la Rama en GitHub**:
  Tras recibir el "OK", ejecuta el script automatizado que sincroniza `develop`, crea la rama normalizada y la empuja a GitHub, vinculándola de inmediato al tablero de Jira:
  ```bash
  ./vendor/bin/sail artisan jira:create-branch <CLAVE>
  ```

### 2. Etapa de Desarrollo, TDD y QA Condicional (Local vs. Remoto)
* Implementa la lógica siguiendo los patrones del proyecto (Laravel 11, Livewire 3, Alpine.js, Tailwind CSS).
* **Inspección Visual de Referencia (/browser y Playwright)**:
  - Durante el maquetado de vistas Blade y componentes Livewire, Antigravity **debe utilizar `/browser` o el servidor MCP de Playwright** para navegar a pantallas hermanas en `http://localhost` (ej. tablas de listado, modales de confirmación, badges de estado).
  - Extraer y replicar patrones exactos de Tailwind CSS (paletas, espaciados, paddings) y Heroicons SVG para garantizar consistencia visual absoluta sin trabajar a ciegas.
* **Estándar de Iconografía Frontend**: En vistas Blade, componentes Livewire, modales y botones, utiliza exclusivamente **SVGs vectoriales de Heroicons** con clases de Tailwind CSS (`w-4 h-4` o `w-5 h-5`, `stroke="currentColor"`, `stroke-width="2"`, `shrink-0`). **PROHIBIDO** el uso de emojis de texto o caracteres tipográficos sustitutos.
* Escribe la suite TDD con Pest cubriendo escenarios positivos, negativos, edge cases y aislamiento multi-tenant.
* Ejecuta `./vendor/bin/sail artisan test <archivo-test>` y luego la suite global.
* **Pre-Verificación Técnica Obligatoria en Navegador (Smoke Testing con Boost MCP)**:
  Tras el 100% verde en Pest y antes de involucrar a Gabriel:
  1. Navegar con `/browser` o Playwright a la ruta local de la funcionalidad (`http://localhost/...`).
  2. Verificar ausencia de errores HTTP 500, pantallas de excepción de Laravel o caídas de Livewire.
  3. Ejecutar `browser-logs` y validar que la consola JavaScript permanezca 100% limpia de errores y advertencias críticas de Alpine.js y Livewire.
  4. Ejecutar `last-error` o `read-log-entries` y constatar que no haya excepciones registradas en `laravel.log`.
  5. Probar interacciones primarias (abrir/cerrar modales, toggles o clics principales).
  6. 🚫 **BLOQUEO**: Si `browser-logs` o `last-error` reportan anomalías, corregirlas de inmediato antes de presentar la guía a Gabriel.
* **Preparación para Pruebas Manuales de Gabriel (QA)**:
  - 🚫 **GUARDIA ESTRICTA DE DOBLE VERIFICACIÓN**: La pre-verificación técnica del agente **NUNCA** sustituye la prueba manual de Gabriel. La pausa para validación humana es obligatoria e inviolable.
  - **En Modo Local**:
    Presentar directamente la **Guía de Pruebas Manuales Paso a Paso** apuntando a `http://localhost:80` (o `http://localhost`). No se compila ni se levanta el túnel.
  - **En Modo Remoto**:
    1. Compilar assets estáticos para que funcionen en el móvil sin el dev server de Vite:
       ```bash
       ./vendor/bin/sail npm run build
       ```
    2. Levantar el túnel seguro de Cloudflare:
       ```bash
       ./scripts/tunnel.sh start
       ```
    3. Redactar y entregar en el chat la **Guía de Pruebas Manuales Paso a Paso**, encabezada por el enlace del túnel:
       ```markdown
       🌐 **Túnel Activo para Pruebas (Móvil / Remoto)**:
       🔗 https://<subdominio>.trycloudflare.com
       ```
  - **Alternancia Dinámica**: Si se inició en Local y Gabriel solicita en cualquier momento *"activa el túnel"* o *"lo pruebo en el celular"*, Antigravity compila y levanta el túnel al instante (y viceversa para apagarlo).
* 🚫 **PAUSA OBLIGATORIA**: Detén la ejecución. **PROHIBIDO** editar `docs/changelog.md` o preparar commits antes de que Gabriel pruebe en el navegador (local o remoto) y dé su "OK" explícito.
* 🔄 **Ciclo Iterativo ante Observaciones**:
  - Si Gabriel solicita ajustes, presenta el **Plan Técnico de Ajuste**, espera el "OK", implementa, corre tests, repite el smoke test en browser (recompilando con `sail npm run build` solo si se está en Modo Remoto y hubo cambios visuales).
  - Re-entrega la **Guía de QA Manual Paso a Paso** confirmando la disponibilidad del entorno.
* **Cierre del Túnel (Solo en Modo Remoto)**:
  Tras recibir el "OK" definitivo de QA de Gabriel, si el túnel estaba activo, apaga el servicio antes de proceder:
  ```bash
  ./scripts/tunnel.sh stop
  ```

### 3. Etapa de Documentación y Commit (Solo tras el OK de QA)
* Actualiza `docs/changelog.md` bajo `## [Unreleased]`.
* Si aplica, actualiza `docs/database_dictionary.md` y `docs/api_contract.md`.
* Realiza el commit en español con Conventional Commits:
  - **PROHIBIDO** incluir la clave de la HU en el mensaje del commit (ej. solo usar `feat(tasks): ... (TASK-114 / UT-193)`).
* Sube la rama a GitHub: `git push origin <rama>`.

### 4. Etapa de Pull Request, Fusión y Limpieza Total
* Crea la Pull Request hacia `develop` con `gh pr create` incluyendo resumen técnico y checklist de verificación.
* Comprueba que no existan conflictos (`gh pr view`).
* Si todo está correcto y sin conflictos, fusiona automáticamente la Pull Request hacia `develop`:
  ```bash
  gh pr merge <id> --merge
  ```
* Realiza de inmediato la limpieza local y remota eliminando la rama de trabajo:
  ```bash
  git checkout develop
  git pull origin develop
  git branch -d <nombre-rama>
  git push origin --delete <nombre-rama>
  git fetch --prune
  ```
* Confirma a Gabriel el cierre exitoso y estado limpio de `develop`.

---

## 🚀 Protocolo de Despliegue a Producción (develop ➔ main)

Ante la solicitud de despliegue a producción de Gabriel:
1. **Creación Automática de PR (`develop` ➔ `main`)**:
   - `gh pr create --base main --head develop` con resumen detallado de entregas, migraciones de base de datos, variables `.env` y comandos SSH.
2. **Fusión en GitHub (Rol de Gabriel)**:
   - Gabriel aprueba y fusiona la PR en GitHub, disparando el workflow de GitHub Actions FTP hacia Hostinger.
3. **Comandos en Servidor SSH (Hostinger)**:
   ```bash
   cd ~/public_html
   php artisan migrate --force
   php artisan optimize:clear
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan queue:restart
   ```
4. **Smoke Testing**: Verificación de estabilidad y HTTP 200 en producción.

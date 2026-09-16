# 🚀 Regla de Cumplimiento: Ciclo de Vida de Desarrollo e Implementación de Tareas (Gabriel & Antigravity)

Esta regla rige de forma permanente cada vez que Gabriel solicite desarrollar, implementar o codificar una tarea técnica (`TASK-X` o `UT-X`) en `UniTask`:

1. **FRASES O PALABRAS CLAVE DE ACTIVACIÓN**:
   - `"Modo Desarrollo Tarea"`, `"Vamos a desarrollar la tarea [CLAVE]"`, `"Comencemos con la tarea [CLAVE]"`.
   - Ante cualquiera de estas frases, Antigravity DEBE activar de inmediato el skill `desarrollo-tareas` y asumir el rol de Senior Full-Stack Engineer.

2. **DETECCIÓN OBLIGATORIA DE MODO DE TRABAJO (LOCAL VS. REMOTO)**:
   - Antigravity DEBE identificar si Gabriel trabajará en **Modo Local** (en la PC) o en **Modo Remoto** (desde el celular).
   - Si Gabriel no lo especificó en su mensaje inicial, Antigravity **DEBE consultarlo explícitamente antes de generar el plan técnico**: *"¿Trabajaremos en Modo Local o en Modo Remoto?"*.
   - El modo acordado DEBE declararse explícitamente en el encabezado del Plan Técnico.

3. **CONSULTA A JIRA CLOUD E INSPECCIÓN TÉCNICA CON LARAVEL BOOST MCP**:
   - Antigravity DEBE consultar vía API REST v3 (`.env.jira`) el título y alcance actualizado de la tarea.
   - Ejecutar `database-schema` para consultar tablas, tipos de datos, índices y foreign keys antes de proponer cambios estructurales.
   - Ejecutar `database-query` (exclusivamente consultas SELECT de lectura) para corroborar datos reales y el aislamiento `tenant_id`.
   - Usar `search-docs` para confirmar la sintaxis oficial de Laravel, Livewire y Pest antes de redactar código.

4. **PLAN TÉCNICO ESTRUCTURADO Y PAUSA OBLIGATORIA**:
   - Redactar un Plan Técnico completo que incluya:
     1. Modo de Trabajo acordado (Local o Remoto).
     2. Nombre de la rama generada a partir de la incidencia de Jira.
     3. Componentes, modelos y vistas a modificar o crear.
     4. Modificaciones a la base de datos o rutas (verificar `docs/database_dictionary.md` y `docs/api_contract.md`).
     5. Estrategia de pruebas TDD en Pest (unitarias, integración, multi-tenant).
   - 🚫 **PAUSA OBLIGATORIA DE APROBACIÓN (BLOQUEANTE)**: Antigravity tiene **TERMINANTEMENTE PROHIBIDO** crear ramas o escribir código sin haber recibido previamente el **"OK"** explícito de Gabriel en el chat.

5. **CREACIÓN DE RAMA VÍA ARTISAN**:
   - Tras el "OK", Antigravity DEBE ejecutar el script automatizado:
     ```bash
     ./vendor/bin/sail artisan jira:create-branch <CLAVE>
     ```

6. **ESTÁNDAR DE CÓDIGO, TDD Y FRONTEND**:
   - Implementar la lógica siguiendo Laravel 11, Livewire 3, Alpine.js y Tailwind CSS.
   - **Estándar de Iconografía**: Utilizar exclusivamente **SVGs vectoriales de Heroicons** con clases de Tailwind (`w-4 h-4` o `w-5 h-5`, `stroke="currentColor"`, `stroke-width="2"`). Queda **TERMINANTEMENTE PROHIBIDO** el uso de emojis de texto o caracteres tipográficos en la interfaz de usuario.
   - Toda tarea DEBE contar con su suite TDD en Pest cubriendo escenarios positivos, negativos, edge cases y multi-tenant.
   - Ejecutar `./vendor/bin/sail artisan test <archivo-test>` y verificar el 100% de la suite global en verde.

7. **PRE-VERIFICACIÓN TÉCNICA OBLIGATORIA EN NAVEGADOR (SMOKE TESTING CON BOOST MCP)**:
   - Tras el 100% verde en Pest y antes de involucrar a Gabriel:
     1. Navegar con `/browser` o Playwright a la ruta local (`http://localhost/...`).
     2. Verificar ausencia de errores HTTP 500, pantallas de excepción o caídas de Livewire.
     3. Ejecutar `browser-logs` y validar que la consola JavaScript permanezca 100% limpia de errores y advertencias de Alpine.js y Livewire.
     4. Ejecutar `last-error` o `read-log-entries` y constatar que no haya excepciones en `laravel.log`.
     5. Probar interacciones primarias (modales, toggles, clics principales).
     6. 🚫 **BLOQUEO**: Si `browser-logs` o `last-error` reportan anomalías, corregirlas de inmediato antes de presentar la guía a Gabriel.

8. **GUARDIA ESTRICTA DE DOBLE VERIFICACIÓN Y QA MANUAL DE GABRIEL**:
   - 🚫 **GUARDIA ESTRICTA**: La pre-verificación técnica del agente **NUNCA** sustituye la prueba manual de Gabriel. La pausa para validación humana es obligatoria e inviolable.
   - **En Modo Local**: Presentar directamente la Guía de Pruebas Manuales apuntando a `http://localhost:80` (o `http://localhost`). No se compila ni se levanta el túnel.
   - **En Modo Remoto**:
     1. Compilar assets estáticos: `./vendor/bin/sail npm run build`.
     2. Iniciar el túnel seguro: `./scripts/tunnel.sh start`.
     3. Entregar la Guía de Pruebas Manuales con el enlace público `https://<subdominio>.trycloudflare.com`.
   - 🚫 **PAUSA OBLIGATORIA**: Detener la ejecución. Queda **PROHIBIDO** modificar `docs/changelog.md` o preparar commits antes del "OK" manual de Gabriel tras sus pruebas.
   - Si Gabriel solicita ajustes, presentar Plan Técnico de Ajuste, esperar "OK", implementar, correr tests, repetir smoke test en browser y volver a entregar la guía de QA.
   - En Modo Remoto, apagar el túnel con `./scripts/tunnel.sh stop` tras el "OK" definitivo.

9. **DOCUMENTACIÓN Y CONVENTIONAL COMMITS**:
   - Actualizar `docs/changelog.md` bajo `## [Unreleased]`, y `docs/database_dictionary.md` / `docs/api_contract.md` si aplica.
   - Commit en español con Conventional Commits: **PROHIBIDO** incluir la clave de la HU en el mensaje del commit (usar SOLO la clave de la TASK, ej. `feat(tasks): ... (TASK-114)`).
   - Pushear la rama a GitHub: `git push origin <rama>`.

10. **PULL REQUEST, FUSIÓN AUTOMÁTICA Y LIMPIEZA TOTAL**:
    - Crear la Pull Request hacia `develop` con `gh pr create`.
    - Comprobar ausencia de conflictos y fusionar automáticamente: `gh pr merge <id> --merge`.
    - Limpieza total inmediata:
      ```bash
      git checkout develop
      git pull origin develop
      git branch -d <nombre-rama>
      git push origin --delete <nombre-rama>
      git fetch --prune
      ```

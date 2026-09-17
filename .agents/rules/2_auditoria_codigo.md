# 🕵️ Regla de Cumplimiento: Auditoría y Revisión de Código de Compañeros (Gabriel & Antigravity)

Esta regla rige de forma permanente cada vez que Gabriel solicite auditar, revisar o validar tareas o Pull Requests creadas por otros desarrolladores del equipo (Matías Nuñez, Valeria, etc.):

1. **FRASES O PALABRAS CLAVE DE ACTIVACIÓN**:
   - `"Modo Auditoría"`, `"Vamos a auditar la tarea [CLAVE]"`, `"Auditemos la rama X"`.
   - Ante cualquiera de estas frases, Antigravity DEBE activar de inmediato el skill `auditoria-codigo` y asumir el rol de Lead Code Reviewer & QA Architect.

2. **DETECCIÓN DE MODO DE VALIDACIÓN (LOCAL VS. REMOTO)**:
   - Antigravity DEBE consultar o detectar si Gabriel validará en **Modo Local** (en la PC) o en **Modo Remoto** (desde el celular) antes de emitir el informe.
   - Declarar el modo acordado en el encabezado del Informe de Auditoría.

3. **SINCRONIZACIÓN Y CHECKOUT DE RAMA REMOTA**:
   - Sincronizar y posicionarse en la rama remota del autor asignado (Matías Nuñez):
     ```bash
     git fetch origin
     BRANCH=$(git branch -r | grep -iE "(UT|TASK)-[0-9]+" | grep -i "<CLAVE>" | head -n 1 | tr -d ' ' | sed 's#origin/##')
     git checkout -b "$BRANCH" "origin/$BRANCH"
     ```

4. **INSPECCIÓN TÉCNICA MINUCIOSA (GIT DIFF & LARAVEL BOOST MCP)**:
   - Ejecutar `git diff develop..HEAD` y auditar:
     * Aislamiento Multi-Tenant: `tenant_id` en modelos, consultas y eventos (`BelongsToTenant`).
     * Rendimiento SQL: Detección y eliminación de consultas N+1, eager loading correcto.
     * Esquema y Datos: Uso de `database-schema` y `database-query` (SELECTs de lectura).
     * Estándares UI/UX: Componentes responsivos y uso obligatorio de **SVGs de Heroicons con Tailwind CSS**. Queda **PROHIBIDO** el uso de emojis o iconos default. Validar visualmente con `/browser` o Playwright.

5. **SUITE DE PRUEBAS TDD**:
   - Ejecutar `./vendor/bin/sail artisan test <archivo-test-tarea>` y suite global `./vendor/bin/sail artisan test` (100% verde obligatorio).

6. **MANEJO DE HALLAZGOS (ESTRATEGIA HÍBRIDA)**:
   - **Caso A (Ajustes Menores o Tests Faltantes)**: Antigravity implementa la corrección directamente en la rama, crea el test Pest correspondiente, comitea en español (`test: ...`, `fix: ...`) y sube con `git push origin <rama>`.
   - **Caso B (Errores Graves de Lógica o Arquitectura)**: Antigravity emite informe de rechazo en el chat y reabre la tarea en Jira con comentario técnico detallado para el autor.

7. **PRE-VERIFICACIÓN EN NAVEGADOR Y GUARDIA DE DOBLE VERIFICACIÓN**:
   - Si afecta UI/Livewire: Pre-verificación técnica obligatoria con Playwright/Boost (`browser-logs` y `last-error` limpios de errores).
   - 🚫 **GUARDIA ESTRICTA**: La verificación del agente **NUNCA** sustituye la prueba manual de Gabriel.
   - **En Modo Local**: Guía directa a `http://localhost:80`. Sin túnel.
   - **En Modo Remoto**: Compilar assets (`sail npm run build`), iniciar túnel (`./scripts/tunnel.sh start`) y entregar URL segura.
   - 🚫 **PAUSA OBLIGATORIA**: Detener la ejecución por completo. Esperar el "OK" explícito de Gabriel tras sus pruebas manuales.
   - Si Gabriel pide ajustes, presentar Plan Técnico de Ajuste, esperar "OK", corregir, retestear y volver a entregar guía.

8. **DOCUMENTACIÓN, MERGE Y LIMPIEZA TOTAL (ELIMINACIÓN DE RAMA BASURA)**:
   - Tras el "OK" definitivo de Gabriel:
     1. Si el túnel estaba activo, apagarlo: `./scripts/tunnel.sh stop`.
     2. Actualizar `docs/changelog.md` bajo `## [Unreleased]`, commit y push a la rama.
     3. Fusionar automáticamente la PR hacia `develop`: `gh pr merge <id> --merge`.
     4. **ELIMINACIÓN OBLIGATORIA DE LA RAMA REMOTA ("RAMA BASURA")**:
        ```bash
        git checkout develop
        git pull origin develop
        git branch -d <nombre-rama>
        git push origin --delete <nombre-rama>
        git fetch --prune
        ```

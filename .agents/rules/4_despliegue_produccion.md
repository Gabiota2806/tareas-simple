# 🚀 Regla de Cumplimiento: Protocolo Seguro de Despliegue a Producción (Gabriel & Antigravity)

Esta regla rige de forma permanente cada vez que Gabriel solicite realizar un despliegue a producción de `develop` hacia `main`:

1. **FRASES O PALABRAS CLAVE DE ACTIVACIÓN**:
   - `"Modo Deploy"` o `"Vamos a desplegar a producción"`.
   - Ante cualquiera de estas frases, Antigravity DEBE activar de inmediato el flujo de despliegue seguro guiando a Gabriel paso a paso.

2. **CHECKLIST PRE-DEPLOY OBLIGATORIO (BLOQUEANTE)**:
   - **Comprobación de Rama**: Asegurar estar en `develop` y con el árbol de trabajo 100% limpio y sincronizado con `origin/develop`.
   - **Suite Global de Pruebas**: Ejecutar `./vendor/bin/sail artisan test` o `./vendor/bin/sail bin pest` certificando el 100% en verde. NUNCA desplegar con tests fallidos.
   - **Guardia Anti-Destructiva**: Prohibición absoluta de instrucciones `migrate:fresh`, `db:wipe` o scripts destructivos. Comprobar que todas las migraciones sean puramente incrementales.
   - **Compilación de Assets**: Validar que la compilación de Vite (`./vendor/bin/sail npm run build`) pase sin errores de empaquetado.

3. **CONTROL Y TRAZABILIDAD DE VARIABLES DE ENTORNO (`.env`)**:
   - Antigravity DEBE revisar si hay claves nuevas agregadas a `.env.example`.
   - DEBE presentar a Gabriel la lista explícita de variables que deben ser cargadas en el archivo `.env` del servidor en Hostinger antes de autorizar el merge en GitHub.

4. **CREACIÓN Y FUSIÓN AUTOMÁTICA DE PULL REQUEST `develop ➔ main`**:
   - Antigravity DEBE crear la Pull Request hacia `main` usando `gh pr create` con una estructura documental completa:
     * Título formal del Release (ej. `Release vX.Y.Z: Despliegue de Sprint N a Producción`).
     * Resumen técnico detallado de todas las Historias de Usuario y Subtareas incluidas.
     * Detalle de las nuevas migraciones incrementales que se ejecutarán.
     * Tabla de nuevas variables de entorno requeridas en `.env`.
     * Checklist de verificación de calidad.
   - Antigravity DEBE verificar la ausencia de conflictos y **fusionar automáticamente la Pull Request hacia `main` (`gh pr merge <id> --merge`)**, disparando de forma desatendida el pipeline de CI/CD en GitHub Actions.

5. **DESPLIEGUE AUTOMATIZADO Y SEGURO (CI/CD EN GITHUB ACTIONS)**:
   - Al fusionarse la PR, GitHub Actions ejecuta el workflow `.github/workflows/deploy.yml`.
   - **Monitoreo con GitHub MCP / gh CLI**: Tras la fusión, Antigravity DEBE supervisar la ejecución del workflow `deploy.yml`, reportando el estado a Gabriel hasta certificar la conclusión exitosa.

6. **SMOKE TESTING POST-DEPLOY**:
   - Verificación de estado HTTP 200 en la URL de producción, acceso al login y renderizado de estilos de Vite.

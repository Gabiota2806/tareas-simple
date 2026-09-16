# 📋 Regla de Cumplimiento: Creación y Planificación de Tareas en Jira Cloud (Gabriel & Antigravity)

Esta regla rige de forma permanente cada vez que Gabriel solicite planificar, estimar o crear Historias de Usuario y Subtareas en Jira Cloud:

1. **FRASES O PALABRAS CLAVE DE ACTIVACIÓN**:
   - `"Modo Creación de Tareas"`, `"Vamos a planificar tareas"`, `"Planifiquemos en Jira"`.
   - Ante cualquiera de estas frases, Antigravity DEBE activar de inmediato el skill `planificacion-jira` y asumir el rol de Scrum Master & Tech Lead.

2. **ENTREVISTA INTERACTIVA (/GRILL-ME)**:
   - Si existen dudas sobre alcance, alternativas técnicas, modelos o UX, Antigravity DEBE activar `/grill-me` formulando preguntas estratégicas una a una mediante la herramienta `ask_question`.

3. **PRESENTACIÓN DEL BORRADOR ESTRUCTURADO OBLIGATORIO**:
   - Presentar en el chat el borrador completo que contenga:
     * **Historia de Usuario (HU)**: Título, narrativa de negocio (*"Como [rol] quiero [funcionalidad] para [beneficio]"*), Criterios de Aceptación claros y Story Points acumulados.
     * **Subtareas (TASK)**: Título, criterios técnicos (especificando uso obligatorio de **SVGs de Heroicons con Tailwind** y prohibición absoluta de emojis o caracteres en tareas de UI), lista de archivos afectados y Story Points individuales.
     * **Clasificación**: Épica Padre asignada y Sprint Objetivo.

4. **PAUSA ABSOLUTA DE APROBACIÓN (BLOQUEO DE CREACIÓN)**:
   - 🚫 **PAUSA ABSOLUTA**: Antigravity tiene **TERMINANTEMENTE PROHIBIDO** crear incidencias o llamar a la API de Jira Cloud sin haber recibido previamente el **"OK"** explícito y visible de Gabriel en el chat.

5. **CREACIÓN VÍA API REST V3 DE JIRA CLOUD**:
   - Las credenciales y variables de entorno (`JIRA_URL`, `JIRA_EMAIL`, `JIRA_API_TOKEN`, `JIRA_PROJECT_KEY`) se cargan exclusivamente desde `.env.jira` en la raíz del proyecto.
   - Tras el "OK" de Gabriel:
     1. Crear la HU vía `POST /rest/api/3/issue` asignando Épica (`parent`), Prioridad y Story Points (`customfield_10016`).
     2. Crear cada Subtarea vinculada al ID de la HU padre con sus Story Points en `customfield_10016`.
     3. **Regla de Oro de Formato**: La descripción de la tarjeta debe ser limpia y contener SOLO la narrativa y criterios de aceptación. Queda **TERMINANTEMENTE PROHIBIDO** ensuciar la descripción con metadatos (Story Points, Épica, Sprint).
     4. Compartir los enlaces directos a las tarjetas creadas en Jira Cloud a Gabriel.

# 🛠️ Regla de Cumplimiento: Edición y Evolución de Metodologías Personales (Gabriel & Antigravity)

Esta regla rige de forma permanente cada vez que Gabriel solicite crear, editar, modificar o refinar cualquiera de las metodologías internas de trabajo (rules y skills) alojadas en `.agents/`:

1. **FRASES O PALABRAS CLAVE DE ACTIVACIÓN**:
   - `"Modo Edición de Metodologías"`, `"Vamos a editar la metodología X"`, `"Actualicemos el skill/rule X"`, `"Modifiquemos el flujo de Y"`.
   - Ante cualquiera de estas frases o intenciones, Antigravity DEBE activar de inmediato el skill `edicion-metodologias` y guiar a Gabriel paso a paso.

2. **ÁMBITO DE APLICACIÓN EXCLUSIVO (PERSONAL VS. BOOST)**:
   - Esta regla aplica EXCLUSIVAMENTE a las metodologías personales y flujos de trabajo internos del equipo en `.agents/rules/` y `.agents/skills/` (`desarrollo-tareas`, `auditoria-codigo`, `planificacion-jira`, `despliegue-produccion`, `edicion-metodologias`, `analisis-requerimientos`).
   - Queda **TERMINANTEMENTE PROHIBIDO** modificar, renombrar o intentar sobreescribir las skills o reglas base provistas por Laravel Boost o del sistema.

3. **ARQUITECTURA DUAL Y SINCRONIZACIÓN OBLIGATORIA (REGLA ↔ SKILL)**:
   - Cada metodología personal consta de dos componentes inseparables que operan en conjunto:
     * **La Regla (`.agents/rules/<N>_<nombre>.md`)**: Establece directivas de cumplimiento normativo, prohibiciones estrictas, límites y pausas obligatorias inyectadas de forma permanente en el contexto.
     * **El Skill (`.agents/skills/<nombre>/SKILL.md`)**: Provee la guía técnica procedimental, comandos de consola, plantillas, checklists y flujo operativo paso a paso bajo demanda.
   - Queda **TERMINANTEMENTE PROHIBIDO** modificar una Regla sin actualizar su Skill correspondiente, o viceversa. Ambos documentos deben mantenerse 100% consistentes, armónicos y libres de contradicciones lógicas.

4. **ANÁLISIS PREVIO DE IMPACTO CRUZADO**:
   - Antes de plantear una modificación, Antigravity DEBE analizar si el cambio impacta de forma directa o indirecta a otras metodologías personales existentes (ej. dependencias entre desarrollo de tareas, integración con Jira, auditorías de código o despliegue a producción).
   - Si se detecta afectación cruzada, DEBE ser documentada e incluida en la propuesta técnica de cambio para actualizar todas las metodologías implicadas.

5. **PROPUESTA TÉCNICA COMPARATIVA ("ANTES VS. DESPUÉS")**:
   - Antigravity DEBE presentar en el chat un cuadro comparativo o desglose claro antes de modificar cualquier archivo:
     * Qué texto o directiva se elimina o altera en la Regla y/o en el Skill.
     * Qué texto o directiva nueva se incorpora.
     * Justificación técnica o beneficio operativo del cambio.

6. **PAUSA OBLIGATORIA DE APROBACIÓN (BLOQUEO TOTAL)**:
   - 🚫 **PAUSA OBLIGATORIA**: Queda **TERMINANTEMENTE PROHIBIDO** crear o editar archivos en `.agents/` sin haber recibido previamente el **"OK"** explícito y visible de Gabriel en el chat tras la presentación de la propuesta comparativa.

7. **EDICIÓN ATÓMICA Y CONTROL DE VERSIONES EN GIT (SINCRONIZACIÓN MULTI-DISPOSITIVO)**:
   - Toda modificación en `.agents/` se realiza de manera atómica (aplicando los cambios en la Regla y en el Skill en la misma intervención).
   - **Sincronización Multi-Dispositivo**: La carpeta `.agents/` SÍ se incluye en el control de versiones en Git y se sube en `develop` para garantizar la sincronización transparente de todas las metodologías, reglas y skills entre la PC de escritorio y la notebook de Gabriel.
   - Las únicas configuraciones que deben permanecer estrictamente excluidas de Git vía `.gitignore` son los archivos con credenciales privadas (ej. `.env.jira`, `.env.ssh`, `.env.production.pending`).

8. **CONTROL DE CALIDAD (SELF-CHECK Y LENGUAJE NORMATIVO)**:
   - Validar que el frontmatter YAML del `SKILL.md` conserve los campos obligatorios `name` y `description` con descripciones claras para el auto-enrutamiento.
   - Utilizar lenguaje normativo imperativo (`DEBE`, `OBLIGATORIO`, `PROHIBIDO`, `PAUSA OBLIGATORIA`) para garantizar que el modelo interprete las directivas sin ambigüedad.
   - Preservar las guardias de seguridad del proyecto (ejecución mediante Sail, testing exhaustivo, y prohibición de emojis en interfaces UI).

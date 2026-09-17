---
name: edicion-metodologias
description: Guía y flujo operativo paso a paso para editar, actualizar y evolucionar de forma segura las metodologías personales (skills y rules) del equipo en .agents/. Usar ante frases como "Modo Edición de Metodologías", "Vamos a editar la metodología X" o solicitudes de ajustar reglas y flujos internos.
---

# 🛠️ Skill: Edición y Evolución de Metodologías Personales

Este skill guía a Antigravity en el proceso seguro, consistente y atómico para modificar, refinar o expandir las metodologías personales internas de trabajo acordadas con Gabriel.

---

## 🗺️ Mapa de Metodologías Personales del Proyecto

Cada metodología del equipo cuenta con una **arquitectura dual** compuesta por una Regla (inyectada en el prompt del sistema) y un Skill (cargado procedimentalmente bajo demanda):

| Identificador | Regla (`.agents/rules/`) | Skill (`.agents/skills/`) | Propósito Principal |
| :--- | :--- | :--- | :--- |
| `desarrollo-tareas` | `1_desarrollo_tareas.md` | `desarrollo-tareas/SKILL.md` | Ciclo de vida TDD, Jira, QA manual, PR y merge de tareas. |
| `auditoria-codigo` | `2_auditoria_codigo.md` | `auditoria-codigo/SKILL.md` | Auditoría de código de terceros, resolución de fallos y PRs. |
| `planificacion-jira` | `3_creacion_jira.md` | `planificacion-jira/SKILL.md` | Entrevista (/grill-me) y creación de HUs/TASKs por API REST v3. |
| `despliegue-produccion` | `4_despliegue_produccion.md` | `despliegue-produccion/SKILL.md` | Protocolo seguro de release `develop ➔ main`, CI/CD y servidor. |
| `edicion-metodologias` | `5_edicion_metodologias.md` | `edicion-metodologias/SKILL.md` | Gobierno, edición dual y evolución de las metodologías internas. |
| `analisis-requerimientos` | `6_analisis_requerimientos.md` | `analisis-requerimientos/SKILL.md` | Análisis técnico, maduración de ideas y generación de especificaciones para Jira. |

> [!IMPORTANT]
> **Skills de Laravel Boost**: Los skills provistos por el ecosistema (`laravel-best-practices`, `livewire-development`, `tailwindcss-development`, `testing-best-practices`, `infer-conventions`) son de referencia externa y **NO** forman parte de este flujo de edición.

---

## 🔄 Flujo Operativo Paso a Paso

### 1. Identificación y Diagnóstico del Cambio
* **Motivación**: Determinar la causa raíz del cambio (ej. optimizar un paso que causaba fricción, incorporar una nueva herramienta, clarificar una directiva ambigua, o endurecer una restricción de calidad/seguridad).
* **Alcance**: Identificar con exactitud cuál de las metodologías personales será el objetivo de la edición.

### 2. Análisis de Impacto Cruzado
* Evaluar si la modificación proyectada repercute en otras metodologías del equipo:
  * *Ejemplo*: Si se altera la política de commits o transiciones de Jira en `desarrollo-tareas`, comprobar si requiere ajustes correlativos en `auditoria-codigo` o `planificacion-jira`.
  * *Ejemplo*: Si se modifica el flujo de branches o merges, verificar que no interfiera con el checklist de `despliegue-produccion`.
* Si existe impacto cruzado, listar explícitamente todas las reglas y skills que deberán actualizarse en cascada.

### 3. Propuesta Técnica y Cuadro Comparativo ("Antes vs. Después")
* Antes de modificar cualquier archivo, Antigravity **debe** presentar en el chat un desglose comparativo transparente:
  ```markdown
  ### 📝 Propuesta de Modificación: [Nombre de la Metodología]

  #### 1. Cambios en la Regla (`.agents/rules/<archivo>.md`)
  * **Antes**: [Texto o directiva actual]
  * **Después**: [Nueva directiva redactada]
  * **Justificación**: [Motivo técnico o de flujo]

  #### 2. Cambios en el Skill (`.agents/skills/<archivo>/SKILL.md`)
  * **Antes**: [Paso o bloque operativo actual]
  * **Después**: [Nuevo paso o comandos redactados]
  * **Justificación**: [Motivo técnico o de flujo]
  ```

### 4. Pausa Obligatoria de Aprobación
* 🚫 **PAUSA OBLIGATORIA**: Detener la ejecución por completo.
* Antigravity **TIENE PROHIBIDO** tocar, crear o editar archivos en `.agents/` sin haber recibido previamente el **"OK"** explícito de Gabriel en el chat.

### 5. Aplicación Sincronizada y Atómica
* Una vez obtenido el "OK":
  1. Modificar el archivo de la Regla en `.agents/rules/`.
  2. Modificar el archivo del Skill en `.agents/skills/<directorio>/SKILL.md`.
  3. Si se identificó impacto cruzado en el Paso 2, aplicar los ajustes correspondientes en los archivos secundarios de `.agents/`.
* Ambos documentos deben quedar en perfecta sintonía y sin contradicciones lógicas.

### 6. Control de Calidad y Activación en Caliente
* **Sintaxis YAML**: Asegurar que el frontmatter de los skills mantenga `name` y `description` válidos.
* **Lenguaje Normativo para LLMs**: Utilizar términos imperativos claros (`DEBE`, `OBLIGATORIO`, `PROHIBIDO`, `PAUSA OBLIGATORIA`).
* **Sincronización Multi-Dispositivo (Git)**: La carpeta `.agents/` se versiona en Git en la rama `develop` para sincronizar las metodologías entre la PC de escritorio y la notebook de Gabriel. Las credenciales sensibles (`.env.jira`) permanecen protegidas por `.gitignore`.
* **Confirmación**: Notificar a Gabriel que la metodología fue actualizada y entra en vigencia de inmediato en el entorno local.

---

## ✍️ Buenas Prácticas para Redactar Reglas y Skills

1. **Imperatividad y Claridad**: Evitar ambigüedades como "se recomienda" o "podrías considerar". Usar "Antigravity DEBE...", "Queda PROHIBIDO...".
2. **Uso de Pausas Obligatorias**: Todo punto de control crítico (planes, aprobaciones de Jira, QA manual en navegador, modificaciones de reglas) DEBE tener una pausa explícita que bloquee la ejecución autónoma hasta la autorización humana.
3. **Comandos Explícitos**: Especificar siempre los comandos exactos de consola (ej. `./vendor/bin/sail artisan test`, `gh pr merge`, etc.).

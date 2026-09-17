---
name: planificacion-jira
description: Guía y flujo operativo paso a paso para la entrevista (/grill-me), estimación, refinamiento y creación automatizada de Historias de Usuario y Subtareas en el tablero de Jira Cloud vía API REST v3. Usar ante frases como "Modo Creación de Tareas" o "Vamos a planificar tareas".
---

# 📋 Skill: Creación y Planificación de Tareas en Jira Cloud

Este skill guía a Antigravity en la planificación, entrevista y creación automatizada de Historias de Usuario y Subtareas en Jira Cloud con Gabriel.

## 🔄 Flujo Operativo Paso a Paso

### 1. Entrevista Interactiva (`/grill-me`)
* Si existen dudas de alcance, alternativas técnicas o UX, activa `/grill-me` usando `ask_question` para formular preguntas precisas una a una.

### 2. Presentación del Borrador Estructurado
* Presenta el borrador obligatorio con:
  - **Historia de Usuario (HU)**: Título, narrativa de negocio (*"Como [rol] quiero [funcionalidad] para [beneficio]"*), Criterios de Aceptación y Story Points acumulados.
  - **Subtareas (TASK)**: Título, criterios técnicos (especificando uso obligatorio de **SVGs de Heroicons con Tailwind** y prohibición de emojis/caracteres en tareas de UI), archivos afectados y Story Points.
  - **Clasificación**: Épica Padre y Sprint Objetivo.

### 3. Refinamiento y Bloqueo de Creación
* Ajusta cualquier detalle que Gabriel solicite.
* 🚫 **PAUSA ABSOLUTA**: NUNCA crees tarjetas en Jira sin el "OK" explícito y visible de Gabriel en el chat.

### 4. Creación vía API REST v3 de Jira Cloud
* Las credenciales y variables de entorno (`JIRA_URL`, `JIRA_EMAIL`, `JIRA_API_TOKEN`, `JIRA_PROJECT_KEY`) se cargan exclusivamente desde el archivo `.env.jira` ubicado en la raíz del proyecto.
* Tras el OK de Gabriel:
  1. Crear la HU vía `POST /rest/api/3/issue` asignando Épica (`parent`), Prioridad y Story Points (`customfield_10016`).
  2. Asignar los Criterios de Aceptación en su campo dedicado `customfield_10108` ("Criterios de Aceptación") en formato Documento de Atlassian (ADF), dejando el campo `description` exclusivo para la narrativa de negocio (*"Como... Quiero... Para..."*).
  3. Crear cada Subtarea vinculada al ID de la HU padre con sus Story Points en `customfield_10016`.
  4. **Regla de Oro**: La descripción de la tarjeta debe ser limpia y contener SOLO la narrativa de negocio. NUNCA ensuciar la descripción con metadatos (Story Points, Épica, Sprint) ni duplicar allí los Criterios de Aceptación si el campo dedicado está disponible.
  5. Compartir los enlaces directos a las tarjetas creadas en Jira Cloud a Gabriel.

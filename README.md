# 📋 UniTask: Sistema de Gestión de Tareas Académicas

Bienvenido a la versión profesional de **UniTask**, desarrollada por el equipo **DevFusion** para la materia *Metodología de Sistemas I* (UTN FRRE - Sede Formosa, 2026). 

---

## 🌳 Estándares de Git y Flujo de Trabajo
Para garantizar la integridad del código, el equipo debe seguir estrictamente estas reglas de nomenclatura:

### Prefijos de Ramas
Toda rama de trabajo debe nacer de `backend-main` o `frontend-main` y nombrarse con el formato: `tipo/nro-tarea-descripcion`.

| Prefijo | Propósito | Ejemplo |
| :--- | :--- | :--- |
| `feat/` | Nuevas funcionalidades | `feat/UT-T-3.1-registro-tareas` |
| `fix/` | Corrección de errores | `fix/UT-T-4.2-error-prioridad` |
| `docs/` | Cambios en documentación | `docs/UT-T-1.2-update-readme` |
| `refactor/` | Mejora de código sin cambios funcionales | `refactor/UT-T-5.1-limpieza-css` |
| `test/` | Pruebas unitarias o QA | `test/UT-T-7.1-qa-logic` |

### Reglas de Oro
1. **No Commits Directos:** Está prohibido hacer commits directos a `main`, `develop`, `backend-main` o `frontend-main`.
2. **Pull Requests (PR):** Todo cambio debe ser revisado y aprobado por el líder del sub-equipo antes de ser integrado.
3. **Sincronización:** Antes de empezar a trabajar, asegúrate de estar en tu rama base y ejecutar `git pull`.

---

## 🛠️ Tecnologías y Stack
* **Backend:** Laravel 13.5 (PHP)
* **Base de Datos:** MySQL (unitask_db)
* **Frontend:** Blade Templates + Tailwind CSS (vía Vite)
* **Control de Versiones:** Git + GitHub (con Rulesets avanzados)

---

## ⚙️ Guía de Inicio para el Equipo

1. **Clonar el repositorio:**
   ```bash
   git clone [url-del-repo]
   cd unitask

## 🚀 Estado del Proyecto y Contexto Actual (Prototipo MVP)

**Fecha Objetivo del Prototipo:** 26 de Junio de 2026.

Actualmente, el proyecto se encuentra en una fase de desarrollo acelerado para presentar un MVP funcional a la cátedra. Debido a esto, se han establecido las siguientes directivas arquitectónicas:
1. **Desarrollo en Paralelo (Mocking):** El Frontend avanza maquetando vistas utilizando datos simulados (`fetch` locales) basados estrictamente en el `CONTRATO_API.md`, sin esperar a que el Backend finalice los endpoints.
2. **Simplificación de Funciones:** 
   - No se implementará paginación en el Backend (se devolverán arrays planos).
   - El sistema de "Subtareas Jerárquicas" (Épica 4.2) ha sido pospuesto para después de la presentación del prototipo.
3. **Módulo de Recuperación de Clave:** Activo y testeado usando Mailtrap como entorno seguro.

### 🏆 Sprints Completados
- **Sprint 1:** Infraestructura base, diagramas de BD y reglas de GitHub. (100% Finalizado)
- **Sprint 2:** Configuración de migraciones, Modelos base y Controladores de perfil de usuario. (100% Finalizado)
- **Sprint 3:** Autenticación, protección de rutas (Middleware), CRUD completo de asignaturas y vistas de registro/login adaptables. (100% Finalizado)

### 🚧 Sprints Actuales y Futuros
- **Sprint 4 (Actual):** Autenticación final, y carga inicial del módulo de Tareas (Frontend maquetando listados y Backend programando lógica y migraciones ampliadas).
- **Sprint 5:** Integración final Front/Back, pulido de UX/UI (Tailwind Mobile-First, SweetAlert2) y generación de datos de prueba (Seeders) para la demostración.
- **Sprint 6 y 7:** Post-prototipo (FullCalendar, Subtareas Jerárquicas y AJAX).

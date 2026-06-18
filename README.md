# 📋 UniTask: Sistema de Gestión de Tareas Académicas

Bienvenido a la versión profesional de **UniTask**, desarrollada por el equipo **DevFusion** para la materia *Metodología de Sistemas I* (UTN FRRE - Sede Formosa, 2026). 

---

## 🌳 Estándares de Git y Flujo de Trabajo
Para garantizar la integridad y el seguimiento del código, todo el equipo debe seguir estrictamente estas reglas de nomenclatura vinculadas a Jira:

### 🌿 Convención de Nombres de Ramas
Toda rama de trabajo debe nacer de `backend-main` o `frontend-main` y nombrarse con el formato: `tipo/UNITASK-[XX]-[breve-descripcion]`.

| Prefijo de Rama | Propósito | Ejemplo de Rama |
| :--- | :--- | :--- |
| `feat/` | Nuevas funcionalidades o maquetados | `feat/UNITASK-75-formulario-tareas` |
| `fix/` | Corrección de errores (Bugs) | `fix/UNITASK-73-error-auth` |
| `style/` | Cambios de CSS o UI sin afectar lógica | `style/UNITASK-105-componentes-botones` |
| `docs/` | Cambios en documentación | `docs/UNITASK-12-update-readme` |
| `refactor/` | Mejora de código sin cambios funcionales | `refactor/UNITASK-40-limpieza-controlador` |

### 💬 Convención de Mensajes de Commit
Es **obligatorio** incluir el código del ticket de Jira en el mensaje del commit usando corchetes. Esto permite que Jira trakee automáticamente el avance del código.
**Formato:** `tipo[UNITASK-XX]: Descripción clara y en imperativo`

**Tipos de Prefijos Aceptados:**
| Prefijo | Propósito | Ejemplo de Commit |
| :--- | :--- | :--- |
| `feat` | Nueva funcionalidad (Backend o Frontend) | `feat[UNITASK-75]: Implementar formulario de registro` |
| `fix` | Corrección de un error (Bug) | `fix[UNITASK-73]: Corregir validación nullable` |
| `docs` | Solo cambios en la documentación | `docs[UNITASK-12]: Actualizar estado de los Sprints` |
| `style` | Cambios visuales de UI, CSS, formato, espacios | `style[UNITASK-105]: Crear componentes reutilizables` |
| `refactor` | Cambio de código que no añade features ni arregla bugs | `refactor[UNITASK-40]: Extraer lógica a un trait` |
| `test` | Agregar o corregir pruebas (PHPUnit, Cypress) | `test[UNITASK-80]: Añadir pruebas de integración para login` |
| `perf` | Cambios para mejorar el rendimiento | `perf[UNITASK-50]: Agregar índices a la tabla users` |
| `chore` | Tareas de mantenimiento, actualización de dependencias | `chore[UNITASK-02]: Actualizar versión de Laravel a 13.5` |
| `build` | Cambios que afectan el sistema de construcción (Vite, NPM) | `build[UNITASK-04]: Configurar compilación de Tailwind` |
| `ci` | Cambios en archivos o scripts de CI/CD (GitHub Actions) | `ci[UNITASK-05]: Configurar workflow de pruebas automáticas` |
| `revert` | Revertir un commit anterior | `revert[UNITASK-99]: Revertir actualización de SweetAlert2` |

### 🛡️ Reglas de Oro
1. **No Commits Directos:** Está terminantemente prohibido hacer commits directos a `main`, `develop`, `backend-main` o `frontend-main`.
2. **Pull Requests (PR):** Todo cambio debe enviarse vía PR y ser revisado y aprobado por el líder del sub-equipo (Antonio) antes de ser integrado.
3. **Sincronización:** Antes de empezar a trabajar en una nueva tarea, asegúrate de hacer `git checkout` a la rama correcta y ejecutar `git pull`.

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

## 🚀 Estado del Proyecto y Contexto Actual (Entrega Final)

**Fecha Límite Definitiva:** 26 de Junio de 2026.

El proyecto se encuentra en la recta final de desarrollo para presentar el sistema completo a la cátedra. Las directivas actuales son:
1. **Conexión Directa Front/Back:** A partir del Sprint 5, se elimina el uso de datos simulados (mocking). El Frontend debe consumir los endpoints reales expuestos por el Backend para todas las funcionalidades.
2. **Nuevas Funcionalidades:** Se incorpora un Tablero Kanban (Drag & Drop) para la gestión visual de tareas, además de mantener FullCalendar y las Subtareas Jerárquicas.
3. **Módulo de Recuperación de Clave:** Activo y testeado usando Mailtrap como entorno seguro.

### 🏆 Sprints Completados
- **Sprint 1:** Infraestructura base, diagramas de BD y reglas de GitHub. (100% Finalizado)
- **Sprint 2:** Configuración de migraciones, Modelos base y Controladores de perfil de usuario. (100% Finalizado)
- **Sprint 3:** Autenticación, protección de rutas, CRUD de asignaturas y vistas adaptables. (100% Finalizado)

### 🚧 Sprints Actuales
- **Sprint 4 (Actual):** Autenticación final, Landing Page, e infraestructura Backend avanzada (Migraciones de tareas, recursividad para subtareas y endpoints AJAX).
- **Sprint 5 (Final):** Integración completa Front/Back, Tablero Kanban, FullCalendar, ciclo de pruebas UAT, Seeders de demostración y Manual de Usuario final.

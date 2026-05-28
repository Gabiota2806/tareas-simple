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

---

## 🚀 Sprint 1
El estado de la infraestructura técnica es el siguiente:

- [x] **Inicialización del Repositorio:** Repositorio central creado y colaboradores invitados.
- [x] **Seguridad y Calidad:** Reglas de protección de rama `main` activas (Pull Requests obligatorios).
- [x] **Scaffolding de Backend:** Instalación base de Laravel realizada con éxito en la rama `develop`.
- [x] **Arquitectura de Ramas:** Estructura de `develop`, `backend-main` y `frontend-main` configurada con Rulesets delegados.
- [ ] **Definir el Layout Base (Mobile First):** paleta de colores corporativa y tipografía oficial.
- [ ] **Diseño de "Task Cards":** con indicadores visuales de prioridad (colores laterales).
- [ ] **Mockup interactivo del formulario:** "Agregar Tarea" y selector de prioridades.
- [x] **Diseñar el Diagrama de Entidad-Relación (DER):** con tipos de datos (BigInt, String) y claves foráneas.
- [x] **Definir el Diccionario:** de Datos con nomenclatura estándar en inglés (snake_case).
- [x] **Redactar el Contrato de API (CONTRATO_API.md):** con las estructuras JSON de entrada y salida.

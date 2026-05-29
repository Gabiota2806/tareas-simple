#  Contrato de API y Definición de Rutas

Este documento establece cómo se comunican las vistas de Frontend (Blade) con el servidor de Laravel 13.5.

##  Reglas de Comunicación
* **Seguridad:** Todas las peticiones POST, PATCH y DELETE deben incluir la directiva `@csrf`.
* **Autenticación:** Las rutas de tareas están protegidas por el middleware `auth`.
* **Formato de Variables:** Los inputs del formulario deben coincidir con las claves del JSON.
* **Estados de tarea:** El campo `status` define el estado de una tarea y debe usar valores permitidos.

---

##  Definición de Rutas (Endpoints)

### Módulo de Autenticación
* `GET /login` — Muestra la vista de acceso.
* `POST /login` — Procesa las credenciales de entrada.
* `POST /logout` — Finaliza la sesión del usuario.

### Módulo de Tareas
| Método | Ruta | Descripción | Inputs principales |
| :--- | :--- | :--- | :--- |
| `GET` | `/tasks` | Listar tareas del usuario autenticado | Ninguno |
| `POST` | `/tasks` | Crear una nueva tarea | `title`, `priority`, `status`, `parent_id` (opcional), `category_ids` (opcional) |
| `PATCH` | `/tasks/{id}` | Actualizar el estado de una tarea | `status` |
| `DELETE` | `/tasks/{id}` | Eliminar una tarea | `id` en URL |

### Módulo de Categorías
| Método | Ruta | Descripción | Inputs principales |
| :--- | :--- | :--- | :--- |
| `GET` | `/categories` | Listar categorías del usuario | Ninguno |
| `POST` | `/categories` | Crear una nueva categoría | `name`, `color` |
| `DELETE` | `/categories/{id}` | Eliminar una categoría | `id` en URL |

---

##  Formatos de Envío (Payloads)

### Crear Tarea Principal
```json
{
  "title": "Maquetar la barra lateral",
  "description": "Incluir el botón de cerrar sesión",
  "priority": "high",
  "status": "pending",
  "category_ids": [5],
  "parent_id": null
}
```

### Crear Subtarea
```json
{
  "title": "Agregar icono de usuario",
  "priority": "medium",
  "status": "in_progress",
  "category_ids": [5, 7],
  "parent_id": 105
}
```

### Crear Categoría
```json
{
  "name": "Diseño",
  "color": "#FF5722"
}
```

### Respuesta de Error (Validación)
```json
{
  "message": "The title field is required.",
  "errors": {
    "title": ["El campo título es obligatorio."]
  }
}
```

---

##  Notas Adicionales
* Para la creación de tareas, `parent_id` es opcional y se usa solo para subtareas.
* Las tareas pueden asociarse a una o varias categorías mediante `category_ids`.
* Las categorías pertenecen a un usuario (`user_id`) y solo deben listar/usar las categorías del usuario autenticado.
* El campo `status` define el estado de la tarea y puede ser usado en dropdowns o etiquetas.
* Valores de `status` recomendados:
  * `pending` — En espera o pendiente.
  * `in_progress` — En proceso.
  * `completed` — Terminada.
* Al actualizar con `PATCH /tasks/{id}`, se actualiza el campo `status`.

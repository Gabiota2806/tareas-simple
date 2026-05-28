# 📖 Diccionario de Datos - UniTask

Este documento define la estructura técnica de la base de datos MySQL para el sistema UniTask.

## 🛠️ Estándares del Proyecto

## Motor: MySQL
## Nomenclatura: `snake_case`
## Idioma: Inglés (Laravel Standard)

---

### 1. Tabla: `users`

| Campo        | Tipo                   | Descripción                         |
| :----------- | :--------------------- | :---------------------------------- |
| `id`         | PK, INT, AI            | Identificador numérico único.       |
| `name`       | VARCHAR(100), NOT NULL | Nombre del usuario.                 |
| `email`      | VARCHAR(150), UNIQUE   | Email para login y notificaciones.  |
| `password`   | VARCHAR(255)           | Hash de seguridad de la contraseña. |
| `created_at` | TIMESTAMP              | Fecha de registro.                  |
| `updated_at` | TIMESTAMP              | Última actualización de perfil.     |

### 2. Tabla: `password_resets`

| Campo        | Tipo         | Descripción                    |
| :----------- | :----------- | :----------------------------- |
| `id`         | PK, INT      | ID de la solicitud.            |
| `user_id`    | INT          | Relación con el usuario.       |
| `token`      | VARCHAR(255) | Código de validación temporal. |
| `expires_at` | DATETIME     | Tiempo de validez del token.   |

### 3. Tabla: `tasks`

| Campo        | Tipo         | Descripción                            |
| :----------- | :----------- | :------------------------------------- |
| `id`         | PK, INT      | Identificador de la tarea.             |
| `title`      | VARCHAR(200) | Título de la actividad.                |
| `parent_id`  | INT, NULL    | ID de la tarea padre (para subtareas). |
| `is_deleted` | TINYINT(1)   | Borrado lógico (Soft Delete).          |

### 4. Tabla: `categories`

| Campo   | Tipo         | Descripción                    |
| :------ | :----------- | :----------------------------- |
| `id`    | PK, INT      | ID único de categoría.         |
| `name`  | VARCHAR(100) | Nombre (Ej: Estudio, Trabajo). |
| `color` | VARCHAR(7)   | Código hexadecimal del color.  |

### 5. Tabla: `task_category`

| Campo         | Tipo | Descripción                |
| :------------ | :--- | :------------------------- |
| `task_id`     | INT  | FK hacia tabla tareas.     |
| `category_id` | INT  | FK hacia tabla categorías. |
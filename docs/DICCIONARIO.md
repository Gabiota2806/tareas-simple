# 📖 Diccionario de Datos - UniTask (Actualizado para Prototipo MVP)

Este documento define la estructura técnica de la base de datos MySQL para el sistema UniTask.

## 🛠️ Estándares del Proyecto

* **Motor:** MySQL 8.0
* **Nomenclatura:** `snake_case`
* **Idioma:** Inglés (Laravel Standard)
* **Tipos de borrado:** Borrado físico en tablas base, borrado lógico (`is_deleted` / `is_active`) en entidades académicas y tareas.

---

### 1. Tabla: `users`
Administrada parcialmente por Laravel Breeze.
| Campo | Tipo | Restricciones | Descripción |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT, PK, AI | NOT NULL | Identificador numérico único. |
| `name` | VARCHAR(100) | NOT NULL | Nombre del usuario. |
| `email` | VARCHAR(150) | UNIQUE, NOT NULL | Email para login y notificaciones. |
| `email_verified_at` | TIMESTAMP | NULL | Fecha de verificación de correo. |
| `password` | VARCHAR(255) | NOT NULL | Hash de seguridad de la contraseña. |
| `remember_token` | VARCHAR(100) | NULL | Token de sesión persistente. |
| `created_at` | TIMESTAMP | NULL | Fecha de registro. |
| `updated_at` | TIMESTAMP | NULL | Última actualización de perfil. |

### 2. Tabla: `password_reset_tokens`
Administrada por Laravel Breeze para recuperación de clave.
| Campo | Tipo | Restricciones | Descripción |
| :--- | :--- | :--- | :--- |
| `email` | VARCHAR(150), PK | NOT NULL | Correo al que se envió el token. |
| `token` | VARCHAR(255) | NOT NULL | Código de validación temporal. |
| `created_at` | TIMESTAMP | NULL | Tiempo de generación del token. |

### 3. Tabla: `universities`
Instituciones a las que asiste el usuario.
| Campo | Tipo | Restricciones | Descripción |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT, PK, AI | NOT NULL | Identificador único. |
| `user_id` | BIGINT, FK | CASCADE ON DELETE | FK hacia `users.id` (Propietario). |
| `name` | VARCHAR(150) | NOT NULL | Nombre de la institución (Ej: UTN FRRE). |
| `acronym` | VARCHAR(20) | NULL | Sigla (Ej: UTN). |
| `created_at` | TIMESTAMP | NULL | Fecha de creación. |
| `updated_at` | TIMESTAMP | NULL | Última modificación. |

### 4. Tabla: `careers`
Carreras universitarias asociadas a una institución.
| Campo | Tipo | Restricciones | Descripción |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT, PK, AI | NOT NULL | Identificador único. |
| `university_id` | BIGINT, FK | CASCADE ON DELETE | FK hacia `universities.id`. |
| `name` | VARCHAR(150) | NOT NULL | Nombre de la carrera. |
| `duration_years` | INT | NULL | Duración teórica en años. |
| `created_at` | TIMESTAMP | NULL | Fecha de creación. |
| `updated_at` | TIMESTAMP | NULL | Última modificación. |

### 5. Tabla: `subjects`
Materias o asignaturas que el usuario cursa.
| Campo | Tipo | Restricciones | Descripción |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT, PK, AI | NOT NULL | Identificador único. |
| `career_id` | BIGINT, FK | CASCADE ON DELETE | FK hacia `careers.id`. |
| `user_id` | BIGINT, FK | CASCADE ON DELETE | FK hacia `users.id` (Propietario). |
| `name` | VARCHAR(150) | NOT NULL | Nombre de la materia. |
| `teacher` | VARCHAR(150) | NULL | Docente a cargo. |
| `classroom` | VARCHAR(50) | NULL | Aula física o virtual. |
| `color_identificador`| VARCHAR(7) | NOT NULL | Color HEX para UI (Ej: #FF5722). |
| `is_active` | BOOLEAN | DEFAULT 1 | Vigencia (0 = Histórica, 1 = Cursando). |
| `created_at` | TIMESTAMP | NULL | Fecha de creación. |
| `updated_at` | TIMESTAMP | NULL | Última modificación. |

### 6. Tabla: `tasks`
Actividades académicas estructuradas.
| Campo | Tipo | Restricciones | Descripción |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT, PK, AI | NOT NULL | Identificador de la tarea. |
| `subject_id` | BIGINT, FK | CASCADE ON DELETE | FK hacia `subjects.id` (Materia). |
| `user_id` | BIGINT, FK | CASCADE ON DELETE | FK hacia `users.id` (Propietario). |
| `parent_id` | BIGINT, FK | NULL, CASCADE | Tarea padre (subtareas). |
| `title` | VARCHAR(200) | NOT NULL | Título de la actividad. |
| `description` | TEXT | NULL | Descripción larga opcional. |
| `task_type` | ENUM | NOT NULL | Valores: 'parcial', 'final', 'tp', 'normal'. |
| `priority` | ENUM | NOT NULL | Valores: 'high', 'medium', 'low'. |
| `is_completed` | BOOLEAN | DEFAULT 0 | Estado de completitud (checkbox). |
| `due_date` | DATE | NULL | Fecha de vencimiento. |
| `is_deleted` | BOOLEAN | DEFAULT 0 | Borrado lógico (Soft Delete). |
| `created_at` | TIMESTAMP | NULL | Fecha de creación. |
| `updated_at` | TIMESTAMP | NULL | Última modificación. |

---
*Nota: Las tablas `categories` y `task_category` del diseño original fueron depreciadas y reemplazadas por el modelo estructural `subjects` para alinearse al MVP Académico.*
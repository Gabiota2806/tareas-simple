# 📄 Contrato de API y Diseño de Payloads (Versión Prototipo MVP)

Este documento centraliza los endpoints y estructuras JSON que el Frontend y Backend utilizarán para el prototipo del 26 de Junio. 

> 🚨 **REGLA DE MOCKING PARA FRONTEND:** Matías y Fabián, usen exactamente estos bloques JSON como datos estáticos locales en sus componentes Vue/Blade (con Fetch API o importándolos). No esperen a que los endpoints estén listos para armar la interfaz visual.

---

## 🔒 1. Reglas Generales
* **Seguridad:** Todas las peticiones mutables (POST, PATCH, DELETE) deben incluir cabecera `@csrf`.
* **Formato:** `Content-Type: application/json` y `Accept: application/json`.
* **Autenticación:** Excepto `/login`, `/register` y recuperación de claves, todo requiere sesión iniciada (middleware `auth`).
* **Paginación:** Desactivada para el prototipo para acelerar el desarrollo. Se devuelven arrays planos completos (`[]`).

---

## 🔐 2. Módulo de Autenticación y Perfil (Laravel Breeze)

Este módulo es manejado internamente por Laravel Breeze, pero el Frontend debe consumir estas rutas web estandarizadas.

| Método | Endpoint | Descripción | Body JSON / Form |
| :--- | :--- | :--- | :--- |
| `POST` | `/register` | Registrar nuevo usuario | `name`, `email`, `password`, `password_confirmation` |
| `POST` | `/login` | Iniciar sesión | `email`, `password` |
| `POST` | `/logout` | Cerrar sesión | Ninguno |
| `PATCH` | `/profile` | Actualizar datos del usuario | `name`, `email` |

---

## 🏛️ 3. Módulo Institucional (Universidades y Carreras)

| Método | Endpoint | Descripción | Body JSON |
| :--- | :--- | :--- | :--- |
| `GET` | `/universities` | Listar universidades del usuario | Ninguno |
| `POST` | `/universities` | Crear universidad | `name`, `acronym` |
| `GET` | `/careers` | Listar carreras del usuario | Ninguno |
| `POST` | `/careers` | Crear carrera | `name`, `duration_years`, `university_id` |

### 3.1 Payload: Crear Universidad (Request POST /universities)
```json
{
  "name": "Universidad Tecnológica Nacional",
  "acronym": "UTN"
}
```

### 3.2 Payload: Crear Carrera (Request POST /careers)
```json
{
  "name": "Tecnicatura Universitaria en Programación",
  "duration_years": 2,
  "university_id": 1
}
```

---

## 📚 4. Módulo Académico (Materias / Subjects)

| Método | Endpoint | Descripción | Body JSON |
| :--- | :--- | :--- | :--- |
| `GET` | `/subjects` | Obtener todas las materias del usuario | Ninguno |
| `POST` | `/subjects` | Crear una nueva materia | `name`, `teacher`, `classroom`, `color_code`, `career_id` |
| `PATCH` | `/subjects/{id}` | Alternar estado (activo/inactivo) | `is_active` |
| `DELETE` | `/subjects/{id}` | Eliminar materia lógicamente | Ninguno |

### 4.1 Payload: Crear Materia (Request POST /subjects)
```json
{
  "name": "Sistemas de Bases de Datos",
  "teacher": "Ing. Carlos Pérez",
  "classroom": "Laboratorio 3",
  "color_code": "#4CAF50",
  "career_id": 1
}
```

### 4.2 Payload: Listar Materias (Response GET /subjects)
```json
[
  {
    "id": 1,
    "name": "Sistemas de Bases de Datos",
    "teacher": "Ing. Carlos Pérez",
    "classroom": "Laboratorio 3",
    "color_code": "#4CAF50",
    "is_active": true
  },
  {
    "id": 2,
    "name": "Ingeniería de Software",
    "teacher": "Lic. Ana Gómez",
    "classroom": "Aula 102",
    "color_code": "#FF9800",
    "is_active": false
  }
]
```

---

## 📝 5. Módulo de Tareas (Tasks)

| Método | Endpoint | Descripción | Body JSON |
| :--- | :--- | :--- | :--- |
| `GET` | `/tasks` | Listar todas las tareas (de materias activas) | Ninguno |
| `POST` | `/tasks` | Crear nueva tarea | `title`, `description`, `task_type`, `priority`, `subject_id`, `due_date`, `task_time`, `estimated_time`, `reminder` |
| `PATCH` | `/tasks/{id}` | Marcar como completada / Editar | `is_completed` u otros campos |
| `DELETE` | `/tasks/{id}` | Eliminar tarea | Ninguno |

### 5.1 Payload: Crear Tarea Principal (Request POST /tasks)
```json
{
  "title": "Entrega de Prototipo MVP",
  "description": "Presentar el sistema funcional al profesor",
  "task_type": "tp", 
  "priority": "high", 
  "subject_id": 1,
  "due_date": "2026-06-26",
  "task_time": "18:30",
  "estimated_time": "2h 30m",
  "reminder": "1 día antes",
  "parent_id": null
}
```
*(Valores permitidos: `task_type` admite 'parcial', 'final', 'tp', 'normal'. `priority` admite 'high', 'medium', 'low').*

### 5.2 Payload: Listar Tareas (Response GET /tasks)
```json
[
  {
    "id": 105,
    "title": "Entrega de Prototipo MVP",
    "description": "Presentar el sistema funcional al profesor",
    "task_type": "tp",
    "priority": "high",
    "is_completed": false,
    "due_date": "2026-06-26",
    "task_time": "18:30",
    "estimated_time": "2h 30m",
    "reminder": "1 día antes",
    "subject_id": 1,
    "parent_id": null,
    "subject": {
       "name": "Sistemas de Bases de Datos",
       "color_code": "#4CAF50"
    }
  }
]
```
*(Nota: El Backend adjunta el objeto anidado `subject` resumido para que el Frontend pueda pintar el borde de la tarjeta con el color correspondiente sin hacer dobles consultas).*

### 5.3 Payload: Completar Tarea Asíncrona (Request PATCH /tasks/{id})
```json
{
  "is_completed": true
}
```

---

## 📅 6. Módulo Calendario (FullCalendar)

| Método | Endpoint | Descripción | Body JSON |
| :--- | :--- | :--- | :--- |
| `GET` | `/calendar/events` | Listado adaptado para el formato nativo de FullCalendar | Ninguno |

### 6.1 Payload: Eventos de Calendario (Response GET /calendar/events)
```json
[
  {
    "id": "task_105",
    "title": "Entrega de Prototipo MVP",
    "start": "2026-06-26",
    "color": "#4CAF50",
    "extendedProps": {
      "type": "task",
      "priority": "high"
    }
  }
]
```

---

## ❌ 7. Respuestas de Error Estándar (HTTP 422 Unprocessable Entity)

Toda validación fallida del Backend devolverá este formato estándar de Laravel:

```json
{
  "message": "The title field is required.",
  "errors": {
    "title": ["El campo título es obligatorio."],
    "subject_id": ["La materia seleccionada no es válida o está inactiva."]
  }
}
```

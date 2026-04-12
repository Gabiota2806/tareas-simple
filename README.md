### Dificultad:  ⚔️ Normal

- Como usuario quiero registrar mis tareas pendientes para organizarme mejor
- Como usuario quiero eliminar las tareas que ya finalizaron 
- Como usuario quiero agregar prioridades a cada tarea

-----------------------------------------------------------------------------------------------------

# 📋 Sistema de Gestión de Tareas (To-Do List)

Aplicación web cliente-servidor desarrollada como práctica para la materia Metodología de Sistemas 1. El objetivo del sistema es permitir a los usuarios organizar sus actividades pendientes mediante una interfaz intuitiva que se comunica de forma asíncrona con un servidor.

## 🛠️ Arquitectura y Tecnologías
El sistema está construido bajo una arquitectura básica sin frameworks adicionales:
- **Frontend:** Interfaz en HTML5 y estilos básicos. La lógica y peticiones asíncronas (Fetch) se manejan con Vanilla JavaScript.
- **Backend:** Controlador único en PHP (`tareas.php`) que procesa las peticiones del cliente.
- **Almacenamiento:** Persistencia de datos en un archivo local (`tareas.json`) simulando una base de datos ligera.

## 🎯 Requerimientos del Sistema
Nivel de Dificultad: ⚔️ Normal

- [x] Como usuario quiero registrar mis tareas pendientes para organizarme mejor.
- [x] Como usuario quiero eliminar las tareas que ya finalizaron.
- [ ] **(Pendiente)** Como usuario quiero agregar prioridades a cada tarea.

## 🚀 Uso del Proyecto
Para correr este proyecto en modo de desarrollo:
1. Clonar este repositorio.
2. Levantar un servidor local con soporte PHP (ej. XAMPP, Laragon, etc.) y colocar el proyecto en la carpeta pública.
3. Acceder al proyecto a través de `localhost`.

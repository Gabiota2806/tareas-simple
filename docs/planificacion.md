📅 Plan de Dirección del Proyecto: Sistema de Gestión de Tareas
1. Alcance del Proyecto
El objetivo de esta iteración es migrar la aplicación "To-Do List" de una arquitectura de archivos planos (PHP puro + JSON) a un entorno profesional utilizando el framework Laravel y bases de datos MySQL, mejorando la interfaz de usuario (UX/UI) e incorporando la funcionalidad de "Prioridad" en las tareas.

2. Gestión de Recursos y Roles
El equipo consta de 6 desarrolladores, divididos equitativamente para asegurar el flujo de trabajo:

*Equipo Frontend (3 integrantes): Encargados de la Experiencia de Usuario (UX), Interfaz (UI) en Blade y estilos modernos.
*Equipo Backend (3 integrantes): Encargados de la persistencia de datos (MySQL), Modelo y Controladores en Laravel.
*Líder Técnico / Project Manager: [Tu Nombre] - Encargado de la arquitectura base (Scaffolding), resolución de bloqueos y seguimiento del cronograma.

3. Identificación y Mitigación de Riesgos
*Riesgo Principal: Curva de aprendizaje técnica. El equipo aún no tiene conocimientos sólidos sobre la estructura de Laravel.
*Estrategia de Mitigación: Se adopta un enfoque de desarrollo híbrido. Se realizará un Timeboxing inicial (Fase 1) dedicado exclusivamente al diseño visual y modelado de datos teóricos, permitiendo que el equipo estudie Laravel en paralelo antes de comenzar la codificación dura (Fase 2).

4. Cronograma y Estructura de Tareas (Enfoque Híbrido)
Fase 1: Prototipado y Contrato (Tiempo estimado: 3 a 5 días)
Objetivo: Definir qué vamos a construir antes de tocar código complejo en Laravel.

    *T1. Frontend: Diseñar los Mockups (bocetos visuales) de la nueva interfaz usando Figma, Canva o papel. Definir paleta de colores y cómo se verá el selector de "Prioridad" (alta, media, baja).
    *T2. Backend: Diseñar el Diagrama de Base de Datos. Anotar en un documento (API Contract) cómo se llamará la tabla (tasks), qué columnas tendrá (id, title, completed, priority) y qué tipo de dato será cada una.
    *T3. Reunión Conjunta (Hito): El equipo completo se reúne 30 minutos. El Frontend presenta el diseño y el Backend presenta el modelo de datos. Ambos aprueban el "Contrato" de variables.

Fase 2: Desarrollo en Paralelo (Tiempo estimado: 1 a 2 semanas)
Objetivo: Codificación en Laravel con los conocimientos ya adquiridos en clases.

    *T4. Backend: * Crear la migración en Laravel para la tabla tasks basándose en el contrato aprobado.
        *Desarrollar el Modelo y el Controlador (TaskController) con las funciones de guardar, leer y eliminar.
    *T5. Frontend: * Traducir el Mockup (Figma) a código HTML/CSS dentro de la vista de Laravel (welcome.blade.php o index.blade.php).
        *Maquetar el formulario para agregar tareas con su respectiva prioridad.
    *T6. Integración (Joint Task): Conectar el formulario del Frontend con las rutas del Backend de Laravel.

5. Plan de Comunicación
*Daily Stand-ups (Sincronización): Se realizarán actualizaciones breves vía chat grupal detallando: 1. Qué hice, 2. Qué haré, 3. Bloqueos.
*Control de versiones: Todo el código se integrará en el repositorio central de GitHub. Ningún equipo avanzará a la Fase 2 sin haber hecho pull de los últimos cambios de arquitectura.

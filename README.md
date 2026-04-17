# 📋 Sistema de Gestión de Tareas (To-Do List) - v2.0

Aplicación web cliente-servidor desarrollada como práctica para la materia **Metodología de Sistemas I**. El objetivo del sistema es permitir a los usuarios organizar sus actividades pendientes mediante una interfaz moderna, responsiva e intuitiva.

Esta versión representa una migración arquitectónica hacia un entorno de trabajo profesional utilizando el stack Laravel + MySQL.

## 🛠️ Arquitectura y Tecnologías

El proyecto está dividido en sub-equipos ágiles y utiliza el siguiente stack tecnológico:

* **Backend & API:** Laravel (PHP)
* **Base de Datos:** MySQL
* **Frontend:** Motor de plantillas Blade (Laravel), HTML5, CSS3 / Framework CSS (Gestionado vía Vite/NPM).
* **Control de Versiones:** Git & GitHub

## 🎯 Requerimientos del Sistema (Product Backlog)

** Dificultad:  ⚔️ Normal (Refactorización a Framework)

* [ ] Como usuario quiero registrar mis tareas pendientes para organizarme mejor.
* [ ] Como usuario quiero eliminar las tareas que ya finalizaron.
* [ ] Como usuario quiero agregar niveles de prioridad a cada tarea.
* [ ] Como usuario quiero una interfaz moderna y responsiva (UX/UI optimizada).

---

## 🚀 Guía de Instalación para el Equipo (Scaffolding)

Para que todos los miembros del equipo (Frontend y Backend) puedan correr este proyecto en sus computadoras locales, deben seguir estos pasos estrictamente:

### Requisitos previos:
* Tener instalado **PHP** y **Composer**.
* Tener instalado **Node.js** y **NPM**.
* Tener un servidor de base de datos **MySQL** (XAMPP, Laragon, MySQL Workbench, etc.).

### Pasos de instalación:

1. **Clonar y sincronizar el repositorio:**
   Si ya tienes el repositorio clonado, asegúrate de estar en la rama principal y trae los últimos cambios:
   ```bash
   git pull origin main
# 📋 UniTask: Sistema Integral de Gestión de Tareas Académicas

## 🎯 ¿En qué consiste el proyecto?
UniTask es una plataforma web desarrollada para centralizar, organizar y priorizar el flujo académico de estudiantes universitarios. Surge como una evolución y profesionalización de un sistema básico (anteriormente en PHP puro y JSON) hacia una arquitectura robusta, segura y escalable utilizando el framework **Laravel** y el motor de base de datos **MySQL**. 

El sistema permite a los estudiantes:
- Registrarse e iniciar sesión de forma segura y privada.
- Administrar sus propias Universidades y Carreras.
- Gestionar un catálogo de Materias (asignaturas) con identificadores visuales.
- Crear tareas, exámenes y trabajos prácticos organizados por nivel de prioridad.
- Administrar entregas complejas mediante la creación de subtareas jerárquicas.
- Visualizar todos sus compromisos académicos de forma mensual y semanal mediante un calendario interactivo e intuitivo.

---

## 🚀 ¿Cómo levantar el proyecto localmente?

Para ejecutar UniTask en tu entorno local de desarrollo, asegúrate de contar con **PHP 8.3 o superior**, **Composer**, **Node.js / NPM** y **MySQL**. Luego, sigue estos pasos:

1. **Clonar el repositorio:**
   ```bash
   git clone <URL_DEL_REPOSITORIO>
   cd tareas-simple
   ```

2. **Instalar dependencias del Backend (PHP):**
   ```bash
   composer install
   ```

3. **Instalar dependencias del Frontend (Node):**
   ```bash
   npm install
   ```

4. **Configurar el entorno:**
   - Duplica el archivo de configuración de ejemplo y renómbralo a `.env`:
     ```bash
     cp .env.example .env
     ```
   - Abre el nuevo archivo `.env` y configura tus credenciales de conexión a la base de datos (asegúrate de crear previamente una base de datos vacía en MySQL, por ejemplo `unitask_db`):
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=unitask_db
     DB_USERNAME=root
     DB_PASSWORD=tu_contraseña
     ```

5. **Generar la clave de seguridad de la aplicación:**
   ```bash
   php artisan key:generate
   ```

6. **Ejecutar migraciones (Base de Datos):**
   Este comando creará toda la estructura de tablas necesaria en tu base de datos MySQL. (Opcionalmente, puedes añadir `--seed` si existen datos de prueba configurados).
   ```bash
   php artisan migrate
   ```

7. **Levantar los servidores de desarrollo:**
   Para que el sistema funcione correctamente con todas sus hojas de estilo, necesitarás ejecutar dos procesos en terminales separadas dentro de la carpeta del proyecto.
   
   - **Terminal 1 (Servidor Backend de Laravel):**
     ```bash
     php artisan serve
     ```
   - **Terminal 2 (Compilador de estilos Vite/Tailwind):**
     ```bash
     npm run dev
     ```

8. **Acceder a la plataforma:**
   Abre tu navegador web de preferencia e ingresa a `http://localhost:8000`.

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

## ⚙️ Puesta en Marcha (Entorno Docker & Laravel Sail)

Este proyecto está completamente dockerizado siguiendo la arquitectura y metodología de desarrollo ágil (Laravel Sail, MariaDB, Redis y phpMyAdmin), eliminando la necesidad de tener PHP, Composer o Node instalados en tu equipo host.

### Requisitos Previos
* **Git** instalado en el sistema.
* **Docker** y **Docker Compose** en ejecución.

---

### 🚀 Inicialización desde Cero (Nuevo Dispositivo / Onboarding)

Para clonar y dejar el proyecto 100% operativo en un solo paso:

```bash
./setup.sh
```

El script `./setup.sh` automatiza toda la secuencia:
1. Comprueba que el demonio de Docker esté activo.
2. Crea el archivo `.env` a partir de `.env.example`.
3. Instala dependencias de Composer mediante un contenedor temporal (sin necesidad de PHP en el host).
4. Levanta los contenedores en segundo plano (`./sail up -d`) con MariaDB, Redis y phpMyAdmin.
5. Genera la clave de cifrado de la aplicación (`APP_KEY`).
6. Ejecuta las migraciones y puebla la base de datos con los datos de prueba (`migrate:fresh --seed`).
7. Instala las dependencias de Node y compila los assets con Vite (`npm install && npm run build`).

**Acceso a los servicios:**
- 📍 **Aplicación web**: [http://localhost](http://localhost)
- 📍 **phpMyAdmin**: [http://localhost:8080](http://localhost:8080)
- 👤 **Credenciales de prueba**: `test@example.com` / `password`

---

### 💻 Desarrollo Diario

Una vez inicializado, para comenzar tu jornada de desarrollo ejecuta:

```bash
./dev.sh
```

El script `./dev.sh`:
- Verifica y asegura que los contenedores de Sail estén activos en segundo plano.
- Inicia el servidor de desarrollo en caliente de Vite (`./sail npm run dev`).

---

### 🛠️ Comandos Útiles con Sail

Puedes usar el wrapper ejecutable `./sail` directamente:
- `./sail artisan <comando>`: Ejecuta comandos de Artisan.
- `./sail npm <comando>`: Ejecuta comandos de Node/NPM.
- `./sail composer <comando>`: Ejecuta comandos de Composer.
- `./sail stop`: Detiene todos los contenedores.

---

## 💻 Alternativa: Instalación Manual Tradicional (Sin Docker)

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

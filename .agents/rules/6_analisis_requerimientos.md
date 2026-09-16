# 💡 Regla de Cumplimiento: Análisis y Maduración de Requerimientos Técnicos y de Producto (Gabriel & Antigravity)

Esta regla rige de forma permanente cada vez que Gabriel solicite analizar, madurar, aterrizar o estructurar una nueva funcionalidad o requerimiento para `UniTask`:

1. **FRASES O PALABRAS CLAVE DE ACTIVACIÓN**:
   - `"Modo Análisis de Requerimientos"`, `"Vamos a analizar una funcionalidad"`, `"Ayúdame a redactar un requerimiento"`, `"Analicemos esta idea"`.
   - Ante cualquiera de estas frases, Antigravity DEBE activar de inmediato el skill `analisis-requerimientos` y asumir el rol de **Analista Técnico de Producto (Technical Product Owner & Business Analyst)**.

2. **ROL Y OBJETIVO EXCLUSIVO**:
   - El objetivo exclusivo de esta metodología es tomar ideas en bruto, notas informales o requerimientos de negocio, investigar la realidad técnica del proyecto, detectar casos borde y producir una especificación técnica madura presentada en un bloque Markdown listo para copiar y pegar en el chat de Planificación de Jira.

3. **PROHIBICIONES ESTRICTAS (REGLA INVIOLABLE)**:
   - 🚫 **PROHIBIDO MODIFICAR CÓDIGO O BASE DE DATOS**: Antigravity **NO debe escribir código, editar archivos de la aplicación, alterar vistas ni ejecutar migraciones**.
   - 🚫 **PROHIBIDO CREAR RAMAS O COMMITS EN GIT**: Antigravity **NO debe crear ramas (`git checkout -b`), hacer commits, pushes ni abrir Pull Requests**. El árbol de Git debe permanecer inalterado.
   - 🚫 **PROHIBIDO LLAMAR A LA API DE JIRA**: Este rol **NO interactúa con Jira Cloud vía API**. La salida es estrictamente documental en el chat para que Gabriel la traslade a la sesión de Planificación.

4. **INSPECCIÓN TÉCNICA OBLIGATORIA CON LARAVEL BOOST MCP Y CÓDIGO REAL**:
   - Antes de formular propuestas o redactar especificaciones, Antigravity DEBE validar la viabilidad técnica contra la realidad actual del sistema:
     * **Herramientas de Laravel Boost MCP**:
       - Usar `database-schema` para consultar la estructura viva de tablas, tipos de columna, claves foráneas e índices.
       - Usar `database-query` para ejecutar consultas de solo lectura (`SELECT`) a fin de comprender cómo se estructuran los datos reales del negocio.
       - Usar `application-info` para auditar rutas registradas, versión de dependencias y configuración de entorno sin alterar estado.
     * **Inspección de Modelos y Dominio Académico**:
       - Inspeccionar modelos en `app/Models/` (User, University, Career, Subject, Task, etc.), relaciones y jerarquías.
     * **Rutas y UI**:
       - Revisar `routes/web.php` y vistas existentes para reutilizar lógica y evitar duplicidades.

5. **ENTREVISTA DE MADURACIÓN (CORTA Y DIRIGIDA)**:
   - Si existen puntos ciegos, ambigüedades o reglas no definidas, Antigravity DEBE formular preguntas breves, directas y estratégicas (máximo 2 a 4 preguntas clave), aportando sugerencias de UX.

6. **ENTREGA DEL PROMPT FINAL PARA JIRA**:
   - La culminación del proceso DEBE ser un único bloque de Markdown formateado y listo para copiar y pegar en el chat de Planificación de Jira con los siguientes 6 apartados obligatorios:
     1. **Título del Requerimiento / Feature**
     2. **Objetivo de Negocio**
     3. **Alcance Funcional Detallado (Paso a paso)**
     4. **Puntos de Integración Técnica Detectados (Modelos, controladores, tablas involucradas)**
     5. **Casos Borde y Validaciones Críticas (Roles, fechas límites, edge cases)**
     6. **Fuera de Alcance (Out of Scope)**

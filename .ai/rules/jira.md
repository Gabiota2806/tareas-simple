---
globs:
  - "**"
---

# Tablero de Jira

El backlog vive en Jira (proyecto `UT`) y se puede leer en vivo con
`scripts/jira.sh`. Usalo cuando haga falta saber en qué sprint estamos, qué
tickets están abiertos o en curso, quién tiene asignada una task, o el estado
real de una HU — y cuando el usuario nombre un ticket (`UT-123`, `HU-81`,
`TASK-154`) y necesites su contenido.

## Qué fuente manda

- **Jira manda** para lo que cambia: sprint actual, estado de un ticket, a quién
  está asignado, qué entró o salió del sprint.
- **`docs/jira_backlog_and_sprints.md` manda** para el detalle escrito de cada HU
  y TASK (si existe): objetivo, criterios de aceptación, consideraciones técnicas.

Si preguntan *"¿podemos arrancar la HU-81?"*: Jira para el estado, la tarjeta para
entender qué pide. No hace falta sincronizarlos.

## Cómo consultar

```bash
scripts/jira.sh whoami                 # verifica que las credenciales andan
scripts/jira.sh sprint                 # issues del sprint activo
scripts/jira.sh backlog                # sin terminar y fuera de sprint
scripts/jira.sh hu UT-261              # la HU y sus TASK, con descripciones
scripts/jira.sh tasks UT-261           # las TASK de una HU
scripts/jira.sh issue UT-123           # detalle de una issue
scripts/jira.sh search "<JQL>"         # cualquier otra cosa
scripts/jira.sh boards                 # tableros del proyecto
scripts/jira.sh raw /rest/api/3/...    # GET crudo, para lo que no cubran los verbos
```

Necesita `.env.jira` en la raíz (ignorado por git). Si falta, el script lo dice y
aborta.

Para arrancar una HU alcanza con `hu`: trae la historia y todas sus TASK con las
descripciones completas —ahí viven los criterios de aceptación— en una sola
llamada, sin encadenar consultas.

Devuelve **JSON crudo**, a propósito: así no depende de `jq` ni de `python` y
corre igual en Windows, WSL, macOS y dentro del contenedor. El formateo es tarea
del agente. Mostrale al usuario un resumen legible —tabla o lista por estado—,
nunca el JSON pelado.

### Recetas de JQL

```bash
# Lo que tiene alguien en curso
scripts/jira.sh search "project = UT AND assignee ~ 'Matias' AND statusCategory != Done"

# Todo lo que cuelga de una HU (las TASK son hijas de la HU)
scripts/jira.sh search "project = UT AND parent = UT-123"

# Qué se cerró en la última semana
scripts/jira.sh search "project = UT AND status CHANGED TO Done AFTER -7d"

# Buscar por texto cuando sabés el nombre pero no la clave
scripts/jira.sh search "project = UT AND summary ~ 'tareas'"
```

Si `assignee ~ 'Nombre'` falla, el Jira puede requerir `accountId`: sacalo con
`scripts/jira.sh raw /rest/api/3/user/search` y filtrá por
`assignee = '<accountId>'`.

### Paginación

La respuesta trae `nextPageToken` cuando hay más resultados. Pasalo como último
argumento: `scripts/jira.sh sprint "<token>"`. No traigas todas las páginas por
reflejo; si son muchas, decí cuántas hay y preguntá qué interesa.

## Solo lectura, y el motivo importa

El script rechaza todo lo que no sea `GET`. No es una limitación técnica: **el
token es de una sola cuenta y lo usa todo el equipo**. Cualquier transición,
comentario o reasignación quedaría en el historial de Jira a nombre del dueño del
token, no de quien la pidió.

Si hay que mover una tarjeta, decí qué mover y a dónde, y que lo haga la persona
desde Jira.

## No toques las credenciales

- No copies el contenido de `.env.jira` a otro archivo, a un mensaje ni a un
  comentario de código.
- No armes el `curl` a mano con el token en la línea de comandos: quedaría en el
  historial del shell y a la vista en `ps`. Para eso está el script.
- `.env.jira` está en `.gitignore`. Si aparece en un `git status`, frená y avisá.

## Vocabulario del tablero

- **EP-xx**: épica macro (de `EP-01` a `EP-08`).
- **HU-xx**: historia de usuario. Es *contexto*, no una orden de trabajo.
- **TASK-xx**: sub-tarea técnica de una HU, y **la unidad de trabajo real**: una
  rama y una PR por TASK, nunca por HU. Ver `flujo-de-trabajo.md`.

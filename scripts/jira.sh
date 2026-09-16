#!/usr/bin/env bash
#
# Lector del tablero de Jira del proyecto (Jira Cloud, API v3 + Agile 1.0).
#
# SOLO LECTURA. Todas las llamadas son GET y el script rechaza cualquier intento
# de cambiar el método o mandar cuerpo. El token es compartido por el equipo, así
# que una escritura accidental quedaría registrada a nombre de su dueño.
#
# Las credenciales salen de .env.jira (ignorado por git) y nunca pasan por la
# línea de comandos: viajan en un archivo de configuración temporal de curl con
# permisos 600 que se borra al salir. En argv quedarían en el historial del shell
# y a la vista de cualquiera que corra `ps`.
#
# Uso:
#   scripts/jira.sh whoami                 # verifica credenciales y conexión
#   scripts/jira.sh sprint                 # issues del sprint activo
#   scripts/jira.sh backlog                # issues fuera de sprint sin terminar
#   scripts/jira.sh hu UT-261              # la HU y sus TASK, con descripciones
#   scripts/jira.sh tasks UT-261           # las TASK (subtareas) de una HU
#   scripts/jira.sh issue UT-123           # detalle de una issue
#   scripts/jira.sh search "<JQL>" [token] # búsqueda libre (token = paginación)
#   scripts/jira.sh boards                 # tableros del proyecto
#   scripts/jira.sh raw /rest/api/3/...    # escape hatch, GET crudo
#
# Devuelve JSON tal cual lo manda Jira, a propósito: así el script no depende de
# jq ni de python, y funciona igual en Windows, WSL, macOS y dentro del
# contenedor. Quien lo formatea es el agente que lo lee.
#
# Variables opcionales: JIRA_MAX (default 100), JIRA_FIELDS.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ENV_FILE="$ROOT/.env.jira"

die() {
    printf 'jira.sh: %s\n' "$1" >&2
    exit 1
}

[ -f "$ENV_FILE" ] || die "falta $ENV_FILE.
Pedile las credenciales a quien administra el Jira y creá el archivo con
JIRA_URL, JIRA_EMAIL, JIRA_API_TOKEN y JIRA_PROJECT_KEY.
Está en .gitignore: no se commitea nunca."

# El archivo se parsea, no se hace `source`. Dos motivos: sourcear ejecuta lo que
# haya adentro, y en Windows el .env suele venir con CRLF — ese \r invisible se
# pegaría al final de la URL y todas las llamadas fallarían con un 404 raro.
read_env() {
    sed -n "s/^$1=//p" "$ENV_FILE" | tail -n 1 | tr -d '\r'
}

JIRA_URL="$(read_env JIRA_URL)"
JIRA_EMAIL="$(read_env JIRA_EMAIL)"
JIRA_API_TOKEN="$(read_env JIRA_API_TOKEN)"
JIRA_PROJECT_KEY="$(read_env JIRA_PROJECT_KEY)"
JIRA_URL="${JIRA_URL%/}"

[ -n "$JIRA_URL" ] || die "JIRA_URL vacío en .env.jira"
[ -n "$JIRA_EMAIL" ] || die "JIRA_EMAIL vacío en .env.jira"
[ -n "$JIRA_API_TOKEN" ] || die "JIRA_API_TOKEN vacío en .env.jira"
[ -n "$JIRA_PROJECT_KEY" ] || die "JIRA_PROJECT_KEY vacío en .env.jira"

command -v curl >/dev/null 2>&1 || die "hace falta curl en el PATH."

CURL_CFG="$(mktemp)"
chmod 600 "$CURL_CFG"
trap 'rm -f "$CURL_CFG"' EXIT
printf 'user = "%s:%s"\n' "$JIRA_EMAIL" "$JIRA_API_TOKEN" >"$CURL_CFG"

# Campos que se piden por defecto. Restringirlos no es cosmético: la respuesta
# completa de Jira trae decenas de campos por issue y llenaría el contexto del
# agente con ruido.
FIELDS="${JIRA_FIELDS:-summary,status,assignee,issuetype,parent}"
MAX="${JIRA_MAX:-100}"

# Hace la llamada. `--get` fuerza que todo lo que venga por --data-urlencode
# termine en el query string, nunca en un cuerpo.
api() {
    local path="$1"
    shift

    local arg
    for arg in "$@"; do
        case "$arg" in
            -X* | --request | --request=*)
                die "solo lectura: no se puede cambiar el método HTTP." ;;
            -d | --data | --data-raw | --data-binary | --data-ascii | -T | --upload-file)
                die "solo lectura: no se puede mandar cuerpo en la petición." ;;
        esac
    done

    local body http
    body="$(mktemp)"

    http="$(curl -sS -K "$CURL_CFG" \
        -o "$body" -w '%{http_code}' \
        -H 'Accept: application/json' \
        --get "$@" \
        "$JIRA_URL$path")" || {
        rm -f "$body"
        die "no se pudo conectar con $JIRA_URL (¿red? ¿VPN? ¿URL correcta?)"
    }

    if [ -z "$http" ] || [ "$http" -ge 400 ] 2>/dev/null; then
        printf 'jira.sh: Jira respondió %s en %s\n' "${http:-sin código}" "$path" >&2
        cat "$body" >&2
        printf '\n' >&2
        rm -f "$body"
        case "${http:-}" in
            401) die "credenciales rechazadas: revisá JIRA_EMAIL y JIRA_API_TOKEN (el token pudo haber sido revocado)." ;;
            403) die "la cuenta del token no tiene permiso sobre ese recurso." ;;
            404) die "no existe (o la cuenta no lo ve). Revisá la clave del proyecto o de la issue." ;;
            *) die "la consulta falló." ;;
        esac
    fi

    cat "$body"
    rm -f "$body"
}

# Búsqueda por JQL. Usa /search/jql: el viejo /rest/api/3/search fue dado de baja
# por Atlassian y hoy devuelve 410.
search() {
    local jql="$1"
    local page_token="${2:-}"
    set -- --data-urlencode "jql=$jql" \
        --data-urlencode "fields=$FIELDS" \
        --data-urlencode "maxResults=$MAX"

    # Sin `if`, un `[ -n ... ] && ...` que da falso devolvería 1 y `set -e`
    # cortaría el script en la última línea de la función.
    if [ -n "$page_token" ]; then
        set -- "$@" --data-urlencode "nextPageToken=$page_token"
    fi

    api /rest/api/3/search/jql "$@"
}

usage() {
    cat <<EOF
Lector del tablero de Jira ($JIRA_PROJECT_KEY). Solo lectura.

  scripts/jira.sh whoami                 verifica credenciales y conexión
  scripts/jira.sh sprint [token]         issues del sprint activo
  scripts/jira.sh backlog [token]        issues fuera de sprint sin terminar
  scripts/jira.sh hu UT-261              la HU y sus TASK, con descripciones
  scripts/jira.sh tasks UT-261           las TASK (subtareas) de una HU
  scripts/jira.sh issue UT-123           detalle de una issue
  scripts/jira.sh search "<JQL>" [token] búsqueda libre
  scripts/jira.sh boards                 tableros del proyecto
  scripts/jira.sh raw /rest/api/3/...    GET crudo contra cualquier endpoint

[token] es el nextPageToken que devuelve la respuesta anterior.

Variables opcionales:
  JIRA_MAX=50        cuántas issues por página (default 100)
  JIRA_FIELDS=...    campos a pedir (default: $FIELDS)
EOF
    exit "${1:-0}"
}

case "${1:-}" in
    whoami)
        api /rest/api/3/myself
        ;;

    sprint)
        # openSprints() evita tener que averiguar el id del tablero primero.
        search "project = $JIRA_PROJECT_KEY AND sprint IN openSprints() ORDER BY status ASC, key ASC" "${2:-}"
        ;;

    backlog)
        search "project = $JIRA_PROJECT_KEY AND sprint IS EMPTY AND statusCategory != Done ORDER BY key ASC" "${2:-}"
        ;;

    hu)
        # Todo lo necesario para arrancar una HU en una sola llamada: la historia
        # y sus TASK, con las descripciones completas (los criterios de
        # aceptación viven ahí). Pensado para no tener que encadenar consultas.
        [ $# -ge 2 ] || die "uso: jira.sh hu UT-261"
        FIELDS="${JIRA_FIELDS:-summary,status,assignee,priority,description}"
        search "project = $JIRA_PROJECT_KEY AND (key = $2 OR parent = $2) ORDER BY key ASC" "${3:-}"
        ;;

    tasks)
        # Las TASK están cargadas como subtareas de la HU. No aparecen en
        # `sprint` porque no llevan el campo sprint: lo heredan del padre.
        [ $# -ge 2 ] || die "uso: jira.sh tasks UT-261"
        search "project = $JIRA_PROJECT_KEY AND parent = $2 ORDER BY key ASC" "${3:-}"
        ;;

    issue)
        [ $# -ge 2 ] || die "uso: jira.sh issue UT-123"
        api "/rest/api/3/issue/$2" \
            --data-urlencode "fields=summary,status,assignee,issuetype,parent,priority,labels,description,subtasks,issuelinks"
        ;;

    search)
        [ $# -ge 2 ] || die "uso: jira.sh search \"project = $JIRA_PROJECT_KEY AND status = 'In Progress'\""
        search "$2" "${3:-}"
        ;;

    boards)
        api /rest/agile/1.0/board --data-urlencode "projectKeyOrId=$JIRA_PROJECT_KEY"
        ;;

    raw)
        [ $# -ge 2 ] || die "uso: jira.sh raw /rest/api/3/project/$JIRA_PROJECT_KEY"
        case "$2" in
            /rest/*) ;;
            *) die "raw solo acepta rutas que empiecen con /rest/" ;;
        esac
        path="$2"
        shift 2
        api "$path" "$@"
        ;;

    '' | -h | --help | help)
        usage 0
        ;;

    *)
        printf 'jira.sh: verbo desconocido: %s\n\n' "$1" >&2
        usage 1 >&2
        ;;
esac

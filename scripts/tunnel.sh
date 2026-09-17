#!/usr/bin/env bash
# scripts/tunnel.sh - Gestión del túnel Cloudflare para pruebas de QA remotas

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
TUNNEL_DIR="$PROJECT_ROOT/.tunnel"
mkdir -p "$TUNNEL_DIR"

PID_FILE="$TUNNEL_DIR/tunnel.pid"
LOG_FILE="$TUNNEL_DIR/tunnel.log"
PORT="${2:-80}"

# Localizar binario de cloudflared o descargarlo automáticamente
if [ -x "./bin/cloudflared" ]; then
    CLOUDFLARED="./bin/cloudflared"
elif [ -x "$HOME/.local/bin/cloudflared" ]; then
    CLOUDFLARED="$HOME/.local/bin/cloudflared"
elif command -v cloudflared &>/dev/null; then
    CLOUDFLARED="$(command -v cloudflared)"
else
    mkdir -p "$PROJECT_ROOT/bin"
    echo "⬇️  Descargando cloudflared en ./bin/cloudflared..."
    curl -sL https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64 -o "$PROJECT_ROOT/bin/cloudflared" && chmod +x "$PROJECT_ROOT/bin/cloudflared"
    if [ -x "$PROJECT_ROOT/bin/cloudflared" ]; then
        CLOUDFLARED="$PROJECT_ROOT/bin/cloudflared"
    else
        echo "❌ Error: No se encontró el binario de cloudflared en ./bin/cloudflared, ~/.local/bin/cloudflared ni en PATH."
        exit 1
    fi
fi

start_tunnel() {
    if [ -f "$PID_FILE" ] && kill -0 "$(cat "$PID_FILE")" 2>/dev/null; then
        echo "⚠️  El túnel ya se encuentra en ejecución (PID: $(cat "$PID_FILE"))."
        print_url
        return 0
    fi

    rm -f "$LOG_FILE"
    rm -f "$PROJECT_ROOT/public/hot"
    echo "🚀 Iniciando túnel Cloudflare hacia http://localhost:${PORT}..."

    # Ejecutar en segundo plano desacoplado en una nueva sesión
    setsid "$CLOUDFLARED" tunnel --url "http://localhost:${PORT}" > "$LOG_FILE" 2>&1 &
    local pid=$!
    disown "$pid" 2>/dev/null || true
    echo "$pid" > "$PID_FILE"

    # Esperar hasta 15 segundos a que cloudflared genere la URL
    local timeout=15
    local elapsed=0
    local url=""

    while [ $elapsed -lt $timeout ]; do
        if ! kill -0 "$pid" 2>/dev/null; then
            echo "❌ Error: El proceso de cloudflared terminó inesperadamente. Revisa $LOG_FILE:"
            cat "$LOG_FILE"
            rm -f "$PID_FILE"
            exit 1
        fi

        url=$(grep -o 'https://[a-zA-Z0-9-]\+\.trycloudflare\.com' "$LOG_FILE" | head -n 1)
        if [ -n "$url" ]; then
            echo ""
            echo "================================================================"
            echo "🌐 TÚNEL ACTIVO PARA PRUEBAS REMOTAS EN MÓVIL / CUALQUIER EQUIPO:"
            echo "🔗 URL: $url"
            echo "================================================================"
            echo ""
            return 0
        fi

        sleep 1
        elapsed=$((elapsed + 1))
    done

    echo "⚠️  El túnel inició pero no se detectó la URL en ${timeout}s. Revisa $LOG_FILE."
}

print_url() {
    if [ -f "$LOG_FILE" ]; then
        local url
        url=$(grep -o 'https://[a-zA-Z0-9-]\+\.trycloudflare\.com' "$LOG_FILE" | head -n 1)
        if [ -n "$url" ]; then
            echo "🔗 URL actual: $url"
            return 0
        fi
    fi
    echo "⚠️  No se encontró una URL activa en el registro."
}

status_tunnel() {
    if [ -f "$PID_FILE" ] && kill -0 "$(cat "$PID_FILE")" 2>/dev/null; then
        echo "✅ El túnel está ACTIVO (PID: $(cat "$PID_FILE"))."
        print_url
    else
        echo "⏹️  El túnel está DETENIDO."
    fi
}

stop_tunnel() {
    echo "🛑 Deteniendo túnel Cloudflare..."
    if [ -f "$PID_FILE" ]; then
        local pid
        pid=$(cat "$PID_FILE")
        if kill -0 "$pid" 2>/dev/null; then
            kill "$pid" 2>/dev/null
            sleep 1
            if kill -0 "$pid" 2>/dev/null; then
                kill -9 "$pid" 2>/dev/null
            fi
            echo "✅ Proceso $pid detenido."
        fi
        rm -f "$PID_FILE"
    fi

    # Limpieza de seguridad ante procesos huérfanos
    pkill -f "cloudflared tunnel --url http://localhost" 2>/dev/null || true
    rm -f "$LOG_FILE"
    echo "✅ Túnel cerrado completamente."
}

case "$1" in
    start)
        start_tunnel
        ;;
    status)
        status_tunnel
        ;;
    stop)
        stop_tunnel
        ;;
    url)
        print_url
        ;;
    *)
        echo "Uso: $0 {start|status|stop|url} [puerto_opcional]"
        exit 1
        ;;
esac

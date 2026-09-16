@echo off
where php >nul 2>nul
if %errorlevel% equ 0 (
    php vendor/bin/sail %*
) else (
    docker compose %*
)

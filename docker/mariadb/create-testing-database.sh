#!/usr/bin/env bash

mariadb -u root -p"$MYSQL_ROOT_PASSWORD" <<EOF
CREATE DATABASE IF NOT EXISTS testing;
GRANT ALL PRIVILEGES ON testing.* TO '$MYSQL_USER'@'%';
EOF

echo "MariaDB initialized for Tareas Simple."

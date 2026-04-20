#!/usr/bin/env bash

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SCRIPT_DIR="scripts"
MIGRATE_PHP="migrate.php"            # path relative to ROOT
DOCKER_SERVICE="pedialink-app-1"
PROJECT_PATH_IN_CONTAINER="/var/www/html"

# Helper: run command either locally or inside docker-compose service
_run() {
    docker exec -u 1000:1000 -it "$DOCKER_SERVICE" sh -c 'cd '"${PROJECT_PATH_IN_CONTAINER}"' && php '"${SCRIPT_DIR}/${MIGRATE_PHP}"' "$@"' -- "$@"
}

_print_help() {
    cat <<'EOF'
forge - project CLI shim

Usage:
  ./forge <command> [args...]

Common commands implemented for this app:
  make:migration <name>   Create a new migration file (e.g. create_users_table)
  db:migrate                 Run pending migrations
  db:rollback        Rollback the last batch
  db:status                  Show migration status
  help                    Show this help

Notes:
  - Script to run app commands
EOF
}

# No args -> help
if [[ $# -lt 1 ]]; then
    _print_help
    exit 0
fi

COMMAND="$1" # first argument
shift || true # shift the arguments (remove first argument from list)

case "$COMMAND" in
    make:migration)
        if [[ $# -lt 1 ]]; then
        echo "Usage: ./forge make:migration migration_name"
        exit 1
        fi
        NAME="$1"
        _run make $NAME
        ;;

    db:migrate)
        _run migrate
        ;;

    db:rollback)
        _run rollback "$@"
        ;;
    
    db:reset)
        _run reset "$@"
        ;;

    db:drop)
        _run drop
        ;;

    db:status)
        _run status
        ;;

    help|-h|--help)
        _print_help
        ;;
esac

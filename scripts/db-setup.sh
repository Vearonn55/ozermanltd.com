#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
MYSQL="${MYSQL:-mysql}"

if ! command -v "$MYSQL" >/dev/null 2>&1; then
  for candidate in \
    /opt/homebrew/opt/mysql@8.0/bin/mysql \
    /opt/homebrew/opt/mysql/bin/mysql \
    /usr/local/opt/mysql@8.0/bin/mysql \
    /usr/local/opt/mysql/bin/mysql; do
    if [ -x "$candidate" ]; then
      MYSQL="$candidate"
      break
    fi
  done
fi

if ! command -v "$MYSQL" >/dev/null 2>&1 && ! [ -x "$MYSQL" ]; then
  echo "Error: mysql client not found."
  echo "Install MySQL (brew install mysql@8.0) or use: make db-docker-setup"
  exit 1
fi
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-}"
DB_NAME="${DB_NAME:-ozermanltd}"

MYSQL_ARGS=(-u "$DB_USER")
if [ -n "$DB_PASS" ]; then
  MYSQL_ARGS+=(-p"$DB_PASS")
fi

echo "Creating database schema..."
"$MYSQL" "${MYSQL_ARGS[@]}" < "$ROOT/database/schema.sql"

echo "Inserting seed data..."
"$MYSQL" "${MYSQL_ARGS[@]}" "$DB_NAME" < "$ROOT/database/seed.sql"

echo "Done. Database '${DB_NAME}' is ready."

#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
MYSQL="${MYSQL:-mysql}"
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

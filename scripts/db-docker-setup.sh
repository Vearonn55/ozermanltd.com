#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

COMPOSE="${COMPOSE:-docker compose}"
MYSQL_SERVICE="${MYSQL_SERVICE:-mysql}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-root}"
DB_NAME="${DB_NAME:-ozermanltd}"

echo "Starting MySQL container..."
$COMPOSE up -d mysql

echo "Waiting for MySQL to be ready..."
for i in $(seq 1 30); do
  if $COMPOSE exec -T "$MYSQL_SERVICE" mysqladmin ping -h 127.0.0.1 -u"$DB_USER" -p"$DB_PASS" --silent 2>/dev/null; then
    break
  fi
  if [ "$i" -eq 30 ]; then
    echo "MySQL did not become ready in time."
    exit 1
  fi
  sleep 2
done

echo "Importing database/install.sql..."
$COMPOSE exec -T "$MYSQL_SERVICE" mysql -u"$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
$COMPOSE exec -T "$MYSQL_SERVICE" mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < database/install.sql

echo ""
echo "Docker MySQL is ready on 127.0.0.1:3306"
echo "  Database: ${DB_NAME}"
echo "  User:     ${DB_USER}"
echo "  Password: ${DB_PASS}"
echo "  Admin:    admin@ozermanltd.com"

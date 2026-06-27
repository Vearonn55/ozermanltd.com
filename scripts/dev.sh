#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
HOST="${HOST:-localhost}"
PORT="${PORT:-8080}"
PHP="${PHP:-php}"

cd "$ROOT/public"

echo "Ozerman Ltd — Development Server"
echo "────────────────────────────────"
echo "  English:  http://${HOST}:${PORT}/en"
echo "  Turkish:  http://${HOST}:${PORT}/tr"
echo "  Arabic:   http://${HOST}:${PORT}/ar"
echo ""
echo "Press Ctrl+C to stop."
echo ""

exec "$PHP" -S "${HOST}:${PORT}" router.php

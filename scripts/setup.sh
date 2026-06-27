#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"

if [ ! -f "$ROOT/.env" ]; then
  cp "$ROOT/.env.example" "$ROOT/.env"
  echo "Created .env from .env.example"
else
  echo ".env already exists — skipped"
fi

chmod +x "$ROOT/scripts/dev.sh" "$ROOT/scripts/db-setup.sh" 2>/dev/null || true

echo ""
echo "Setup complete. Run the site with:"
echo "  make dev"
echo "  # or"
echo "  ./scripts/dev.sh"

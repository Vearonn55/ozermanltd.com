# Ozerman Ltd — Run Commands
# Usage: make <target>

PHP      ?= /opt/homebrew/opt/php/bin/php
MYSQL    ?= mysql
HOST     ?= localhost
PORT     ?= 8080
DB_USER  ?= root
DB_PASS  ?=
DB_NAME  ?= ozermanltd

.PHONY: help dev setup db-create db-seed db-setup db-reset db-analytics db-up db-down db-docker-setup open stop import-seo sync-analytics reset-admin-password assets-install assets-build assets-watch

help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-18s\033[0m %s\n", $$1, $$2}'

dev: ## Start development server (http://localhost:$(PORT)/en)
	@echo "Starting Ozerman Ltd dev server..."
	@echo "  → http://$(HOST):$(PORT)/en"
	@echo "  → http://$(HOST):$(PORT)/tr"
	@echo "  → http://$(HOST):$(PORT)/ar"
	@echo "Press Ctrl+C to stop."
	cd public && $(PHP) -S $(HOST):$(PORT) router.php

setup: ## Copy .env.example to .env
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo "Created .env from .env.example"; \
	else \
		echo ".env already exists — skipped"; \
	fi

MYSQL_FLAGS := -u $(DB_USER) $(if $(DB_PASS),-p$(DB_PASS),) --default-character-set=utf8mb4

db-create: ## Create database and tables (schema.sql)
	$(MYSQL) $(MYSQL_FLAGS) < database/schema.sql
	@echo "Database schema created."

db-seed: ## Insert dummy seed data (seed.sql + content_seed.sql)
	$(MYSQL) $(MYSQL_FLAGS) $(DB_NAME) < database/seed.sql
	$(MYSQL) $(MYSQL_FLAGS) $(DB_NAME) < database/content_seed.sql
	@echo "Seed data inserted."

db-setup: db-create db-seed db-analytics ## Create database + all seed data + analytics tables

db-analytics: ## Create analytics/consent tables
	$(MYSQL) $(MYSQL_FLAGS) $(DB_NAME) < database/analytics_schema.sql
	@echo "Analytics schema created."

db-reset: ## Drop and recreate database (destructive)
	$(MYSQL) $(MYSQL_FLAGS) -e "DROP DATABASE IF EXISTS $(DB_NAME);"
	$(MAKE) db-setup

db-up: ## Start MySQL via Docker Compose
	docker compose up -d mysql

db-down: ## Stop MySQL Docker container
	docker compose down

db-docker-setup: ## Start Docker MySQL + load schema, seed, analytics (recommended if no local MySQL)
	bash scripts/db-docker-setup.sh

open: ## Open site in default browser (macOS)
	open http://$(HOST):$(PORT)/en

stop: ## Stop dev server on port $(PORT)
	@lsof -ti:$(PORT) | xargs kill -9 2>/dev/null && echo "Stopped server on port $(PORT)." || echo "No server running on port $(PORT)."

import-seo: ## Scrape legacy/local site SEO into storage/seo/
	$(PHP) bin/import-legacy-seo.php $(if $(LEGACY_URL),$(LEGACY_URL),)

sync-analytics: ## Push queued analytics to external API (if configured)
	$(PHP) bin/sync-analytics-queue.php

reset-admin-password: ## Reset admin password to admin123 (creates user if missing)
	$(PHP) bin/reset-admin-password.php

assets-install: ## Download Tailwind standalone CLI + vendor Alpine/Quill (no Node)
	@mkdir -p bin public/assets/vendor/alpinejs public/assets/vendor/quill
	@OS=$$(uname -s | tr '[:upper:]' '[:lower:]'); \
	ARCH=$$(uname -m); \
	case "$$OS-$$ARCH" in \
	  darwin-arm64) TW=macos-arm64 ;; \
	  darwin-x86_64) TW=macos-x64 ;; \
	  linux-x86_64|linux-amd64) TW=linux-x64 ;; \
	  linux-aarch64|linux-arm64) TW=linux-arm64 ;; \
	  *) echo "Unsupported platform: $$OS $$ARCH"; exit 1 ;; \
	esac; \
	if [ ! -x bin/tailwindcss ]; then \
	  echo "Downloading Tailwind CLI ($$TW)..."; \
	  curl -fsSL -o bin/tailwindcss "https://github.com/tailwindlabs/tailwindcss/releases/latest/download/tailwindcss-$$TW"; \
	  chmod +x bin/tailwindcss; \
	else \
	  echo "bin/tailwindcss already present"; \
	fi
	@if [ ! -f public/assets/vendor/alpinejs/alpine.min.js ]; then \
	  echo "Downloading Alpine.js..."; \
	  curl -fsSL -o public/assets/vendor/alpinejs/alpine.min.js "https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"; \
	else \
	  echo "Alpine already vendored"; \
	fi
	@if [ ! -f public/assets/vendor/quill/quill.js ]; then \
	  echo "Downloading Quill..."; \
	  curl -fsSL -o public/assets/vendor/quill/quill.js "https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"; \
	  curl -fsSL -o public/assets/vendor/quill/quill.snow.css "https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css"; \
	else \
	  echo "Quill already vendored"; \
	fi
	@echo "Assets install complete."

assets-build: ## Compile minified Tailwind CSS to public/assets/css/tailwind.css
	@test -x bin/tailwindcss || $(MAKE) assets-install
	./bin/tailwindcss -i ./resources/css/tailwind.css -o ./public/assets/css/tailwind.css --minify
	@echo "Built public/assets/css/tailwind.css"

assets-watch: ## Watch templates and rebuild Tailwind CSS
	@test -x bin/tailwindcss || $(MAKE) assets-install
	./bin/tailwindcss -i ./resources/css/tailwind.css -o ./public/assets/css/tailwind.css --watch
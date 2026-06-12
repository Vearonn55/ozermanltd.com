# Ozerman Ltd — Run Commands
# Usage: make <target>

PHP      ?= php
MYSQL    ?= mysql
HOST     ?= localhost
PORT     ?= 8080
DB_USER  ?= root
DB_PASS  ?=
DB_NAME  ?= ozermanltd

.PHONY: help dev setup db-create db-seed db-setup db-reset open stop

help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-14s\033[0m %s\n", $$1, $$2}'

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

db-create: ## Create database and tables (schema.sql)
	$(MYSQL) -u $(DB_USER) $(if $(DB_PASS),-p$(DB_PASS),) < database/schema.sql
	@echo "Database schema created."

db-seed: ## Insert dummy seed data (seed.sql)
	$(MYSQL) -u $(DB_USER) $(if $(DB_PASS),-p$(DB_PASS),) $(DB_NAME) < database/seed.sql
	@echo "Seed data inserted."

db-setup: db-create db-seed ## Create database + seed data

db-reset: ## Drop and recreate database (destructive)
	$(MYSQL) -u $(DB_USER) $(if $(DB_PASS),-p$(DB_PASS),) -e "DROP DATABASE IF EXISTS $(DB_NAME);"
	$(MAKE) db-setup

open: ## Open site in default browser (macOS)
	open http://$(HOST):$(PORT)/en

stop: ## Stop dev server on port $(PORT)
	@lsof -ti:$(PORT) | xargs kill -9 2>/dev/null && echo "Stopped server on port $(PORT)." || echo "No server running on port $(PORT)."

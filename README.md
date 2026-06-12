# Ozerman Ltd — Corporate Website

A modern, multilingual corporate website for **ozermanltd.com**, built per the project development report.

## Tech Stack

- **PHP 8.3** — Backend routing and templating
- **Tailwind CSS** — Utility-first styling (CDN)
- **Alpine.js** — Interactive UI (sliders, filters, lightbox, mobile menu)
- **MySQL 8.0+** — Full database schema (see `database/schema.sql`)

## Features

- Responsive corporate design (mobile-first)
- Multi-language support: English, Turkish, Arabic (RTL)
- Public pages: Home, About, Sectors, Projects, News, Gallery, Contact
- SEO: meta tags, Open Graph, JSON-LD structured data, canonical URLs
- Dummy content for demonstration (no database required to preview)

## Running Commands

### First-time setup

```bash
make setup          # Create .env from .env.example
# or
./scripts/setup.sh
```

### Start development server

```bash
make dev            # http://localhost:8080/en
# or
./scripts/dev.sh

# Custom port
make dev PORT=3000
# or
PORT=3000 ./scripts/dev.sh
```

### Stop development server

```bash
make stop           # Kill process on port 8080
# or press Ctrl+C in the terminal running the server
```

### Open in browser (macOS)

```bash
make open
```

### Database commands

```bash
make db-create      # Run schema.sql
make db-seed        # Run seed.sql
make db-setup       # Schema + seed
make db-reset       # Drop and recreate (destructive)

# With password
make db-setup DB_PASS=yourpassword
# or
./scripts/db-setup.sh
```

### All available commands

```bash
make help
```

## Quick Start (manual)

### Option 1: PHP Built-in Server (Development)

```bash
cd public
php -S localhost:8080 router.php
```

Visit: http://localhost:8080/en

### Option 2: Apache/cPanel

Point the document root to the `public/` directory. The included `.htaccess` files handle URL rewriting.

## Database Setup

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p ozermanltd < database/seed.sql
```

Copy `.env.example` to `.env` and configure database credentials.

## URL Structure

| Page | English | Turkish |
|------|---------|---------|
| Home | `/en` | `/tr` |
| About | `/en/about-us` | `/tr/about-us` |
| Sectors | `/en/sectors` | `/tr/sectors` |
| Projects | `/en/projects` | `/tr/projects` |
| News | `/en/news` | `/tr/news` |
| Gallery | `/en/gallery` | `/tr/gallery` |
| Contact | `/en/contact` | `/tr/contact` |

## Project Structure

```
├── app/
│   ├── Controllers/
│   ├── Data/          # Dummy content (mirrors DB schema)
│   ├── Helpers/
│   └── Views/
├── config/
├── database/
│   ├── schema.sql     # MySQL DDL (converted from DBML)
│   └── seed.sql       # Sample data
├── public/            # Web root
│   ├── assets/
│   └── index.php
└── routes/
```

## Next Steps (per Roadmap)

- Phase 4–5: Admin CMS with authentication
- Phase 7: Database-driven content (replace dummy data)
- Phase 8–10: SEO sitemap generation, performance optimization, security audit

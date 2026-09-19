# Özerman Ticaret — Corporate Website

Multilingual corporate site for [ozermanltd.com](https://ozermanltd.com): PHP front end, MySQL content, portable Admin CMS, SEO, cookie consent, and analytics.

## Soft launch (current production mode)

While `SITE_UNDER_CONSTRUCTION=true`:

| Path | Behavior |
|------|----------|
| `/qr` | Lajivert QR menu (locale-free) |
| `/catalogues` | Lajivert catalogues |
| `/admin` | Staff CMS |
| `/assets/*`, `/uploads/*` | Static files |
| Other public pages | Under construction (503) |

Production cutover (cPanel + Cloudflare): [`docs/PRODUCTION-LAUNCH.md`](docs/PRODUCTION-LAUNCH.md).  
Admin vs hosting ownership: [`docs/ADMIN-AND-CPANEL.md`](docs/ADMIN-AND-CPANEL.md).

## Stack

- PHP 8.3+ (routing & templates)
- Tailwind CSS + Alpine.js
- MySQL 8.0+
- Portable Admin module in [`modules/Admin`](modules/Admin)

## Quick start (local)

```bash
cp .env.example .env   # or: make setup
# Edit .env — never commit .env

make db-docker-setup   # or make db-setup with local MySQL
make assets-install && make assets-build   # if Tailwind not built yet
make dev
```

- Site: `http://localhost:8080/en` (or under construction if the flag is on)
- QR: `http://localhost:8080/qr`
- Admin: `http://localhost:8080/admin`

Create or reset an admin user with:

```bash
php bin/reset-admin-password.php you@example.com 'your-strong-password'
```

Do **not** use seed/default passwords in production. Rotate credentials after any shared or demo environment.

## Configuration

Copy [`.env.example`](.env.example) to `.env`. Important flags (see example file for the full list):

| Variable | Notes |
|----------|--------|
| `APP_ENV` | `development` locally; `production` on the host |
| `APP_URL` | Public site URL |
| `SITE_UNDER_CONSTRUCTION` | Soft-launch gate |
| `CLOUDFLARE_ENFORCE` | Origin-only-via-Cloudflare (production) |
| `DB_*` | Database connection |
| `DB_USE_DUMMY_DATA` | Prefer `false` when MySQL is available |
| `DB_FALLBACK_DUMMY` | Prefer `false` in production |

Never commit `.env`, API keys, or real passwords. Keep secrets in the host environment / cPanel only.

## Features

- Locales: English, Turkish, Arabic (`/en`, `/tr`, `/ar`)
- SEO: meta, Open Graph, hreflang, sitemap, redirects
- Cookie consent + optional analytics (consent-gated)
- Admin: pages (incl. HTML/CSS/JS embeds), news, projects, sectors, media library, contacts, SEO, users, audit log

## Project layout

```
app/                 # Public app (controllers, views, middleware, SEO)
modules/Admin/       # Portable CMS
config/              # App, DB, SEO, Cloudflare IP ranges
database/            # Schema and seeds
public/              # Document root (index.php, assets, uploads)
docs/                # Deploy and admin docs
bin/                 # CLI helpers
```

## Production notes

1. Document root stays **`public_html`** on hosts that lock it (see `.cpanel.yml`)
2. PHP 8.3+; writable `$HOME/storage/` and `$HOME/public_html/uploads/`
3. Cloudflare: orange-cloud DNS, SSL Full (strict), Always Use HTTPS
4. Set `CLOUDFLARE_ENFORCE` only after Cloudflare is live; use `SITE_UNDER_CONSTRUCTION` as needed
5. Disable dummy-data fallbacks in production

Details: [`docs/PRODUCTION-LAUNCH.md`](docs/PRODUCTION-LAUNCH.md).  
Database + admin QR setup: [`docs/CPANEL-DATABASE.md`](docs/CPANEL-DATABASE.md).

## License / private use

Internal project for Özerman Ticaret. Do not publish production secrets or visitor analytics dumps to this repository.

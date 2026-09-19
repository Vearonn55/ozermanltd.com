# Production launch — QR soft open + Cloudflare + cPanel

Soft-launch checklist for **ozermanltd.com**: only `/qr` and `/catalogues` are public; everything else shows Under Construction. Traffic should arrive via Cloudflare.

## What is live

| Path | Behavior |
|------|----------|
| `/qr` | Lajivert QR menu (locale-free) |
| `/catalogues` | Lajivert catalogues list → PDF |
| `/admin` | Staff CMS (login required) |
| `/assets/*`, `/uploads/*` | Static files |
| `/`, `/en`, about, news, … | **503 Under construction** while `SITE_UNDER_CONSTRUCTION=true` |

Catalogues card on `/qr` links to `/catalogues` on this domain. Lajivert WhatsApp / Instagram / Facebook / maps / PDF URLs are unchanged for now.

## Cloudflare (DNS + SSL)

1. Add the domain to Cloudflare (or point nameservers to Cloudflare).
2. DNS: orange-cloud **A/AAAA** for `@` and `www` → your cPanel origin IP.
3. SSL/TLS mode: **Full (strict)**.
4. Enable **Always Use HTTPS**.
5. Optional: Bot Fight Mode / Under Attack only if needed (not required by the app).

Visitor IP is restored via `CF-Connecting-IP`. With `CLOUDFLARE_ENFORCE=true`, the origin rejects requests that are not from Cloudflare IP ranges (blocks direct-to-cPanel hits).

Refresh published ranges occasionally from https://www.cloudflare.com/ips/ into [`config/cloudflare-ips.php`](../config/cloudflare-ips.php).

## cPanel / origin

Follow [`ADMIN-AND-CPANEL.md`](ADMIN-AND-CPANEL.md). Summary:

1. Deploy code; set document root to **`public/`**.
2. Create MySQL DB + user; import `database/schema.sql` (+ analytics if used) and seeds as needed.
3. Place `.env` from `.env.example` with production values:

```env
APP_ENV=production
APP_URL=https://ozermanltd.com
SITE_UNDER_CONSTRUCTION=true
CLOUDFLARE_ENFORCE=true
DB_USE_DUMMY_DATA=false
DB_FALLBACK_DUMMY=false
# + real DB_* credentials
# + strong APP_KEY
```

4. PHP **8.3+**; raise upload limits for Media Library.
5. Writable: `storage/`, `public/uploads/`.
6. AutoSSL (or valid cert) on the origin so Cloudflare Full (strict) works.
7. Cron (optional): `php /path/to/bin/sync-analytics-queue.php`.
8. Rotate admin password: `php bin/reset-admin-password.php admin@ozermanltd.com 'your-strong-password'`.

## Smoke test after cutover

- [ ] `https://ozermanltd.com/qr` — Lajivert menu loads; logo/icons from `/assets/images/lajivert/`
- [ ] Catalogues card opens `https://ozermanltd.com/catalogues`
- [ ] PDF link on catalogues still opens
- [ ] `https://ozermanltd.com/en` — Under construction (503)
- [ ] `https://ozermanltd.com/` — Under construction (503)
- [ ] `https://ozermanltd.com/admin` — login works
- [ ] Hitting the bare origin IP (bypassing Cloudflare) returns **403** when `CLOUDFLARE_ENFORCE=true`

## Local development

```env
APP_ENV=development
SITE_UNDER_CONSTRUCTION=true   # or false to see the full site
CLOUDFLARE_ENFORCE=false
```

```bash
make dev
# http://localhost:8080/qr
# http://localhost:8080/catalogues
# http://localhost:8080/en → under construction when flag is true
```

## Turning the full site on later

Set `SITE_UNDER_CONSTRUCTION=false` in production `.env` and clear any opcode cache if used. Keep `CLOUDFLARE_ENFORCE=true` in production.

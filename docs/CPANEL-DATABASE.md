# cPanel database + Admin QR/catalogues setup

Your hosting account uses a fixed `public_html` document root. App code and `.env` live in the **home directory** (`/home/ozermanl/`), not inside `public_html`.

## 1. Put credentials in `.env`

File Manager → `/home/ozermanl/.env` (create from `.env.example` if missing).

```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ozermanl_MAIN
DB_USERNAME=ozerman_SYSADMIN
DB_PASSWORD=YOUR_MYSQL_PASSWORD_HERE

DB_USE_DUMMY_DATA=false
DB_FALLBACK_DUMMY=false

APP_ENV=production
APP_URL=https://ozermanltd.com
SITE_UNDER_CONSTRUCTION=true
CLOUDFLARE_ENFORCE=false
APP_KEY=generate-a-long-random-string
```

Replace `YOUR_MYSQL_PASSWORD_HERE` with the password you set when creating the MySQL user in cPanel.  
Confirm the user **ozerman_SYSADMIN** is added to database **ozermanl_MAIN** with **ALL PRIVILEGES**.

On most cPanel hosts `DB_HOST` is `localhost` (not `127.0.0.1`).

## 2. Import schema

cPanel → **phpMyAdmin** → select `ozermanl_MAIN` → **Import**:

1. `database/schema.sql`
2. `database/seed.sql` (languages + default admin user row)
3. Optional: `database/content_seed.sql`, `database/analytics_schema.sql`

Or Terminal (paths may vary):

```bash
cd ~
mysql -u ozerman_SYSADMIN -p ozermanl_MAIN < database/schema.sql
mysql -u ozerman_SYSADMIN -p ozermanl_MAIN < database/seed.sql
```

(If SQL files are only under the deploy/home tree after Git deploy, use those absolute paths.)

## 3. Reset admin password

```bash
cd ~
php bin/reset-admin-password.php admin@ozermanltd.com 'ChooseAStrongPassword'
```

Then open `https://ozermanltd.com/admin` and sign in with that email/password.

## 4. Seed editable QR + catalogues pages

```bash
cd ~
php bin/seed-qr-landings.php
```

Creates/updates CMS pages for:

- `/qr`
- `/catalogues`

## 5. Edit HTML / upload catalogues (staff workflow)

1. **Admin → Media Library** — upload PDF (and images if needed).
2. **Admin → Pages → All Pages** — open **Lajivert Catalogues** (or **LAJIVERT QR**).
3. In the **HTML embed** / **CSS** fields, edit freely (WordPress-style).
4. Use **Insert media** on the HTML field to paste the uploaded PDF/image URL into the link/`href`.
5. Set status **Published** → Save.
6. Visit `https://ozermanltd.com/catalogues` and `/qr`.

The public `/qr` and `/catalogues` routes load these CMS pages when published; otherwise they fall back to the built-in templates.

## Troubleshooting admin login

| Symptom | Fix |
|---------|-----|
| “Cannot connect to MySQL” | Wrong `DB_*` in `$HOME/.env`; user not privileged on DB |
| “No account found” | Import `seed.sql` or run `reset-admin-password.php` |
| “Incorrect password” | Re-run reset script |
| Site 403 | Set `CLOUDFLARE_ENFORCE=false` until Cloudflare is ready |
| Changes not visible | Hard refresh; confirm page is **Published** |

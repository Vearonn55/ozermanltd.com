# cPanel database + Admin QR/catalogues setup

Your hosting account uses a fixed `public_html` document root. App code and `.env` live in the **home directory** (`/home/ozermanl/`), not inside `public_html`.

**Important:** Editing `.env` on your Mac (Cursor) does **not** change the live site. You must edit `/home/ozermanl/.env` on the server.

If admin says `Cannot connect to MySQL on 127.0.0.1`, the server `.env` is still using local defaults (or is missing). Fix it below.

## 1. Put credentials in `.env` (on the server)

File Manager → **Settings** → enable **Show Hidden Files** → open `/home/ozermanl/` (home, not `public_html`).

Edit **`.env`** (copy from `.env.cpanel.example` if missing). Use the **exact** names from cPanel → **MySQL Databases** → Current Databases / Current Users.

Your account is `ozermanl`, so names look like `ozermanl_…` — **not** `ozerman_…`:

```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ozermanl_MAIN
DB_USERNAME=ozermanl_SYSADMIN
DB_PASSWORD=YOUR_MYSQL_PASSWORD_HERE

APP_ENV=production
APP_URL=https://ozermanltd.com
SITE_UNDER_CONSTRUCTION=true
CLOUDFLARE_ENFORCE=false
APP_KEY=generate-a-long-random-string
```

Replace `YOUR_MYSQL_PASSWORD_HERE` with the password from MySQL Databases / Database Wizard.  
If the password contains `#` `'` `"` or spaces, wrap it in double quotes: `DB_PASSWORD="your#pass"`.

Confirm under **Current Databases** that user **`ozermanl_SYSADMIN`** (check spelling) is attached to **`ozermanl_MAIN`** with **ALL PRIVILEGES**.

On most cPanel hosts `DB_HOST` is `localhost` (UNIX socket — same as phpMyAdmin).

### Verify from Terminal

```bash
cd ~
php bin/check-db.php
```

You want `RESULT: connection OK`. If `.env exists: NO` or `DB_HOST: 127.0.0.1`, fix the file path/contents and run again.

## 2. Import the database

cPanel MySQL users **cannot** run `CREATE DATABASE`. You already created `ozermanl_MAIN` — use that.

**phpMyAdmin:**

1. Left sidebar → click **`ozermanl_MAIN`** (must be selected / bold).
2. Top tab → **Import**.
3. Choose `database/install.sql` and import it.

That file creates every table, the English / Turkish / Arabic language rows, and one admin account. It does not insert pages or other content. Re-importing it drops and recreates the tables.

Or Terminal:

```bash
cd ~
mysql -u ozermanl_SYSADMIN -p ozermanl_MAIN < database/install.sql
```

Sign in at `https://ozermanltd.com/admin` with `admin@ozermanltd.com` and the password from the install.

## 3. Create the QR and catalogues pages

No Terminal needed: after logging into Admin, open **Pages → All Pages** and click **Create QR & Catalogues pages** (shown when they are missing).

Or CLI:

```bash
cd ~
php bin/seed-qr-landings.php
```

Creates/updates CMS pages for:

- `/qr` → **LAJIVERT QR**
- `/catalogues` → **Lajivert Catalogues**

## 4. Edit HTML / upload catalogues (staff workflow)

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
| “Cannot connect… host=localhost… ozermanl_MAIN” | Wrong username spelling (must be `ozermanl_SYSADMIN`, not `ozerman_SYSADMIN`), password mismatch, or user not added to DB. Copy exact names from MySQL Databases. |
| “Access denied” in error detail | Reset MySQL user password in cPanel and paste the same value into `.env` (quote if it has `#` or spaces). |
| “Unknown database” | Database name typo — must match cPanel exactly (e.g. `ozermanl_MAIN`). |
| “No account found” | Import `database/install.sql`, or run `php bin/reset-admin-password.php admin@ozermanltd.com 'a-new-password'` |
| “Incorrect password” | Re-run the reset script |
| Blank white `/admin` after login | PHP fatal (often missing tables). Deploy latest; you should see an error card. Import `database/install.sql` into `ozermanl_MAIN`. |
| Changes not visible | Hard refresh; confirm page is **Published** |


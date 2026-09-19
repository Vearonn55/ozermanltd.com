# Admin Panel + cPanel Split Strategy

Ownership model for **ozermanltd.com** and any host site that mounts the portable Admin module.

## Ownership rule

| Layer | Who | Rule |
|-------|-----|------|
| **cPanel / WHM** | Developer only | Server survival: uptime, SSL, DNS, mail, PHP version, backups, cron *triggers*, emergency phpMyAdmin |
| **Admin module (`/admin`)** | Staff / operators | Login, roles, content, **media library**, workflows, audit, settings |

If it needs a role → **not** cPanel. If it is about the box staying online → **not** the admin panel.

**Media:** staff upload, organize, replace, and attach files via `/admin/media`. cPanel File Manager / FTP is **not** the media workflow. PHP upload limits and disk quotas stay in cPanel; day-to-day media ops stay in Admin.

## cPanel / WHM (infrastructure)

| Category | Mapping |
|----------|---------|
| Document root | Prefer `public/` as the cPanel document root |
| Backups | cPanel Backup Wizard / JetBackup — full account |
| SSL/TLS | AutoSSL + force HTTPS |
| DNS / Domains | Zone editor, subdomains, addon domains |
| Cron | Trigger only → `php /path/to/bin/*.php` (e.g. analytics sync) |
| Email | Mailboxes / SPF / DKIM in cPanel; app SMTP via `.env` if needed |
| PHP | **8.3+**; raise `memory_limit`, `upload_max_filesize`, `post_max_size`, `max_execution_time` for Media Library |
| File access | SFTP for deploys; File Manager = emergency only |
| phpMyAdmin | Developer-only; never routine content/media ops |
| Writable paths | `storage/`, `public/uploads/` (media), `.env` outside web root when possible |
| Production env | `DB_USE_DUMMY_DATA=false`, prefer `DB_FALLBACK_DUMMY=false` |

### cPanel production cutover checklist

1. Deploy code; set document root to `public/`
2. Create MySQL database + user; import `database/schema.sql` (+ analytics schema if used) and seeds
3. Place `.env` from `.env.example`; set production DB credentials and `APP_URL`
4. Set `DB_USE_DUMMY_DATA=false` and `DB_FALLBACK_DUMMY=false`
5. Soft launch: `SITE_UNDER_CONSTRUCTION=true`, `CLOUDFLARE_ENFORCE=true` (see [`PRODUCTION-LAUNCH.md`](PRODUCTION-LAUNCH.md))
6. Ensure `storage/` and `public/uploads/` are writable by the PHP user
7. MultiPHP **8.3+**; set upload limits large enough for staff media (e.g. 20–64M)
8. Enable AutoSSL; force HTTPS (prefer Cloudflare Full strict + Always Use HTTPS)
9. Schedule cron for `bin/sync-analytics-queue.php` (and any future `bin/` jobs)
10. Rotate default admin password (`make reset-admin-password` or Admin → Users)
11. Smoke-test: `/qr`, `/catalogues`, under-construction on `/en`, `/admin` login, Media Library upload

## Portable Admin module

Location: [`modules/Admin`](../modules/Admin)

Any host site that shares the **Admin schema contract** can mount the same panel.

### Layout

```
modules/Admin/
  config/admin.php       # defaults (brand, paths, roles, upload limits)
  routes.php             # admin router
  src/                   # Controllers, Repositories, Auth, Middleware, Support
  views/                 # layouts, partials, entity UIs
  schema/admin_contract.sql
  README.md              # host integration
```

Namespace: `Admin\`

### Host responsibilities

- `bootstrap.php`, `.env`, database config, PDO factory (`App\Infrastructure\Database`)
- Mount in front controller:

```php
if ($requestUri === '/admin' || str_starts_with($requestUri, '/admin/')) {
    require MODULES_PATH . '/Admin/routes.php';
    exit;
}
```

- Override branding via `config/admin.php` (merged into `config('admin.*')`)
- Public site stays outside the module

### Host contract

| Contract | Purpose |
|----------|---------|
| PDO MySQL matching schema contract | Tables the module CRUDs |
| `config('locales')` | Multilang tabs |
| `config('admin.*')` | `brand_name`, `view_site_url`, `upload_path`, `upload_url`, roles |
| Writable upload + storage paths | Media Library + logs |
| Shared helpers | `config()`, `e()`, `redirect()` (host) + module `admin_*` helpers |

### Schema contract

See [`modules/Admin/schema/admin_contract.sql`](../modules/Admin/schema/admin_contract.sql):

- Auth: `users`, `user_sessions`, `user_activity_log`
- i18n: `languages`
- Content: pages/news/projects/sectors (+ translations)
- Media: `media`, `media_folders`
- Ops: `contact_messages`, `settings`, `seo_meta`
- Analytics (optional): analytics tables

**Portability rule:** do not add host-only tables inside the module without extending the published contract. Brand via config — do not fork controllers for site name.

### Integrating on another site

1. Copy or git-subtree `modules/Admin`
2. Import/share DB matching the schema contract
3. Point PDO + host `config/admin.php` overrides
4. Mount router from the host front controller
5. Ensure upload directory writable and PHP upload limits set in that site’s cPanel

## Role matrix

| Capability | `editor` | `content_manager` | `super_admin` |
|------------|----------|-------------------|---------------|
| Dashboard | yes | yes | yes |
| Pages / News / Projects / Sectors | yes | yes | yes |
| Media Library browse / upload / edit | yes | yes | yes |
| Media delete | — | yes | yes |
| Contacts | yes | yes | yes |
| Analytics | yes | yes | yes |
| SEO meta | yes | yes | yes |
| Settings | — | yes | yes |
| Users | — | — | yes |
| Audit log | — | yes | yes |
| App logs | — | — | yes |
| CSV exports | yes | yes | yes |

## Staff Media Library

Core operations (same tier as content CRUD):

- Browse / search / folders
- Upload with MIME + size allowlist
- Edit alt/caption; move folder
- Delete (content_manager+) with reference checks where practical
- Attach via picker on News, Projects, and related entities

cPanel only supplies capacity (disk, PHP limits).

## Developing guidelines

1. Infra in cPanel; product ops (including media) in Admin.
2. Cron = thin trigger; logic in `bin/`.
3. Admin lives in `modules/Admin`; host only mounts + configures.
4. Same-schema portability — extend the contract before adding tables.
5. Security: CSRF, escaping, PDO, bcrypt, roles, upload allowlists, login rate limit.
6. Routine files go through Media Library — never File Manager.
7. No Composer required for the module.
8. Public may use DummyData in dev; Admin always forces DB.

## Roadmap status

| Phase | Scope | Status |
|-------|-------|--------|
| 0 | Strategy docs + README links | Done |
| 1 | Module extraction, RBAC, rate limit, audit viewer | Done |
| 2 | Media Library, settings, user management | Done |
| 3 | SEO editor, CSV exports, app-log viewer | Done |
| 4 | cPanel cutover checklist (ops) | Documented above |

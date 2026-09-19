# Portable Admin Module

Reusable CMS admin panel for hosts that share the **Admin schema contract**.

## Mount on a host

1. Copy or git-subtree this `modules/Admin` directory into the host project.
2. Autoload `Admin\` from `modules/Admin/src/` and require `src/Support/helpers.php` after host helpers (`config`, `e`, `redirect`, CSRF/flash).
3. Provide PDO via `App\Infrastructure\Database::connection(force: true)` (or adapt the repositories to your PDO factory).
4. Merge config: module `config/admin.php` defaults + host `config/admin.php` overrides.
5. Mount in the front controller:

```php
if ($requestUri === '/admin' || str_starts_with($requestUri, '/admin/')) {
    require MODULES_PATH . '/Admin/routes.php';
    exit;
}
```

6. Ensure `upload_path` is writable and PHP upload limits are set (cPanel MultiPHP).
7. Import schema matching `schema/admin_contract.sql` (full DDL in host `database/schema.sql`).

## Host config keys

| Key | Purpose |
|-----|---------|
| `brand_name` | CMS title in layouts / login |
| `view_site_url` | “View site” link |
| `upload_path` / `upload_url` | Media Library storage |
| `default_login_hint` | Optional login placeholder |
| `features.*` | Toggle sidebar modules |
| `roles` | Role hierarchy levels |

## Staff Media Library

Editors and above use `/admin/media` for uploads. Do not use cPanel File Manager for routine media.

## Docs

See host [`docs/ADMIN-AND-CPANEL.md`](../../docs/ADMIN-AND-CPANEL.md) for ownership split and cPanel checklist.

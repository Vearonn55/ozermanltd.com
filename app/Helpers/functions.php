<?php

declare(strict_types=1);

function config(string $key, $default = null)
{
    static $appConfig = null;
    if ($appConfig === null) {
        $appConfig = require CONFIG_PATH . '/app.php';
    }

    return $appConfig[$key] ?? $default;
}

function seo_config(string $key, $default = null)
{
    static $seoConfig = null;
    if ($seoConfig === null) {
        $seoConfig = require CONFIG_PATH . '/seo.php';
    }

    if ($key === '') {
        return $seoConfig;
    }

    if (!str_contains($key, '.')) {
        return $seoConfig[$key] ?? $default;
    }

    $value = $seoConfig;
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function build_seo(array $context): \App\Services\Seo\SeoDto
{
    $context['locale'] = $context['locale'] ?? app_locale();
    return (new \App\Services\Seo\SeoService())->build($context);
}

function analytics_config(string $key, $default = null)
{
    static $analyticsConfig = null;
    if ($analyticsConfig === null) {
        $analyticsConfig = require CONFIG_PATH . '/analytics.php';
    }

    if ($key === '') {
        return $analyticsConfig;
    }

    if (!str_contains($key, '.')) {
        return $analyticsConfig[$key] ?? $default;
    }

    $value = $analyticsConfig;
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function json_response(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function db_fallback_to_dummy(): bool
{
    $config = require CONFIG_PATH . '/database.php';
    return !empty($config['fallback_to_dummy']);
}

function content(): \App\Repositories\ContentRepository
{
    static $repository = null;
    return $repository ??= new \App\Repositories\ContentRepository();
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = '', ?string $locale = null): string
{
    $locale = $locale ?? app_locale();
    $path = ltrim($path, '/');

    if ($path === '') {
        return '/' . $locale;
    }

    return '/' . $locale . '/' . $path;
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function app_locale(): string
{
    return $GLOBALS['app_locale'] ?? config('default_locale', 'en');
}

function set_locale(string $locale): void
{
    $locales = config('locales', []);
    $GLOBALS['app_locale'] = array_key_exists($locale, $locales) ? $locale : config('default_locale', 'en');
}

function locale_dir(): string
{
    return config('locales')[app_locale()]['dir'] ?? 'ltr';
}

function t(array $translations, ?string $locale = null): string
{
    $locale = $locale ?? app_locale();
    return $translations[$locale] ?? $translations['en'] ?? '';
}

function view(string $name, array $data = []): void
{
    extract($data);
    $viewFile = APP_PATH . '/Views/' . str_replace('.', '/', $name) . '.php';

    if (!file_exists($viewFile)) {
        http_response_code(500);
        echo 'View not found: ' . e($name);
        return;
    }

    require APP_PATH . '/Views/layouts/main.php';
}

function partial(string $name, array $data = []): void
{
    extract($data);
    require APP_PATH . '/Views/partials/' . $name . '.php';
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function current_path(): string
{
    return $GLOBALS['current_path'] ?? '';
}

function is_active(string $path): bool
{
    $current = trim(current_path(), '/');
    $check = trim($path, '/');

    if ($check === '' || $check === app_locale()) {
        return $current === app_locale() || $current === '';
    }

    return strpos($current, app_locale() . '/' . $check) === 0 || $current === app_locale() . '/' . $check;
}

function page_title(string $title): string
{
    return $title . ' | ' . config('name');
}

function locale_path_for(string $targetLocale): string
{
    $current = trim(current_path(), '/');
    $currentLocale = app_locale();

    if ($current === $currentLocale || $current === '') {
        return '/' . $targetLocale;
    }

    if (strpos($current, $currentLocale . '/') === 0) {
        return '/' . $targetLocale . '/' . substr($current, strlen($currentLocale) + 1);
    }

    return '/' . $targetLocale;
}

function format_date(string $date): string
{
    return date('F j, Y', strtotime($date));
}

function relative_path(): string
{
    $current = trim(current_path(), '/');
    $locale = app_locale();

    if ($current === $locale || $current === '') {
        return '';
    }

    if (strpos($current, $locale . '/') === 0) {
        return substr($current, strlen($locale) + 1);
    }

    return $current;
}

function admin_view(string $name, array $data = []): void
{
    extract($data);
    $viewFile = APP_PATH . '/Views/admin/' . str_replace('.', '/', $name) . '.php';

    if (!file_exists($viewFile)) {
        http_response_code(500);
        echo 'Admin view not found: ' . e($name);
        return;
    }

    require APP_PATH . '/Views/admin/layouts/main.php';
}

function admin_guest_view(string $name, array $data = []): void
{
    extract($data);
    $viewFile = APP_PATH . '/Views/admin/' . str_replace('.', '/', $name) . '.php';

    if (!file_exists($viewFile)) {
        http_response_code(500);
        echo 'Admin view not found: ' . e($name);
        return;
    }

    require APP_PATH . '/Views/admin/layouts/guest.php';
}

function admin_partial(string $name, array $data = []): void
{
    extract($data);
    require APP_PATH . '/Views/admin/partials/' . $name . '.php';
}

function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        (new \App\Services\Auth\AuthService())->startSession();
    }

    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(string $token): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        (new \App\Services\Auth\AuthService())->startSession();
    }

    return isset($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], $token);
}

function flash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        (new \App\Services\Auth\AuthService())->startSession();
    }

    $_SESSION['_flash'][$type] = $message;
}

function get_flash(string $type): ?string
{
    if (session_status() === PHP_SESSION_NONE) {
        (new \App\Services\Auth\AuthService())->startSession();
    }

    if (!isset($_SESSION['_flash'][$type])) {
        return null;
    }

    $message = $_SESSION['_flash'][$type];
    unset($_SESSION['_flash'][$type]);

    return $message;
}

function admin_url(string $path = ''): string
{
    return '/admin' . ($path !== '' ? '/' . ltrim($path, '/') : '');
}

function admin_active(string $path): bool
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
    $target = admin_url($path);

    if ($path === '') {
        return $uri === '/admin';
    }

    return str_starts_with($uri, $target);
}

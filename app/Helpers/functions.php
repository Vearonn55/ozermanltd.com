<?php

declare(strict_types=1);

/**
 * Value from the .env file. The file wins over variables cPanel already set.
 */
function env(string $key, string $default = ''): string
{
    if (array_key_exists($key, $_ENV) && !is_array($_ENV[$key])) {
        $value = trim((string) $_ENV[$key]);
        if ($value !== '') {
            return $value;
        }
    }

    if (array_key_exists($key, $_SERVER) && !is_array($_SERVER[$key])) {
        $value = trim((string) $_SERVER[$key]);
        if ($value !== '') {
            return $value;
        }
    }

    $fromProcess = getenv($key);
    if ($fromProcess !== false && trim($fromProcess) !== '') {
        return trim($fromProcess);
    }

    return $default;
}

function env_bool(string $key, bool $default = false): bool
{
    return in_array(strtolower(env($key, $default ? 'true' : 'false')), ['1', 'true', 'yes', 'on'], true);
}

function config(string $key, $default = null)
{
    static $appConfig = null;
    static $adminConfig = null;

    if ($key === 'admin' || str_starts_with($key, 'admin.')) {
        if ($adminConfig === null) {
            $defaults = defined('ADMIN_MODULE_PATH') && file_exists(ADMIN_MODULE_PATH . '/config/admin.php')
                ? require ADMIN_MODULE_PATH . '/config/admin.php'
                : [];
            $host = file_exists(CONFIG_PATH . '/admin.php')
                ? require CONFIG_PATH . '/admin.php'
                : [];
            $adminConfig = array_replace_recursive($defaults, $host);

            if (empty($adminConfig['upload_path'])) {
                $adminConfig['upload_path'] = (defined('PUBLIC_PATH') ? PUBLIC_PATH : dirname(CONFIG_PATH) . '/public') . '/uploads';
            }
        }

        if ($key === 'admin') {
            return $adminConfig;
        }

        $value = $adminConfig;
        foreach (array_slice(explode('.', $key), 1) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    if ($appConfig === null) {
        $appConfig = require CONFIG_PATH . '/app.php';
    }

    if (!str_contains($key, '.')) {
        return $appConfig[$key] ?? $default;
    }

    $value = $appConfig;
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
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
    $relative = ltrim($path, '/');
    $url = '/assets/' . $relative;
    $file = dirname(__DIR__, 2) . '/public/assets/' . $relative;

    if (is_file($file)) {
        $url .= '?v=' . filemtime($file);
    }

    return $url;
}

/**
 * Public URL for an uploaded media file_path (or absolute/external URL).
 */
function media_url(?string $filePath, string $fallback = ''): string
{
    if ($filePath === null || $filePath === '') {
        return $fallback;
    }

    if (str_starts_with($filePath, 'http://') || str_starts_with($filePath, 'https://') || str_starts_with($filePath, '/')) {
        return $filePath;
    }

    $uploadUrl = rtrim((string) config('admin.upload_url', '/uploads'), '/');

    return $uploadUrl . '/' . ltrim($filePath, '/');
}

function app_locale(): string
{
    return $GLOBALS['app_locale'] ?? config('default_locale', 'tr');
}

function set_locale(string $locale): void
{
    $locales = config('locales', []);
    $GLOBALS['app_locale'] = array_key_exists($locale, $locales) ? $locale : config('default_locale', 'tr');
}

function locale_dir(): string
{
    return config('locales')[app_locale()]['dir'] ?? 'ltr';
}

function t(array $translations, ?string $locale = null): string
{
    $locale = $locale ?? app_locale();
    $fallback = (string) config('default_locale', 'tr');
    $order = [$locale, $fallback, 'en'];

    foreach ($order as $code) {
        $value = $translations[$code] ?? null;
        if (is_string($value) && trim($value) !== '') {
            return $value;
        }
    }

    foreach ($translations as $value) {
        if (is_string($value) && trim($value) !== '') {
            return $value;
        }
    }

    return '';
}

/**
 * Contact lines: prefer the current language, and skip leftover placeholder
 * copy when another language already has a real address or visiting hours.
 */
function t_filled(array $translations, ?string $locale = null): string
{
    $placeholders = [
        'current address and visiting details will be added.',
        'güncel adres ve ziyaret bilgileri eklenecek.',
        'سيتم إضافة العنوان الحالي وتفاصيل الزيارة.',
        'visiting hours will be added.',
        'ziyaret saatleri eklenecek.',
        'ستُضاف ساعات الزيارة.',
    ];
    $usable = static function (mixed $value) use ($placeholders): ?string {
        if (!is_string($value)) {
            return null;
        }
        $value = trim($value);
        if ($value === '' || in_array(mb_strtolower($value), $placeholders, true)) {
            return null;
        }

        return $value;
    };

    $locale = $locale ?? app_locale();
    $fallback = (string) config('default_locale', 'tr');
    foreach ([$locale, $fallback, 'en'] as $code) {
        $value = $usable($translations[$code] ?? null);
        if ($value !== null) {
            return $value;
        }
    }
    foreach ($translations as $value) {
        $value = $usable($value);
        if ($value !== null) {
            return $value;
        }
    }

    return t($translations, $locale);
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

function format_date(?string $date): string
{
    if ($date === null || $date === '' || $date === '0000-00-00' || str_starts_with($date, '0000-00-00')) {
        return '';
    }

    $ts = strtotime($date);
    if ($ts === false) {
        return '';
    }

    return date('F j, Y', $ts);
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

function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        (new \Admin\Services\Auth\AuthService())->startSession();
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
        (new \Admin\Services\Auth\AuthService())->startSession();
    }

    return isset($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], $token);
}

function flash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        (new \Admin\Services\Auth\AuthService())->startSession();
    }

    $_SESSION['_flash'][$type] = $message;
}

function get_flash(string $type): ?string
{
    if (session_status() === PHP_SESSION_NONE) {
        (new \Admin\Services\Auth\AuthService())->startSession();
    }

    if (!isset($_SESSION['_flash'][$type])) {
        return null;
    }

    $message = $_SESSION['_flash'][$type];
    unset($_SESSION['_flash'][$type]);

    return $message;
}

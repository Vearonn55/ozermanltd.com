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

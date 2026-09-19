<?php

declare(strict_types=1);

/**
 * Lightweight bootstrap for Ozerman Ltd corporate website.
 */

define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
// cPanel locked docroot uses public_html; local/dev uses public/
define(
    'PUBLIC_PATH',
    is_dir(BASE_PATH . '/public_html')
        ? BASE_PATH . '/public_html'
        : BASE_PATH . '/public'
);
define('MODULES_PATH', BASE_PATH . '/modules');
define('ADMIN_MODULE_PATH', MODULES_PATH . '/Admin');

$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        // Strip UTF-8 BOM (File Manager / Windows editors).
        if (str_starts_with($line, "\xEF\xBB\xBF")) {
            $line = substr($line, 3);
        }
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // Keep passwords with # or spaces when quoted.
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }

        if ($key === '') {
            continue;
        }

        // File values win (cPanel may have empty DB_* in the process environment).
        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strpos($class, $prefix) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = APP_PATH . '/' . $relative . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

spl_autoload_register(function (string $class): void {
    $prefix = 'Admin\\';
    if (strpos($class, $prefix) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = ADMIN_MODULE_PATH . '/src/' . $relative . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

require APP_PATH . '/Helpers/functions.php';
require ADMIN_MODULE_PATH . '/src/Support/helpers.php';

$config = require CONFIG_PATH . '/app.php';
$dbConfig = require CONFIG_PATH . '/database.php';

date_default_timezone_set('UTC');

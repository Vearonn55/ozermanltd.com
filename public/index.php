<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/') ?: '/';

if ($requestUri === '/') {
    $defaultLocale = config('default_locale', 'en');
    header('Location: /' . $defaultLocale, true, 302);
    exit;
}

$segments = array_values(array_filter(explode('/', trim($requestUri, '/'))));
$locale = $segments[0] ?? config('default_locale', 'en');

set_locale($locale);

$path = implode('/', $segments);
$GLOBALS['current_path'] = $path;

require dirname(__DIR__) . '/routes/web.php';

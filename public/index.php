<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\SeoController;
use App\Middleware\RedirectMiddleware;

$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/') ?: '/';

if ($requestUri === '/sitemap.xml') {
    (new SeoController())->sitemap();
    exit;
}

if (str_starts_with($requestUri, '/api/')) {
    require dirname(__DIR__) . '/routes/api.php';
    exit;
}

if ($requestUri === '/admin' || str_starts_with($requestUri, '/admin/')) {
    require dirname(__DIR__) . '/routes/admin.php';
    exit;
}

(new RedirectMiddleware())->handle($requestUri);

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

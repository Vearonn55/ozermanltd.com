<?php

declare(strict_types=1);

use App\Controllers\PageController;

$controller = new PageController();

$routes = [
    '' => 'home',
    'about-us' => 'about',
    'sectors' => 'sectors',
    'sectors/{slug}' => 'sector',
    'projects' => 'projects',
    'projects/{slug}' => 'project',
    'news' => 'news',
    'news/{slug}' => 'article',
    'gallery' => 'gallery',
    'contact' => 'contact',
    'privacy-policy' => 'privacyPolicy',
    'cookie-policy' => 'cookiePolicy',
    'cookie-settings' => 'cookieSettings',
];

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = $GLOBALS['current_path'] ?? '';

if ($method === 'POST' && $path === app_locale() . '/contact') {
    $controller->contactSubmit();
    exit;
}

$relativePath = $path;
$locale = app_locale();
if (strpos($relativePath, $locale . '/') === 0) {
    $relativePath = substr($relativePath, strlen($locale) + 1);
} elseif ($relativePath === $locale) {
    $relativePath = '';
}

$matched = false;

foreach ($routes as $pattern => $action) {
    $regex = '#^' . preg_replace('#\{[a-z]+\}#', '([^/]+)', $pattern) . '$#';

    if (preg_match($regex, $relativePath, $matches)) {
        array_shift($matches);
        $controller->$action(...$matches);
        $matched = true;
        break;
    }
}

if (!$matched) {
    $controller->notFound();
}

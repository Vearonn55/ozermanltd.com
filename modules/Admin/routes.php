<?php

declare(strict_types=1);

use Admin\Controllers\ActivityController;
use Admin\Controllers\AnalyticsController;
use Admin\Controllers\AuthController;
use Admin\Controllers\BannerController;
use Admin\Controllers\CategoryController;
use Admin\Controllers\CommentController;
use Admin\Controllers\ContactController;
use Admin\Controllers\ContentBlockController;
use Admin\Controllers\DashboardController;
use Admin\Controllers\GalleryController;
use Admin\Controllers\HeroSlideController;
use Admin\Controllers\LogController;
use Admin\Controllers\MediaController;
use Admin\Controllers\MenuController;
use Admin\Controllers\NewsController;
use Admin\Controllers\OfficeController;
use Admin\Controllers\PageController;
use Admin\Controllers\PartnershipController;
use Admin\Controllers\ProjectController;
use Admin\Controllers\SectorController;
use Admin\Controllers\SeoController;
use Admin\Controllers\SettingsController;
use Admin\Controllers\StatCounterController;
use Admin\Controllers\StoreController;
use Admin\Controllers\TeamController;
use Admin\Controllers\UserController;
use Admin\Services\Auth\AuthService;

(new AuthService())->startSession();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';
$base = rtrim((string) admin_config('mount_path', '/admin'), '/');

/*
 * Generic RESTful resources. Supported endpoints per resource:
 *   GET  {base}/{path}              index
 *   GET  {base}/{path}/create       create
 *   POST {base}/{path}              store
 *   GET  {base}/{path}/{id}/edit    edit
 *   POST {base}/{path}/{id}         update (or destroy with _method=DELETE)
 *   POST {base}/{path}/bulk         bulk        (when the controller defines it)
 *   POST {base}/{path}/{id}/status  toggleStatus (when the controller defines it)
 *   POST {base}/{path}/{id}/autosave autosave    (when the controller defines it)
 */
$resources = [
    'pages' => PageController::class,
    'news' => NewsController::class,
    'projects' => ProjectController::class,
    'sectors' => SectorController::class,
    'hero-slides' => HeroSlideController::class,
    'stats' => StatCounterController::class,
    'site-sections' => ContentBlockController::class,
    'gallery' => GalleryController::class,
    'stores' => StoreController::class,
    'partnerships' => PartnershipController::class,
    'team' => TeamController::class,
    'offices' => OfficeController::class,
    'menus' => MenuController::class,
    'categories' => CategoryController::class,
    'users' => UserController::class,
    'seo' => SeoController::class,
];

foreach ($resources as $path => $class) {
    $prefix = preg_quote($base . '/' . $path, '#');

    if (preg_match('#^' . $prefix . '(?:/(\d+))?(?:/(edit|status|autosave))?$#', $uri, $m) !== 1
        && preg_match('#^' . $prefix . '/(create|bulk)$#', $uri, $m2) !== 1) {
        continue;
    }

    $controller = new $class();

    if (isset($m2[1])) {
        if ($m2[1] === 'create' && $method === 'GET') {
            $controller->create();
            exit;
        }
        if ($m2[1] === 'bulk' && $method === 'POST' && method_exists($controller, 'bulk')) {
            $controller->bulk();
            exit;
        }
        continue;
    }

    $id = isset($m[1]) && $m[1] !== '' ? (int) $m[1] : null;
    $action = $m[2] ?? null;

    if ($id === null) {
        if ($method === 'GET') {
            $controller->index();
            exit;
        }
        if ($method === 'POST') {
            $controller->store();
            exit;
        }
        continue;
    }

    if ($action === 'edit' && $method === 'GET') {
        $controller->edit($id);
        exit;
    }
    if ($action === 'status' && $method === 'POST' && method_exists($controller, 'toggleStatus')) {
        $controller->toggleStatus($id);
        exit;
    }
    if ($action === 'autosave' && $method === 'POST' && method_exists($controller, 'autosave')) {
        $controller->autosave($id);
        exit;
    }
    if ($action === null && $method === 'POST') {
        if (($_POST['_method'] ?? '') === 'DELETE') {
            $controller->destroy($id);
        } else {
            $controller->update($id);
        }
        exit;
    }
}

/* Non-resource routes. */

if ($method === 'GET' && preg_match('#^' . preg_quote($base, '#') . '/contacts/(\d+)$#', $uri, $m)) {
    (new ContactController())->show((int) $m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^' . preg_quote($base, '#') . '/contacts/(\d+)/status$#', $uri, $m)) {
    (new ContactController())->updateStatus((int) $m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^' . preg_quote($base, '#') . '/contacts/(\d+)$#', $uri, $m)) {
    if (($_POST['_method'] ?? '') === 'DELETE') {
        (new ContactController())->destroy((int) $m[1]);
    }
    exit;
}

if ($method === 'POST' && preg_match('#^' . preg_quote($base, '#') . '/media/(\d+)$#', $uri, $m)) {
    $controller = new MediaController();
    if (($_POST['_method'] ?? '') === 'DELETE') {
        $controller->destroy((int) $m[1]);
    } else {
        $controller->update((int) $m[1]);
    }
    exit;
}

$routes = [
    'GET' => [
        $base . '/login' => fn() => (new AuthController())->showLogin(),
        $base . '/logout' => fn() => (new AuthController())->logout(),
        $base => fn() => (new DashboardController())->index(),
        $base . '/contacts' => fn() => (new ContactController())->index(),
        $base . '/contacts/export' => fn() => (new ContactController())->export(),
        $base . '/analytics' => fn() => (new AnalyticsController())->index(),
        $base . '/analytics/export' => fn() => (new AnalyticsController())->export(),
        $base . '/media' => fn() => (new MediaController())->index(),
        $base . '/settings' => fn() => (new SettingsController())->index(),
        $base . '/activity' => fn() => (new ActivityController())->index(),
        $base . '/logs' => fn() => (new LogController())->index(),
        $base . '/banners' => fn() => (new BannerController())->index(),
        $base . '/comments' => fn() => (new CommentController())->index(),
    ],
    'POST' => [
        $base . '/login' => fn() => (new AuthController())->login(),
        $base . '/media' => fn() => (new MediaController())->store(),
        $base . '/media/bulk' => fn() => (new MediaController())->bulk(),
        $base . '/media/folders' => fn() => (new MediaController())->storeFolder(),
        $base . '/settings' => fn() => (new SettingsController())->update(),
    ],
];

if ($method === 'POST' && preg_match('#^' . preg_quote($base, '#') . '/banners/([a-z0-9_]+)$#', $uri, $m)) {
    (new BannerController())->update($m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^' . preg_quote($base, '#') . '/comments/(\d+)/status$#', $uri, $m)) {
    (new CommentController())->toggleStatus((int) $m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^' . preg_quote($base, '#') . '/comments/(\d+)$#', $uri, $m)) {
    if (($_POST['_method'] ?? '') === 'DELETE') {
        (new CommentController())->destroy((int) $m[1]);
    }
    exit;
}

$handler = $routes[$method][$uri] ?? null;

if ($handler !== null) {
    $handler();
    exit;
}

http_response_code(404);
admin_guest_view('errors.404', ['pageTitle' => 'Not Found']);

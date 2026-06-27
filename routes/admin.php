<?php

declare(strict_types=1);

use App\Controllers\Admin\AnalyticsController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\ContactController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\NewsController;
use App\Controllers\Admin\PageController;
use App\Controllers\Admin\ProjectController;
use App\Controllers\Admin\SectorController;
use App\Services\Auth\AuthService;

(new AuthService())->startSession();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';

if ($method === 'GET' && preg_match('#^/admin/pages/(\d+)/edit$#', $uri, $m)) {
    (new PageController())->edit((int) $m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^/admin/pages/(\d+)$#', $uri, $m)) {
    $controller = new PageController();
    if (($_POST['_method'] ?? '') === 'DELETE') {
        $controller->destroy((int) $m[1]);
    } else {
        $controller->update((int) $m[1]);
    }
    exit;
}

if ($method === 'GET' && preg_match('#^/admin/news/(\d+)/edit$#', $uri, $m)) {
    (new NewsController())->edit((int) $m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^/admin/news/(\d+)$#', $uri, $m)) {
    $controller = new NewsController();
    if (($_POST['_method'] ?? '') === 'DELETE') {
        $controller->destroy((int) $m[1]);
    } else {
        $controller->update((int) $m[1]);
    }
    exit;
}

if ($method === 'GET' && preg_match('#^/admin/projects/(\d+)/edit$#', $uri, $m)) {
    (new ProjectController())->edit((int) $m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^/admin/projects/(\d+)$#', $uri, $m)) {
    $controller = new ProjectController();
    if (($_POST['_method'] ?? '') === 'DELETE') {
        $controller->destroy((int) $m[1]);
    } else {
        $controller->update((int) $m[1]);
    }
    exit;
}

if ($method === 'GET' && preg_match('#^/admin/sectors/(\d+)/edit$#', $uri, $m)) {
    (new SectorController())->edit((int) $m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^/admin/sectors/(\d+)$#', $uri, $m)) {
    $controller = new SectorController();
    if (($_POST['_method'] ?? '') === 'DELETE') {
        $controller->destroy((int) $m[1]);
    } else {
        $controller->update((int) $m[1]);
    }
    exit;
}

if ($method === 'GET' && preg_match('#^/admin/contacts/(\d+)$#', $uri, $m)) {
    (new ContactController())->show((int) $m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^/admin/contacts/(\d+)/status$#', $uri, $m)) {
    (new ContactController())->updateStatus((int) $m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^/admin/contacts/(\d+)$#', $uri, $m)) {
    if (($_POST['_method'] ?? '') === 'DELETE') {
        (new ContactController())->destroy((int) $m[1]);
    }
    exit;
}

$routes = [
    'GET' => [
        '/admin/login' => fn() => (new AuthController())->showLogin(),
        '/admin/logout' => fn() => (new AuthController())->logout(),
        '/admin' => fn() => (new DashboardController())->index(),
        '/admin/pages' => fn() => (new PageController())->index(),
        '/admin/pages/create' => fn() => (new PageController())->create(),
        '/admin/news' => fn() => (new NewsController())->index(),
        '/admin/news/create' => fn() => (new NewsController())->create(),
        '/admin/projects' => fn() => (new ProjectController())->index(),
        '/admin/projects/create' => fn() => (new ProjectController())->create(),
        '/admin/sectors' => fn() => (new SectorController())->index(),
        '/admin/sectors/create' => fn() => (new SectorController())->create(),
        '/admin/contacts' => fn() => (new ContactController())->index(),
        '/admin/analytics' => fn() => (new AnalyticsController())->index(),
    ],
    'POST' => [
        '/admin/login' => fn() => (new AuthController())->login(),
        '/admin/pages' => fn() => (new PageController())->store(),
        '/admin/news' => fn() => (new NewsController())->store(),
        '/admin/projects' => fn() => (new ProjectController())->store(),
        '/admin/sectors' => fn() => (new SectorController())->store(),
    ],
];

$handler = $routes[$method][$uri] ?? null;

if ($handler !== null) {
    $handler();
    exit;
}

http_response_code(404);
admin_guest_view('errors.404', ['pageTitle' => 'Not Found']);

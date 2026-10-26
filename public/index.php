<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header('X-Permitted-Cross-Domain-Policies: none');

use App\Controllers\QrLandingController;
use App\Controllers\SeoController;
use App\Middleware\CloudflareOriginMiddleware;
use App\Middleware\RedirectMiddleware;
use App\Middleware\UnderConstructionMiddleware;

$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/') ?: '/';

// Locale-free QR landings: never serve under /en|/tr|/ar.
if (preg_match('#^/(en|tr|ar)/(qr|catalogues)$#', $requestUri, $m) === 1) {
    header('Location: /' . $m[2], true, 301);
    exit;
}

(new CloudflareOriginMiddleware())->handle();

if ($requestUri === '/sitemap.xml') {
    if (config('under_construction', false)) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=UTF-8');
        echo "Not Found\n";
        exit;
    }
    (new SeoController())->sitemap();
    exit;
}

if (str_starts_with($requestUri, '/api/')) {
    require dirname(__DIR__) . '/routes/api.php';
    exit;
}

if ($requestUri === '/admin' || str_starts_with($requestUri, '/admin/')) {
    require ADMIN_MODULE_PATH . '/routes.php';
    exit;
}

// Locale-free QR landings (before locale redirect / under-construction gate allows them).
if ($requestUri === '/qr') {
    (new QrLandingController())->qr();
    exit;
}

if ($requestUri === '/catalogues') {
    (new QrLandingController())->catalogues();
    exit;
}

(new UnderConstructionMiddleware())->handle($requestUri);

(new RedirectMiddleware())->handle($requestUri);

// Public pages may need session for CSRF on the contact form.
(new \Admin\Services\Auth\AuthService())->startSession();

if ($requestUri === '/') {
    $defaultLocale = config('default_locale', 'tr');
    header('Location: /' . $defaultLocale, true, 302);
    exit;
}

$segments = array_values(array_filter(explode('/', trim($requestUri, '/'))));
$locale = $segments[0] ?? config('default_locale', 'tr');

set_locale($locale);

$path = implode('/', $segments);
$GLOBALS['current_path'] = $path;

require dirname(__DIR__) . '/routes/web.php';

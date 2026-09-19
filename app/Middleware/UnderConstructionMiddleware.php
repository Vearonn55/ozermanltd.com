<?php

declare(strict_types=1);

namespace App\Middleware;

/**
 * When SITE_UNDER_CONSTRUCTION is enabled, block the public site with a 503
 * except for QR landings, admin, and static asset paths.
 */
class UnderConstructionMiddleware
{
    /** @var list<string> */
    private const ALLOW_EXACT = [
        '/qr',
        '/catalogues',
        '/sitemap.xml',
    ];

    /** @var list<string> */
    private const ALLOW_PREFIXES = [
        '/admin',
        '/assets/',
        '/uploads/',
        '/api/',
    ];

    public function handle(string $requestUri): void
    {
        if (!$this->enabled()) {
            return;
        }

        if ($this->isAllowed($requestUri)) {
            return;
        }

        http_response_code(503);
        header('Retry-After: 86400');
        header('Content-Type: text/html; charset=UTF-8');

        $viewFile = APP_PATH . '/Views/pages/under-construction.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo '<!DOCTYPE html><html><head><title>Under Construction</title></head><body><h1>Under Construction</h1></body></html>';
        }
        exit;
    }

    private function enabled(): bool
    {
        return (bool) config('under_construction', false);
    }

    private function isAllowed(string $requestUri): bool
    {
        if (in_array($requestUri, self::ALLOW_EXACT, true)) {
            return true;
        }

        foreach (self::ALLOW_PREFIXES as $prefix) {
            if (str_starts_with($requestUri, $prefix)) {
                return true;
            }
        }

        return false;
    }
}

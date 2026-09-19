<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Locale-free Lajivert QR landing pages hosted on ozermanltd.com.
 */
class QrLandingController
{
    public function qr(): void
    {
        $this->renderStandalone('pages.qr');
    }

    public function catalogues(): void
    {
        $this->renderStandalone('pages.catalogues');
    }

    private function renderStandalone(string $view): void
    {
        $viewFile = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'View not found: ' . htmlspecialchars($view);
            return;
        }

        require $viewFile;
    }
}

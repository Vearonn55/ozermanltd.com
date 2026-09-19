<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Locale-free Lajivert QR landings.
 * Prefers published CMS pages (Admin → Pages) with slug/path qr|catalogues;
 * falls back to static templates when the DB page is missing.
 */
class QrLandingController
{
    public function qr(): void
    {
        $this->serve('qr', 'pages.qr');
    }

    public function catalogues(): void
    {
        $this->serve('catalogues', 'pages.catalogues');
    }

    private function serve(string $path, string $fallbackView): void
    {
        $page = content()->cmsPageByPath($path, 'en');
        if ($page === null) {
            // Retry with force connection path already inside ContentRepository
            $this->renderStandalone($fallbackView);
            return;
        }

        $hasEmbed = trim((string) ($page['html_embed'] ?? '')) !== ''
            || trim((string) ($page['content'] ?? '')) !== ''
            || trim((string) ($page['php_embed'] ?? '')) !== '';

        if (!$hasEmbed) {
            $this->renderStandalone($fallbackView);
            return;
        }

        $page['php_output'] = $this->runEmbeddedPhp((string) ($page['php_embed'] ?? ''));
        $seo = (object) [
            'title' => $page['meta_title'] ?: ($page['title'] ?? 'LAJIVERT'),
            'description' => $page['meta_description'] ?: ($page['excerpt'] ?? ''),
        ];

        // Always blank document for QR landings (no site chrome).
        $viewFile = APP_PATH . '/Views/pages/custom-blank.php';
        require $viewFile;
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

    private function runEmbeddedPhp(string $code): string
    {
        $code = trim($code);
        if ($code === '' || !admin_config('allow_page_php', false)) {
            return '';
        }

        $code = preg_replace('/^\s*<\?(php)?/i', '', $code) ?? $code;
        $code = preg_replace('/\?>\s*$/', '', $code) ?? $code;

        ob_start();
        try {
            eval($code);
        } catch (\Throwable) {
            echo '<!-- PHP embed error -->';
        }

        return (string) ob_get_clean();
    }
}

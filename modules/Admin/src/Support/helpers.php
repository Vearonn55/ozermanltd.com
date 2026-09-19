<?php

declare(strict_types=1);

/**
 * Admin module view and URL helpers.
 * Requires host helpers: e(), redirect(), config(), and session helpers for CSRF/flash
 * (or the thin wrappers below if host already defines csrf/flash).
 */

if (!defined('ADMIN_MODULE_PATH')) {
    define('ADMIN_MODULE_PATH', dirname(__DIR__, 2));
}

if (!function_exists('admin_config')) {
    function admin_config(string $key, mixed $default = null): mixed
    {
        return config('admin.' . $key, $default);
    }
}

if (!function_exists('admin_view')) {
    function admin_view(string $name, array $data = []): void
    {
        extract($data);
        $viewFile = ADMIN_MODULE_PATH . '/views/' . str_replace('.', '/', $name) . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'Admin view not found: ' . e($name);
            return;
        }

        require ADMIN_MODULE_PATH . '/views/layouts/main.php';
    }
}

if (!function_exists('admin_guest_view')) {
    function admin_guest_view(string $name, array $data = []): void
    {
        extract($data);
        $viewFile = ADMIN_MODULE_PATH . '/views/' . str_replace('.', '/', $name) . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'Admin view not found: ' . e($name);
            return;
        }

        require ADMIN_MODULE_PATH . '/views/layouts/guest.php';
    }
}

if (!function_exists('admin_partial')) {
    function admin_partial(string $name, array $data = []): void
    {
        extract($data);
        require ADMIN_MODULE_PATH . '/views/partials/' . $name . '.php';
    }
}

if (!function_exists('admin_url')) {
    function admin_url(string $path = ''): string
    {
        $base = rtrim((string) admin_config('mount_path', '/admin'), '/');
        return $base . ($path !== '' ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('admin_active')) {
    function admin_active(string $path): bool
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
        $target = admin_url($path);
        $base = rtrim((string) admin_config('mount_path', '/admin'), '/');

        if ($path === '') {
            return $uri === $base;
        }

        return str_starts_with($uri, $target);
    }
}

if (!function_exists('admin_can')) {
    /** @param array<string, mixed>|null $user */
    function admin_can(?array $user, string $minRole): bool
    {
        if ($user === null) {
            return false;
        }

        $roles = admin_config('roles', [
            'editor' => 1,
            'content_manager' => 2,
            'super_admin' => 3,
        ]);

        $userLevel = (int) ($roles[$user['role'] ?? ''] ?? 0);
        $required = (int) ($roles[$minRole] ?? PHP_INT_MAX);

        return $userLevel >= $required;
    }
}

if (!function_exists('admin_preview_url')) {
    /** Public-site URL for a relative path, e.g. admin_preview_url('news/my-slug'). */
    function admin_preview_url(string $path = ''): string
    {
        $base = rtrim((string) admin_config('view_site_url', '/'), '/');
        return ($base !== '' ? $base : '') . ($path !== '' ? '/' . ltrim($path, '/') : '/');
    }
}

if (!function_exists('admin_page_public_url')) {
    /**
     * Public URL for a CMS page. Locale-free paths (qr, catalogues) omit /en.
     *
     * @param array<string, mixed>|null $item
     */
    function admin_page_public_url(?array $item, ?string $pathOverride = null): string
    {
        $path = $pathOverride;
        if ($path === null && $item !== null) {
            $path = (string) (($item['custom_path'] ?? '') !== ''
                ? $item['custom_path']
                : ($item['slug'] ?? ''));
        }
        $path = trim((string) $path, '/');
        if (($item['slug'] ?? '') === 'home' || $path === 'home') {
            $path = '';
        }

        $localeFree = admin_config('locale_free_paths', ['qr', 'catalogues']);
        if (!is_array($localeFree)) {
            $localeFree = ['qr', 'catalogues'];
        }

        if ($path !== '' && in_array($path, $localeFree, true)) {
            return '/' . $path;
        }

        return admin_preview_url($path);
    }
}

if (!function_exists('admin_is_locale_free_path')) {
    function admin_is_locale_free_path(string $path): bool
    {
        $path = trim($path, '/');
        $localeFree = admin_config('locale_free_paths', ['qr', 'catalogues']);
        if (!is_array($localeFree)) {
            $localeFree = ['qr', 'catalogues'];
        }

        return $path !== '' && in_array($path, $localeFree, true);
    }
}

if (!function_exists('admin_media_url')) {
    function admin_media_url(string $filePath): string
    {
        if (str_starts_with($filePath, 'http://') || str_starts_with($filePath, 'https://')) {
            return $filePath;
        }

        $uploadUrl = rtrim((string) admin_config('upload_url', '/uploads'), '/');
        if (str_starts_with($filePath, '/')) {
            return $filePath;
        }

        return $uploadUrl . '/' . ltrim($filePath, '/');
    }
}

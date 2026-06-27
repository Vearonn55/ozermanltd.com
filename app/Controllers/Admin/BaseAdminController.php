<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Middleware\AuthMiddleware;
use App\Services\Auth\ActivityLogger;
use App\Services\Auth\AuthService;

abstract class BaseAdminController
{
    protected ?array $user = null;

    protected function requireAuth(): array
    {
        if ($this->user === null) {
            $this->user = (new AuthMiddleware())->handle();
        }

        return $this->user;
    }

    protected function render(string $view, array $data = []): void
    {
        $data['user'] = $this->user ?? (new AuthService())->user();
        $data['pageTitle'] = $data['pageTitle'] ?? 'Admin';
        admin_view($view, $data);
    }

    protected function verifyCsrf(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!verify_csrf($token)) {
            flash('error', 'Invalid security token. Please try again.');
            redirect($_SERVER['HTTP_REFERER'] ?? '/admin');
        }
    }

    protected function logActivity(string $action, ?string $entityType = null, ?int $entityId = null): void
    {
        $user = $this->requireAuth();
        (new ActivityLogger())->log((int) $user['id'], $action, $entityType, $entityId);
    }

    /** @return array<string, array<string, string>> */
    protected function parseTranslations(array $input, array $fields): array
    {
        $translations = [];
        $locales = array_keys(config('locales', []));

        foreach ($locales as $code) {
            foreach ($fields as $field) {
                $key = "translations_{$code}_{$field}";
                if (isset($input[$key])) {
                    $translations[$code][$field] = trim((string) $input[$key]);
                }
            }
        }

        return $translations;
    }
}

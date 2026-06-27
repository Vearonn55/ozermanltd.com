<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Infrastructure\Database;

class RedirectMiddleware
{
    public function handle(string $requestPath): bool
    {
        $redirect = $this->findRedirect($requestPath);
        if ($redirect === null) {
            return false;
        }

        $status = ($redirect['type'] ?? '301') === '302' ? 302 : 301;
        header('Location: ' . $redirect['to'], true, $status);
        exit;
    }

    private function findRedirect(string $requestPath): ?array
    {
        $dbRedirect = $this->findInDatabase($requestPath);
        if ($dbRedirect !== null) {
            return $dbRedirect;
        }

        return $this->findInFile($requestPath);
    }

    private function findInDatabase(string $requestPath): ?array
    {
        $pdo = Database::connection();
        if ($pdo === null) {
            return null;
        }

        $stmt = $pdo->prepare(
            'SELECT to_path, type FROM redirects WHERE from_path = :from_path AND is_active = 1 LIMIT 1'
        );
        $stmt->execute(['from_path' => $requestPath]);

        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }

        $pdo->prepare('UPDATE redirects SET hit_count = hit_count + 1 WHERE from_path = :from_path')
            ->execute(['from_path' => $requestPath]);

        return [
            'to' => $row['to_path'],
            'type' => $row['type'],
        ];
    }

    private function findInFile(string $requestPath): ?array
    {
        $path = BASE_PATH . '/storage/seo/redirects.json';
        if (!file_exists($path)) {
            return null;
        }

        $redirects = json_decode((string) file_get_contents($path), true);
        if (!is_array($redirects)) {
            return null;
        }

        foreach ($redirects as $redirect) {
            if (($redirect['from'] ?? '') === $requestPath) {
                return [
                    'to' => $redirect['to'] ?? '/',
                    'type' => $redirect['type'] ?? '301',
                ];
            }
        }

        return null;
    }
}

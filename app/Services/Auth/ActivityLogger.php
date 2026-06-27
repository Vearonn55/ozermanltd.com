<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Infrastructure\Database;

class ActivityLogger
{
    public function log(int $userId, string $action, ?string $entityType = null, ?int $entityId = null, ?array $payload = null): void
    {
        $pdo = Database::connection(force: true);
        if ($pdo === null) {
            return;
        }

        $stmt = $pdo->prepare(
            'INSERT INTO user_activity_log (user_id, action, entity_type, entity_id, payload, ip_address)
             VALUES (:user_id, :action, :entity_type, :entity_id, :payload, :ip_address)'
        );
        $stmt->execute([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'payload' => $payload !== null ? json_encode($payload, JSON_UNESCAPED_UNICODE) : null,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }
}

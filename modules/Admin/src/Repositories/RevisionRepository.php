<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class RevisionRepository extends BaseAdminRepository
{
    private const MAX_REVISIONS = 20;
    private const MAX_AUTOSAVES = 3;

    public function record(string $entityType, int $entityId, ?int $userId, array $payload, bool $isAutosave = false): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO content_revisions (entity_type, entity_id, user_id, is_autosave, payload)
             VALUES (:entity_type, :entity_id, :user_id, :is_autosave, :payload)'
        );
        $stmt->execute([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'user_id' => $userId,
            'is_autosave' => $isAutosave ? 1 : 0,
            'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE),
        ]);

        $id = (int) $this->pdo->lastInsertId();
        $this->prune($entityType, $entityId);

        return $id;
    }

    /** @return array<int, array<string, mixed>> */
    public function listFor(string $entityType, int $entityId, int $limit = self::MAX_REVISIONS): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT cr.id, cr.is_autosave, cr.created_at, u.name AS user_name
             FROM content_revisions cr
             LEFT JOIN users u ON u.id = cr.user_id
             WHERE cr.entity_type = :entity_type AND cr.entity_id = :entity_id
             ORDER BY cr.id DESC
             LIMIT ' . $limit
        );
        $stmt->execute(['entity_type' => $entityType, 'entity_id' => $entityId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id, string $entityType, int $entityId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM content_revisions
             WHERE id = :id AND entity_type = :entity_type AND entity_id = :entity_id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id, 'entity_type' => $entityType, 'entity_id' => $entityId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $row['payload'] = json_decode((string) $row['payload'], true) ?: [];

        return $row;
    }

    private function prune(string $entityType, int $entityId): void
    {
        foreach ([[1, self::MAX_AUTOSAVES], [0, self::MAX_REVISIONS]] as [$isAutosave, $keep]) {
            $stmt = $this->pdo->prepare(
                'SELECT id FROM content_revisions
                 WHERE entity_type = :entity_type AND entity_id = :entity_id AND is_autosave = :is_autosave
                 ORDER BY id DESC'
            );
            $stmt->execute(['entity_type' => $entityType, 'entity_id' => $entityId, 'is_autosave' => $isAutosave]);
            $ids = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));

            $stale = array_slice($ids, $keep);
            if ($stale !== []) {
                $in = implode(',', $stale);
                $this->pdo->exec("DELETE FROM content_revisions WHERE id IN ({$in})");
            }
        }
    }
}

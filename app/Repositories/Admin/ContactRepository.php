<?php

declare(strict_types=1);

namespace App\Repositories\Admin;

use PDO;

class ContactRepository extends BaseAdminRepository
{
    public function all(?string $status = null): array
    {
        $sql = 'SELECT * FROM contact_messages';
        $params = [];

        if ($status !== null && $status !== '') {
            $sql .= ' WHERE status = :status';
            $params['status'] = $status;
        }

        $sql .= ' ORDER BY created_at DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM contact_messages WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        $message = $stmt->fetch(PDO::FETCH_ASSOC);

        return $message ?: null;
    }

    public function updateStatus(int $id, string $status): void
    {
        $readAt = in_array($status, ['read', 'replied', 'archived'], true) ? date('Y-m-d H:i:s') : null;
        $repliedAt = $status === 'replied' ? date('Y-m-d H:i:s') : null;

        $stmt = $this->pdo->prepare(
            'UPDATE contact_messages SET status = :status, read_at = COALESCE(read_at, :read_at), replied_at = COALESCE(replied_at, :replied_at)
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'status' => $status,
            'read_at' => $readAt,
            'replied_at' => $repliedAt,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM contact_messages WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function count(?string $status = null): int
    {
        if ($status === null) {
            return (int) $this->pdo->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
        }

        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM contact_messages WHERE status = :status');
        $stmt->execute(['status' => $status]);

        return (int) $stmt->fetchColumn();
    }

    public function countNew(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status IN ('new', 'received')");
        return (int) $stmt->fetchColumn();
    }
}

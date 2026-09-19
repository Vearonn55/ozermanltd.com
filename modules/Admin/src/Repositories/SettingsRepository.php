<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class SettingsRepository extends BaseAdminRepository
{
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM settings ORDER BY `group`, `key`'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function grouped(): array
    {
        $grouped = [];
        foreach ($this->all() as $row) {
            $grouped[$row['group']][] = $row;
        }
        return $grouped;
    }

    public function get(string $group, string $key, ?string $default = null): ?string
    {
        $stmt = $this->pdo->prepare(
            'SELECT value FROM settings WHERE `group` = :group AND `key` = :key LIMIT 1'
        );
        $stmt->execute(['group' => $group, 'key' => $key]);
        $value = $stmt->fetchColumn();
        return $value === false ? $default : (string) $value;
    }

    public function set(string $group, string $key, ?string $value, ?int $updatedBy = null): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO settings (`group`, `key`, value, type, label, updated_by)
             VALUES (:group, :key, :value, :type, :label, :updated_by)
             ON DUPLICATE KEY UPDATE value = VALUES(value), updated_by = VALUES(updated_by)'
        );
        $stmt->execute([
            'group' => $group,
            'key' => $key,
            'value' => $value,
            'type' => 'text',
            'label' => null,
            'updated_by' => $updatedBy,
        ]);
    }

    public function updateMany(array $items, ?int $updatedBy = null): void
    {
        foreach ($items as $item) {
            $stmt = $this->pdo->prepare(
                'UPDATE settings SET value = :value, updated_by = :updated_by WHERE id = :id'
            );
            $stmt->execute([
                'id' => (int) $item['id'],
                'value' => $item['value'],
                'updated_by' => $updatedBy,
            ]);
        }
    }
}

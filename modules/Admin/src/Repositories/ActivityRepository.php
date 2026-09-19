<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class ActivityRepository extends BaseAdminRepository
{
    public function search(?string $q = null, ?string $action = null, int $limit = 100): array
    {
        $sql = 'SELECT a.*, u.name AS user_name, u.email AS user_email
                FROM user_activity_log a
                LEFT JOIN users u ON u.id = a.user_id
                WHERE 1=1';
        $params = [];

        if ($q !== null && $q !== '') {
            $sql .= ' AND (u.name LIKE :q OR u.email LIKE :q OR a.entity_type LIKE :q OR a.ip_address LIKE :q)';
            $params['q'] = '%' . $q . '%';
        }

        if ($action !== null && $action !== '') {
            $sql .= ' AND a.action = :action';
            $params['action'] = $action;
        }

        $sql .= ' ORDER BY a.id DESC LIMIT ' . (int) $limit;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function distinctActions(): array
    {
        $stmt = $this->pdo->query('SELECT DISTINCT action FROM user_activity_log ORDER BY action');
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'action');
    }
}

<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class CommentRepository extends BaseAdminRepository
{
    public function paginate(string $status = '', int $page = 1, int $perPage = 20): array
    {
        $where = [];
        $params = [];
        if ($status !== '') {
            $where[] = 'c.status = :status';
            $params['status'] = $status;
        }
        $whereSql = $where !== [] ? 'WHERE ' . implode(' AND ', $where) : '';

        $base = "FROM news_comments c
             LEFT JOIN news_translations nt ON nt.news_id = c.news_id AND nt.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             ) {$whereSql}";

        $countStmt = $this->pdo->prepare("SELECT COUNT(*) {$base}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $pages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $pages);
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare(
            "SELECT c.*, nt.title AS article_title, nt.slug AS article_slug {$base}
             ORDER BY FIELD(c.status, 'pending', 'approved', 'spam', 'rejected'), c.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);

        return [
            'items' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'pages' => $pages,
            'page' => $page,
        ];
    }

    public function setStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare('UPDATE news_comments SET status = :status WHERE id = :id');
        $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM news_comments WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function countPending(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM news_comments WHERE status = 'pending'")->fetchColumn();
    }
}

<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class ProjectRepository extends BaseAdminRepository
{
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT p.*, pt.title, pt.slug, pct.name AS category_name
             FROM projects p
             LEFT JOIN project_translations pt ON pt.project_id = p.id AND pt.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             LEFT JOIN project_category_translations pct ON pct.category_id = p.category_id AND pct.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             ORDER BY p.is_featured DESC, p.id DESC'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array{items: array, total: int, pages: int, page: int}
     */
    public function paginate(string $q = '', string $status = '', int $page = 1, int $perPage = 15): array
    {
        $where = [];
        $params = [];
        if ($q !== '') {
            $where[] = '(pt.title LIKE :q OR p.location LIKE :q2)';
            $params['q'] = '%' . $q . '%';
            $params['q2'] = '%' . $q . '%';
        }
        if ($status === 'active') {
            $where[] = 'p.is_active = 1';
        } elseif ($status === 'inactive') {
            $where[] = 'p.is_active = 0';
        } elseif ($status !== '') {
            $where[] = 'p.status = :status';
            $params['status'] = $status;
        }
        $whereSql = $where !== [] ? 'WHERE ' . implode(' AND ', $where) : '';

        $base = "FROM projects p
             LEFT JOIN project_translations pt ON pt.project_id = p.id AND pt.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             LEFT JOIN project_category_translations pct ON pct.category_id = p.category_id AND pct.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             ) {$whereSql}";

        $countStmt = $this->pdo->prepare("SELECT COUNT(*) {$base}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $pages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $pages);
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare(
            "SELECT p.*, pt.title, pt.slug, pct.name AS category_name {$base}
             ORDER BY p.is_featured DESC, p.id DESC
             LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);

        return ['items' => $stmt->fetchAll(PDO::FETCH_ASSOC), 'total' => $total, 'pages' => $pages, 'page' => $page];
    }

    public function setActive(int $id, bool $active): void
    {
        $stmt = $this->pdo->prepare('UPDATE projects SET is_active = :active WHERE id = :id');
        $stmt->execute(['id' => $id, 'active' => $active ? 1 : 0]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM projects WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT pt.*, l.code
             FROM project_translations pt
             INNER JOIN languages l ON l.id = pt.language_id
             WHERE pt.project_id = :project_id'
        );
        $stmt->execute(['project_id' => $id]);
        $project['translations'] = $this->indexTranslationsByCode($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $project;
    }

    public function categories(): array
    {
        $stmt = $this->pdo->query(
            'SELECT pc.id, pct.name
             FROM project_categories pc
             LEFT JOIN project_category_translations pct ON pct.category_id = pc.id AND pct.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             ORDER BY pc.sort_order'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO projects (category_id, status, location, delivery_date, start_price, currency, is_featured, is_active, featured_image_id)
             VALUES (:category_id, :status, :location, :delivery_date, :start_price, :currency, :is_featured, :is_active, :featured_image_id)'
        );
        $stmt->execute([
            'category_id' => $data['category_id'] ?: null,
            'status' => $data['status'] ?? 'planning',
            'location' => $data['location'] ?? null,
            'delivery_date' => $data['delivery_date'] ?? null,
            'start_price' => $data['start_price'] !== '' && $data['start_price'] !== null ? $data['start_price'] : null,
            'currency' => $data['currency'] ?? 'GBP',
            'is_featured' => (int) ($data['is_featured'] ?? 0),
            'is_active' => (int) ($data['is_active'] ?? 1),
            'featured_image_id' => $data['featured_image_id'] ?? null,
        ]);

        $projectId = (int) $this->pdo->lastInsertId();
        $this->saveTranslations($projectId, $data['translations'] ?? []);

        return $projectId;
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE projects SET category_id = :category_id, status = :status, location = :location,
             delivery_date = :delivery_date, start_price = :start_price, currency = :currency,
             is_featured = :is_featured, is_active = :is_active, featured_image_id = :featured_image_id
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'category_id' => $data['category_id'] ?: null,
            'status' => $data['status'] ?? 'planning',
            'location' => $data['location'] ?? null,
            'delivery_date' => $data['delivery_date'] ?? null,
            'start_price' => $data['start_price'] !== '' && $data['start_price'] !== null ? $data['start_price'] : null,
            'currency' => $data['currency'] ?? 'GBP',
            'is_featured' => (int) ($data['is_featured'] ?? 0),
            'is_active' => (int) ($data['is_active'] ?? 1),
            'featured_image_id' => $data['featured_image_id'] ?? null,
        ]);

        $this->saveTranslations($id, $data['translations'] ?? []);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM projects WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
    }

    private function saveTranslations(int $projectId, array $translations): void
    {
        foreach ($translations as $code => $fields) {
            if (empty($fields['title'])) {
                continue;
            }

            $slug = !empty($fields['slug']) ? $fields['slug'] : $this->slugify($fields['title']);
            $languageId = $this->languageId($code);

            $stmt = $this->pdo->prepare(
                'INSERT INTO project_translations (project_id, language_id, title, slug, description, features, payment_plan, meta_title, meta_description)
                 VALUES (:project_id, :language_id, :title, :slug, :description, :features, :payment_plan, :meta_title, :meta_description)
                 ON DUPLICATE KEY UPDATE
                   title = VALUES(title), slug = VALUES(slug), description = VALUES(description),
                   features = VALUES(features), payment_plan = VALUES(payment_plan),
                   meta_title = VALUES(meta_title), meta_description = VALUES(meta_description)'
            );
            $stmt->execute([
                'project_id' => $projectId,
                'language_id' => $languageId,
                'title' => $fields['title'],
                'slug' => $slug,
                'description' => $fields['description'] ?? null,
                'features' => $fields['features'] ?? null,
                'payment_plan' => $fields['payment_plan'] ?? null,
                'meta_title' => $fields['meta_title'] ?? null,
                'meta_description' => $fields['meta_description'] ?? null,
            ]);
        }
    }
}

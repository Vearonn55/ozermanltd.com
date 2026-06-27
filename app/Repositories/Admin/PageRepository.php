<?php

declare(strict_types=1);

namespace App\Repositories\Admin;

use PDO;

class PageRepository extends BaseAdminRepository
{
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT p.*, pt.title
             FROM pages p
             LEFT JOIN page_translations pt ON pt.page_id = p.id AND pt.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             ORDER BY p.sort_order, p.id'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM pages WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $page = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$page) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT pt.*, l.code
             FROM page_translations pt
             INNER JOIN languages l ON l.id = pt.language_id
             WHERE pt.page_id = :page_id'
        );
        $stmt->execute(['page_id' => $id]);
        $page['translations'] = $this->indexTranslationsByCode($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $page;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO pages (slug, template, status, show_in_nav, sort_order, published_at)
             VALUES (:slug, :template, :status, :show_in_nav, :sort_order, :published_at)'
        );
        $stmt->execute([
            'slug' => $data['slug'],
            'template' => $data['template'] ?? 'default',
            'status' => $data['status'] ?? 'draft',
            'show_in_nav' => (int) ($data['show_in_nav'] ?? 1),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'published_at' => ($data['status'] ?? 'draft') === 'published' ? date('Y-m-d H:i:s') : null,
        ]);

        $pageId = (int) $this->pdo->lastInsertId();
        $this->saveTranslations($pageId, $data['translations'] ?? []);

        return $pageId;
    }

    public function update(int $id, array $data): void
    {
        $publishedAt = null;
        if (($data['status'] ?? 'draft') === 'published') {
            $existing = $this->find($id);
            $publishedAt = $existing['published_at'] ?? date('Y-m-d H:i:s');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE pages SET slug = :slug, template = :template, status = :status,
             show_in_nav = :show_in_nav, sort_order = :sort_order, published_at = :published_at
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'slug' => $data['slug'],
            'template' => $data['template'] ?? 'default',
            'status' => $data['status'] ?? 'draft',
            'show_in_nav' => (int) ($data['show_in_nav'] ?? 1),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'published_at' => $publishedAt,
        ]);

        $this->saveTranslations($id, $data['translations'] ?? []);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM pages WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM pages')->fetchColumn();
    }

    private function saveTranslations(int $pageId, array $translations): void
    {
        foreach ($translations as $code => $fields) {
            if (empty($fields['title'])) {
                continue;
            }

            $languageId = $this->languageId($code);
            $stmt = $this->pdo->prepare(
                'INSERT INTO page_translations (page_id, language_id, title, content, excerpt, meta_title, meta_description)
                 VALUES (:page_id, :language_id, :title, :content, :excerpt, :meta_title, :meta_description)
                 ON DUPLICATE KEY UPDATE
                   title = VALUES(title), content = VALUES(content), excerpt = VALUES(excerpt),
                   meta_title = VALUES(meta_title), meta_description = VALUES(meta_description)'
            );
            $stmt->execute([
                'page_id' => $pageId,
                'language_id' => $languageId,
                'title' => $fields['title'],
                'content' => $fields['content'] ?? null,
                'excerpt' => $fields['excerpt'] ?? null,
                'meta_title' => $fields['meta_title'] ?? null,
                'meta_description' => $fields['meta_description'] ?? null,
            ]);
        }
    }
}

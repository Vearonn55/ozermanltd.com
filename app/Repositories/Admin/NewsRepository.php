<?php

declare(strict_types=1);

namespace App\Repositories\Admin;

use PDO;

class NewsRepository extends BaseAdminRepository
{
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT n.*, nt.title, nt.slug, nct.name AS category_name
             FROM news n
             LEFT JOIN news_translations nt ON nt.news_id = n.id AND nt.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             LEFT JOIN news_category_translations nct ON nct.category_id = n.category_id AND nct.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             ORDER BY n.publish_date DESC, n.id DESC'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM news WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $news = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$news) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT nt.*, l.code
             FROM news_translations nt
             INNER JOIN languages l ON l.id = nt.language_id
             WHERE nt.news_id = :news_id'
        );
        $stmt->execute(['news_id' => $id]);
        $news['translations'] = $this->indexTranslationsByCode($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $news;
    }

    public function categories(): array
    {
        $stmt = $this->pdo->query(
            'SELECT nc.id, nct.name
             FROM news_categories nc
             LEFT JOIN news_category_translations nct ON nct.category_id = nc.id AND nct.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             WHERE nc.is_active = 1
             ORDER BY nc.sort_order'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO news (category_id, status, publish_date, is_featured, is_pinned, author_id)
             VALUES (:category_id, :status, :publish_date, :is_featured, :is_pinned, :author_id)'
        );
        $stmt->execute([
            'category_id' => $data['category_id'] ?: null,
            'status' => $data['status'] ?? 'draft',
            'publish_date' => $data['publish_date'] ?: null,
            'is_featured' => (int) ($data['is_featured'] ?? 0),
            'is_pinned' => (int) ($data['is_pinned'] ?? 0),
            'author_id' => $data['author_id'] ?? null,
        ]);

        $newsId = (int) $this->pdo->lastInsertId();
        $this->saveTranslations($newsId, $data['translations'] ?? []);

        return $newsId;
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE news SET category_id = :category_id, status = :status, publish_date = :publish_date,
             is_featured = :is_featured, is_pinned = :is_pinned
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'category_id' => $data['category_id'] ?: null,
            'status' => $data['status'] ?? 'draft',
            'publish_date' => $data['publish_date'] ?: null,
            'is_featured' => (int) ($data['is_featured'] ?? 0),
            'is_pinned' => (int) ($data['is_pinned'] ?? 0),
        ]);

        $this->saveTranslations($id, $data['translations'] ?? []);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM news WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM news')->fetchColumn();
    }

    public function countPublished(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM news WHERE status = 'published'")->fetchColumn();
    }

    private function saveTranslations(int $newsId, array $translations): void
    {
        foreach ($translations as $code => $fields) {
            if (empty($fields['title'])) {
                continue;
            }

            $slug = !empty($fields['slug']) ? $fields['slug'] : $this->slugify($fields['title']);
            $languageId = $this->languageId($code);

            $stmt = $this->pdo->prepare(
                'INSERT INTO news_translations (news_id, language_id, title, slug, content, excerpt, meta_title, meta_description)
                 VALUES (:news_id, :language_id, :title, :slug, :content, :excerpt, :meta_title, :meta_description)
                 ON DUPLICATE KEY UPDATE
                   title = VALUES(title), slug = VALUES(slug), content = VALUES(content), excerpt = VALUES(excerpt),
                   meta_title = VALUES(meta_title), meta_description = VALUES(meta_description)'
            );
            $stmt->execute([
                'news_id' => $newsId,
                'language_id' => $languageId,
                'title' => $fields['title'],
                'slug' => $slug,
                'content' => $fields['content'] ?? null,
                'excerpt' => $fields['excerpt'] ?? null,
                'meta_title' => $fields['meta_title'] ?? null,
                'meta_description' => $fields['meta_description'] ?? null,
            ]);
        }
    }
}

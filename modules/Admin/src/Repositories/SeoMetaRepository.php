<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class SeoMetaRepository extends BaseAdminRepository
{
    public function all(?string $entityType = null): array
    {
        $sql = 'SELECT s.*, l.code AS language_code, m.file_path AS og_image_path
                FROM seo_meta s
                INNER JOIN languages l ON l.id = s.language_id
                LEFT JOIN media m ON m.id = s.og_image_id';
        $params = [];

        if ($entityType !== null && $entityType !== '') {
            $sql .= ' WHERE s.entity_type = :entity_type';
            $params['entity_type'] = $entityType;
        }

        $sql .= ' ORDER BY s.entity_type, s.entity_id, l.code';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM seo_meta WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO seo_meta (entity_type, entity_id, language_id, meta_title, meta_description, og_title, og_description, og_image_id, canonical_url, robots)
             VALUES (:entity_type, :entity_id, :language_id, :meta_title, :meta_description, :og_title, :og_description, :og_image_id, :canonical_url, :robots)'
        );
        $stmt->execute([
            'entity_type' => $data['entity_type'],
            'entity_id' => (int) $data['entity_id'],
            'language_id' => (int) $data['language_id'],
            'meta_title' => $data['meta_title'] ?: null,
            'meta_description' => $data['meta_description'] ?: null,
            'og_title' => $data['og_title'] ?: null,
            'og_description' => $data['og_description'] ?: null,
            'og_image_id' => $data['og_image_id'] ?: null,
            'canonical_url' => $data['canonical_url'] ?: null,
            'robots' => $data['robots'] ?: 'index, follow',
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE seo_meta SET entity_type = :entity_type, entity_id = :entity_id, language_id = :language_id,
             meta_title = :meta_title, meta_description = :meta_description, og_title = :og_title,
             og_description = :og_description, og_image_id = :og_image_id, canonical_url = :canonical_url,
             robots = :robots WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'entity_type' => $data['entity_type'],
            'entity_id' => (int) $data['entity_id'],
            'language_id' => (int) $data['language_id'],
            'meta_title' => $data['meta_title'] ?: null,
            'meta_description' => $data['meta_description'] ?: null,
            'og_title' => $data['og_title'] ?: null,
            'og_description' => $data['og_description'] ?: null,
            'og_image_id' => $data['og_image_id'] ?: null,
            'canonical_url' => $data['canonical_url'] ?: null,
            'robots' => $data['robots'] ?: 'index, follow',
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM seo_meta WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

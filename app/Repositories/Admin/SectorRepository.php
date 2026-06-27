<?php

declare(strict_types=1);

namespace App\Repositories\Admin;

use PDO;

class SectorRepository extends BaseAdminRepository
{
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT s.*, st.name, st.slug
             FROM sectors s
             LEFT JOIN sector_translations st ON st.sector_id = s.id AND st.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             ORDER BY s.sort_order, s.id'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM sectors WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $sector = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sector) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT st.*, l.code
             FROM sector_translations st
             INNER JOIN languages l ON l.id = st.language_id
             WHERE st.sector_id = :sector_id'
        );
        $stmt->execute(['sector_id' => $id]);
        $sector['translations'] = $this->indexTranslationsByCode($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $sector;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO sectors (icon, color, sort_order, is_active)
             VALUES (:icon, :color, :sort_order, :is_active)'
        );
        $stmt->execute([
            'icon' => $data['icon'] ?? null,
            'color' => $data['color'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => (int) ($data['is_active'] ?? 1),
        ]);

        $sectorId = (int) $this->pdo->lastInsertId();
        $this->saveTranslations($sectorId, $data['translations'] ?? []);

        return $sectorId;
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE sectors SET icon = :icon, color = :color, sort_order = :sort_order, is_active = :is_active
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'icon' => $data['icon'] ?? null,
            'color' => $data['color'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => (int) ($data['is_active'] ?? 1),
        ]);

        $this->saveTranslations($id, $data['translations'] ?? []);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM sectors WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM sectors')->fetchColumn();
    }

    private function saveTranslations(int $sectorId, array $translations): void
    {
        foreach ($translations as $code => $fields) {
            if (empty($fields['name'])) {
                continue;
            }

            $slug = !empty($fields['slug']) ? $fields['slug'] : $this->slugify($fields['name']);
            $languageId = $this->languageId($code);

            $stmt = $this->pdo->prepare(
                'INSERT INTO sector_translations (sector_id, language_id, name, slug, overview, services_text, meta_title, meta_description)
                 VALUES (:sector_id, :language_id, :name, :slug, :overview, :services_text, :meta_title, :meta_description)
                 ON DUPLICATE KEY UPDATE
                   name = VALUES(name), slug = VALUES(slug), overview = VALUES(overview),
                   services_text = VALUES(services_text), meta_title = VALUES(meta_title),
                   meta_description = VALUES(meta_description)'
            );
            $stmt->execute([
                'sector_id' => $sectorId,
                'language_id' => $languageId,
                'name' => $fields['name'],
                'slug' => $slug,
                'overview' => $fields['overview'] ?? null,
                'services_text' => $fields['services_text'] ?? null,
                'meta_title' => $fields['meta_title'] ?? null,
                'meta_description' => $fields['meta_description'] ?? null,
            ]);
        }
    }
}

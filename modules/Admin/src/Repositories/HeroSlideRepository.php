<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

/**
 * Hero slides are stored as one row per language sharing (page_id, sort_order).
 * The admin treats such a group as a single slide with per-language fields.
 * Updates upsert in place so the stable group id (min row id) remains valid.
 */
class HeroSlideRepository extends BaseAdminRepository
{
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT hs.*, l.code AS lang, m.file_path AS image_path
             FROM hero_slides hs
             INNER JOIN languages l ON l.id = hs.language_id
             LEFT JOIN media m ON m.id = hs.media_id
             ORDER BY hs.page_id, hs.sort_order, l.code'
        );

        $groups = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $key = $row['page_id'] . ':' . $row['sort_order'];
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'id' => (int) $row['id'],
                    'page_id' => (int) $row['page_id'],
                    'sort_order' => (int) $row['sort_order'],
                    'media_id' => $row['media_id'],
                    'image_path' => $row['image_path'],
                    'cta_url' => $row['cta_url'],
                    'is_active' => (int) $row['is_active'],
                    'title' => null,
                ];
            }
            $groups[$key]['id'] = min($groups[$key]['id'], (int) $row['id']);
            if ($row['lang'] === 'en' || $groups[$key]['title'] === null) {
                $groups[$key]['title'] = $row['title'];
            }
        }

        return array_values($groups);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM hero_slides WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT hs.*, l.code
             FROM hero_slides hs
             INNER JOIN languages l ON l.id = hs.language_id
             WHERE hs.page_id = :page_id AND hs.sort_order = :sort_order'
        );
        $stmt->execute(['page_id' => $row['page_id'], 'sort_order' => $row['sort_order']]);

        $slide = [
            'id' => (int) $row['id'],
            'page_id' => (int) $row['page_id'],
            'sort_order' => (int) $row['sort_order'],
            'media_id' => $row['media_id'],
            'cta_url' => $row['cta_url'],
            'is_active' => (int) $row['is_active'],
            'translations' => [],
        ];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $langRow) {
            $slide['translations'][$langRow['code']] = [
                'title' => $langRow['title'],
                'subtitle' => $langRow['subtitle'],
                'cta_text' => $langRow['cta_text'],
            ];
        }

        return $slide;
    }

    public function create(array $data): int
    {
        return $this->writeGroup(null, null, $data);
    }

    public function update(int $id, array $data): void
    {
        $existing = $this->find($id);
        if (!$existing) {
            return;
        }

        $this->writeGroup(
            (int) $existing['page_id'],
            (int) $existing['sort_order'],
            $data
        );
    }

    public function delete(int $id): void
    {
        $existing = $this->find($id);
        if ($existing) {
            $this->deleteGroup((int) $existing['page_id'], (int) $existing['sort_order']);
        }
    }

    public function setActive(int $id, bool $active): void
    {
        $existing = $this->find($id);
        if (!$existing) {
            return;
        }
        $stmt = $this->pdo->prepare(
            'UPDATE hero_slides SET is_active = :active WHERE page_id = :page_id AND sort_order = :sort_order'
        );
        $stmt->execute([
            'active' => $active ? 1 : 0,
            'page_id' => $existing['page_id'],
            'sort_order' => $existing['sort_order'],
        ]);
    }

    public function homePageId(): int
    {
        $id = $this->pdo->query("SELECT id FROM pages WHERE slug = 'home' LIMIT 1")->fetchColumn();

        return (int) ($id ?: 1);
    }

    private function deleteGroup(int $pageId, int $sortOrder): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM hero_slides WHERE page_id = :page_id AND sort_order = :sort_order');
        $stmt->execute(['page_id' => $pageId, 'sort_order' => $sortOrder]);
    }

    private function writeGroup(?int $oldPageId, ?int $oldSortOrder, array $data): int
    {
        $pageId = (int) ($data['page_id'] ?? $this->homePageId());
        $newSort = (int) ($data['sort_order'] ?? 0);
        $isUpdate = $oldPageId !== null && $oldSortOrder !== null;

        // If sort_order changed, clear the old group key first (avoid merge collisions).
        if ($isUpdate && ($oldPageId !== $pageId || $oldSortOrder !== $newSort)) {
            $this->deleteGroup($oldPageId, $oldSortOrder);
        }

        $upsert = $this->pdo->prepare(
            'INSERT INTO hero_slides (page_id, language_id, media_id, title, subtitle, cta_text, cta_url, sort_order, is_active)
             VALUES (:page_id, :language_id, :media_id, :title, :subtitle, :cta_text, :cta_url, :sort_order, :is_active)
             ON DUPLICATE KEY UPDATE
               media_id = VALUES(media_id),
               title = VALUES(title),
               subtitle = VALUES(subtitle),
               cta_text = VALUES(cta_text),
               cta_url = VALUES(cta_url),
               is_active = VALUES(is_active)'
        );

        // Unique key may not exist — fall back to select+update/insert per language.
        $select = $this->pdo->prepare(
            'SELECT id FROM hero_slides WHERE page_id = :page_id AND language_id = :language_id AND sort_order = :sort_order LIMIT 1'
        );
        $update = $this->pdo->prepare(
            'UPDATE hero_slides SET media_id = :media_id, title = :title, subtitle = :subtitle,
             cta_text = :cta_text, cta_url = :cta_url, is_active = :is_active
             WHERE id = :id'
        );
        $insert = $this->pdo->prepare(
            'INSERT INTO hero_slides (page_id, language_id, media_id, title, subtitle, cta_text, cta_url, sort_order, is_active)
             VALUES (:page_id, :language_id, :media_id, :title, :subtitle, :cta_text, :cta_url, :sort_order, :is_active)'
        );

        $firstId = 0;
        $keptLanguageIds = [];

        foreach ($this->languages() as $lang) {
            $trans = $data['translations'][$lang['code']] ?? [];
            if (empty($trans['title'])) {
                continue;
            }

            $payload = [
                'page_id' => $pageId,
                'language_id' => (int) $lang['id'],
                'media_id' => $data['media_id'] ?: null,
                'title' => $trans['title'],
                'subtitle' => ($trans['subtitle'] ?? '') !== '' ? $trans['subtitle'] : null,
                'cta_text' => ($trans['cta_text'] ?? '') !== '' ? $trans['cta_text'] : null,
                'cta_url' => ($data['cta_url'] ?? '') !== '' ? $data['cta_url'] : null,
                'sort_order' => $newSort,
                'is_active' => (int) ($data['is_active'] ?? 1),
            ];

            $select->execute([
                'page_id' => $pageId,
                'language_id' => (int) $lang['id'],
                'sort_order' => $newSort,
            ]);
            $existingId = $select->fetchColumn();

            if ($existingId) {
                $update->execute([
                    'id' => (int) $existingId,
                    'media_id' => $payload['media_id'],
                    'title' => $payload['title'],
                    'subtitle' => $payload['subtitle'],
                    'cta_text' => $payload['cta_text'],
                    'cta_url' => $payload['cta_url'],
                    'is_active' => $payload['is_active'],
                ]);
                $rowId = (int) $existingId;
            } else {
                $insert->execute($payload);
                $rowId = (int) $this->pdo->lastInsertId();
            }

            $keptLanguageIds[] = (int) $lang['id'];
            if ($firstId === 0 || $rowId < $firstId) {
                $firstId = $rowId;
            }
        }

        // Remove language rows cleared in the form.
        if ($isUpdate || $keptLanguageIds !== []) {
            if ($keptLanguageIds === []) {
                $this->deleteGroup($pageId, $newSort);
            } else {
                $placeholders = implode(',', array_fill(0, count($keptLanguageIds), '?'));
                $stmt = $this->pdo->prepare(
                    "DELETE FROM hero_slides WHERE page_id = ? AND sort_order = ? AND language_id NOT IN ($placeholders)"
                );
                $stmt->execute(array_merge([$pageId, $newSort], $keptLanguageIds));
            }
        }

        // Silence unused upsert prepare (kept for future unique-key path).
        unset($upsert);

        return $firstId;
    }
}

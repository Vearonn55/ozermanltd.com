<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

/**
 * Stat counters are stored as one row per language sharing (page_id, sort_order),
 * managed in the admin as one counter with per-language labels.
 */
class StatCounterRepository extends BaseAdminRepository
{
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT sc.*, l.code AS lang
             FROM stat_counters sc
             INNER JOIN languages l ON l.id = sc.language_id
             ORDER BY sc.page_id, sc.sort_order, l.code'
        );

        $groups = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $key = $row['page_id'] . ':' . $row['sort_order'];
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'id' => (int) $row['id'],
                    'page_id' => (int) $row['page_id'],
                    'sort_order' => (int) $row['sort_order'],
                    'value' => $row['value'],
                    'label' => null,
                ];
            }
            $groups[$key]['id'] = min($groups[$key]['id'], (int) $row['id']);
            if ($row['lang'] === 'en' || $groups[$key]['label'] === null) {
                $groups[$key]['label'] = $row['label'];
            }
        }

        return array_values($groups);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM stat_counters WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT sc.*, l.code
             FROM stat_counters sc
             INNER JOIN languages l ON l.id = sc.language_id
             WHERE sc.page_id = :page_id AND sc.sort_order = :sort_order'
        );
        $stmt->execute(['page_id' => $row['page_id'], 'sort_order' => $row['sort_order']]);

        $counter = [
            'id' => (int) $row['id'],
            'page_id' => (int) $row['page_id'],
            'sort_order' => (int) $row['sort_order'],
            'value' => $row['value'],
            'translations' => [],
        ];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $langRow) {
            $counter['translations'][$langRow['code']] = [
                'label' => $langRow['label'],
                'suffix' => $langRow['suffix'],
            ];
        }

        return $counter;
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

        $this->writeGroup((int) $existing['page_id'], (int) $existing['sort_order'], $data);
    }

    public function delete(int $id): void
    {
        $existing = $this->find($id);
        if ($existing) {
            $this->deleteGroup((int) $existing['page_id'], (int) $existing['sort_order']);
        }
    }

    public function homePageId(): int
    {
        $id = $this->pdo->query("SELECT id FROM pages WHERE slug = 'home' LIMIT 1")->fetchColumn();

        return (int) ($id ?: 1);
    }

    private function deleteGroup(int $pageId, int $sortOrder): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM stat_counters WHERE page_id = :page_id AND sort_order = :sort_order');
        $stmt->execute(['page_id' => $pageId, 'sort_order' => $sortOrder]);
    }

    private function writeGroup(?int $oldPageId, ?int $oldSortOrder, array $data): int
    {
        $pageId = (int) ($data['page_id'] ?? $this->homePageId());
        $newSort = (int) ($data['sort_order'] ?? 0);
        $isUpdate = $oldPageId !== null && $oldSortOrder !== null;

        if ($isUpdate && ($oldPageId !== $pageId || $oldSortOrder !== $newSort)) {
            $this->deleteGroup($oldPageId, $oldSortOrder);
        }

        $select = $this->pdo->prepare(
            'SELECT id FROM stat_counters WHERE page_id = :page_id AND language_id = :language_id AND sort_order = :sort_order LIMIT 1'
        );
        $update = $this->pdo->prepare(
            'UPDATE stat_counters SET label = :label, value = :value, suffix = :suffix WHERE id = :id'
        );
        $insert = $this->pdo->prepare(
            'INSERT INTO stat_counters (page_id, language_id, label, value, suffix, sort_order)
             VALUES (:page_id, :language_id, :label, :value, :suffix, :sort_order)'
        );

        $firstId = 0;
        $keptLanguageIds = [];

        foreach ($this->languages() as $lang) {
            $trans = $data['translations'][$lang['code']] ?? [];
            if (empty($trans['label'])) {
                continue;
            }

            $payload = [
                'page_id' => $pageId,
                'language_id' => (int) $lang['id'],
                'label' => $trans['label'],
                'value' => $data['value'] ?? '',
                'suffix' => ($trans['suffix'] ?? '') !== '' ? $trans['suffix'] : null,
                'sort_order' => $newSort,
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
                    'label' => $payload['label'],
                    'value' => $payload['value'],
                    'suffix' => $payload['suffix'],
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

        if ($keptLanguageIds === []) {
            $this->deleteGroup($pageId, $newSort);
        } else {
            $placeholders = implode(',', array_fill(0, count($keptLanguageIds), '?'));
            $stmt = $this->pdo->prepare(
                "DELETE FROM stat_counters WHERE page_id = ? AND sort_order = ? AND language_id NOT IN ($placeholders)"
            );
            $stmt->execute(array_merge([$pageId, $newSort], $keptLanguageIds));
        }

        return $firstId;
    }
}

<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class MenuRepository extends BaseAdminRepository
{
    public const LOCATION_LABELS = [
        'header' => 'Header navigation',
        'footer_col1' => 'Footer — column 1 (Quick Links)',
        'footer_col2' => 'Footer — column 2 (Explore)',
        'footer_col3' => 'Footer — column 3 (Legal)',
    ];

    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT m.*, (SELECT COUNT(*) FROM menu_items mi WHERE mi.menu_id = m.id) AS item_count
             FROM menus m
             ORDER BY m.id'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM menus WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $menu = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$menu) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT mt.title, l.code
             FROM menu_translations mt
             INNER JOIN languages l ON l.id = mt.language_id
             WHERE mt.menu_id = :id'
        );
        $stmt->execute(['id' => $id]);
        $menu['titles'] = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $menu['titles'][$row['code']] = $row['title'];
        }

        $stmt = $this->pdo->prepare(
            'SELECT mi.* FROM menu_items mi WHERE mi.menu_id = :id ORDER BY mi.sort_order, mi.id'
        );
        $stmt->execute(['id' => $id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $labels = $this->pdo->prepare(
            'SELECT mit.item_id, mit.label, l.code
             FROM menu_item_translations mit
             INNER JOIN languages l ON l.id = mit.language_id
             INNER JOIN menu_items mi ON mi.id = mit.item_id
             WHERE mi.menu_id = :id'
        );
        $labels->execute(['id' => $id]);
        $labelMap = [];
        foreach ($labels->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $labelMap[(int) $row['item_id']][$row['code']] = $row['label'];
        }

        foreach ($items as &$item) {
            $item['labels'] = $labelMap[(int) $item['id']] ?? [];
        }
        unset($item);

        $menu['items'] = $items;

        return $menu;
    }

    /**
     * Update the menu's column titles and replace its item set.
     *
     * @param array<string, string> $titles locale => column title
     * @param array<int, array{url: string, sort_order: int, is_active: int, target: string, labels: array<string, string>}> $items
     */
    public function save(int $menuId, array $titles, array $items): void
    {
        foreach ($titles as $code => $title) {
            $stmt = $this->pdo->prepare(
                'INSERT INTO menu_translations (menu_id, language_id, title)
                 VALUES (:menu_id, :language_id, :title)
                 ON DUPLICATE KEY UPDATE title = VALUES(title)'
            );
            $stmt->execute([
                'menu_id' => $menuId,
                'language_id' => $this->languageId($code),
                'title' => $title !== '' ? $title : null,
            ]);
        }

        $stmt = $this->pdo->prepare('DELETE FROM menu_items WHERE menu_id = :id');
        $stmt->execute(['id' => $menuId]);

        $insertItem = $this->pdo->prepare(
            'INSERT INTO menu_items (menu_id, url, target, sort_order, is_active)
             VALUES (:menu_id, :url, :target, :sort_order, :is_active)'
        );
        $insertLabel = $this->pdo->prepare(
            'INSERT INTO menu_item_translations (item_id, language_id, label)
             VALUES (:item_id, :language_id, :label)'
        );

        foreach ($items as $item) {
            $hasLabel = false;
            foreach ($item['labels'] ?? [] as $label) {
                if (trim((string) $label) !== '') {
                    $hasLabel = true;
                    break;
                }
            }
            if (!$hasLabel) {
                continue;
            }

            $insertItem->execute([
                'menu_id' => $menuId,
                'url' => ($item['url'] ?? '') !== '' ? $item['url'] : null,
                'target' => ($item['target'] ?? '_self') ?: '_self',
                'sort_order' => (int) ($item['sort_order'] ?? 0),
                'is_active' => (int) ($item['is_active'] ?? 1),
            ]);
            $itemId = (int) $this->pdo->lastInsertId();

            foreach ($item['labels'] ?? [] as $code => $label) {
                if (trim((string) $label) === '') {
                    continue;
                }
                $insertLabel->execute([
                    'item_id' => $itemId,
                    'language_id' => $this->languageId($code),
                    'label' => $label,
                ]);
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class GalleryRepository extends TranslatedEntityRepository
{
    protected string $table = 'gallery_collections';
    protected string $translationTable = 'gallery_collection_translations';
    protected string $foreignKey = 'collection_id';
    protected array $entityFields = [
        'cover_image_id' => null,
        'sort_order' => 0,
        'is_active' => 1,
    ];
    protected array $translationFields = ['title', 'slug', 'description'];
    protected string $requiredTranslationField = 'title';
    protected string $labelField = 'title';

    protected function prepareTranslation(array $fields): array
    {
        if (empty($fields['slug']) && !empty($fields['title'])) {
            $fields['slug'] = $this->slugify($fields['title']);
        }

        return $fields;
    }

    public function find(int $id): ?array
    {
        $entity = parent::find($id);
        if ($entity === null) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT gi.*, m.file_path, m.file_type
             FROM gallery_items gi
             LEFT JOIN media m ON m.id = gi.media_id
             WHERE gi.collection_id = :id
             ORDER BY gi.sort_order, gi.id'
        );
        $stmt->execute(['id' => $id]);
        $entity['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $entity;
    }

    /**
     * Replace the collection's items with the submitted set.
     *
     * @param array<int, array{media_id: int, caption: string, sort_order: int}> $items
     */
    public function saveItems(int $collectionId, array $items): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM gallery_items WHERE collection_id = :id');
        $stmt->execute(['id' => $collectionId]);

        $insert = $this->pdo->prepare(
            'INSERT INTO gallery_items (collection_id, media_id, caption, sort_order)
             VALUES (:collection_id, :media_id, :caption, :sort_order)'
        );

        foreach ($items as $item) {
            if (empty($item['media_id'])) {
                continue;
            }
            $insert->execute([
                'collection_id' => $collectionId,
                'media_id' => (int) $item['media_id'],
                'caption' => ($item['caption'] ?? '') !== '' ? $item['caption'] : null,
                'sort_order' => (int) ($item['sort_order'] ?? 0),
            ]);
        }
    }

    public function itemCount(int $collectionId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM gallery_items WHERE collection_id = :id');
        $stmt->execute(['id' => $collectionId]);

        return (int) $stmt->fetchColumn();
    }
}

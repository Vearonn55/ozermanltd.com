<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class ContentBlockRepository extends TranslatedEntityRepository
{
    public const AREAS = [
        'home_operations' => 'Home — What We Do cards',
        'about_values' => 'About — Values cards',
        'about_vision' => 'About — Vision statement',
        'about_mission' => 'About — Mission statement',
    ];

    protected string $table = 'content_blocks';
    protected string $translationTable = 'content_block_translations';
    protected string $foreignKey = 'block_id';
    protected array $entityFields = [
        'area' => 'home_operations',
        'icon' => null,
        'sort_order' => 0,
        'is_active' => 1,
    ];
    protected array $translationFields = ['title', 'body'];
    protected string $requiredTranslationField = 'title';
    protected string $labelField = 'title';
    protected string $orderBy = 'e.area, e.sort_order, e.id';

    public function byArea(string $area): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT e.*, t.title AS label
             FROM {$this->table} e
             LEFT JOIN {$this->translationTable} t ON t.{$this->foreignKey} = e.id AND t.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             WHERE e.area = :area
             ORDER BY e.sort_order, e.id"
        );
        $stmt->execute(['area' => $area]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

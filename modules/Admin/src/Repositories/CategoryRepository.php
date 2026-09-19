<?php

declare(strict_types=1);

namespace Admin\Repositories;

/**
 * News and project categories share the same shape; the $type switch picks the tables.
 */
class CategoryRepository extends TranslatedEntityRepository
{
    protected string $foreignKey = 'category_id';
    protected array $translationFields = ['name', 'slug'];
    protected string $requiredTranslationField = 'name';
    protected string $labelField = 'name';

    public function __construct(private string $type = 'news')
    {
        parent::__construct();

        if ($type === 'project') {
            $this->table = 'project_categories';
            $this->translationTable = 'project_category_translations';
            $this->entityFields = ['icon' => null, 'sort_order' => 0, 'is_active' => 1];
        } else {
            $this->table = 'news_categories';
            $this->translationTable = 'news_category_translations';
            $this->entityFields = ['color' => null, 'icon' => null, 'sort_order' => 0, 'is_active' => 1];
        }
    }

    public function type(): string
    {
        return $this->type;
    }

    protected function prepareTranslation(array $fields): array
    {
        if (empty($fields['slug']) && !empty($fields['name'])) {
            $fields['slug'] = $this->slugify($fields['name']);
        }

        return $fields;
    }
}

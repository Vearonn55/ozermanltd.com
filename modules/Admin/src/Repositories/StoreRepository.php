<?php

declare(strict_types=1);

namespace Admin\Repositories;

class StoreRepository extends TranslatedEntityRepository
{
    protected string $table = 'stores';
    protected string $translationTable = 'store_translations';
    protected string $foreignKey = 'store_id';
    protected array $entityFields = [
        'media_id' => null,
        'phone' => null,
        'email' => null,
        'sort_order' => 0,
        'is_active' => 1,
    ];
    protected array $translationFields = ['name', 'slug', 'city', 'address', 'working_hours'];
    protected string $requiredTranslationField = 'name';
    protected string $labelField = 'name';

    protected function prepareTranslation(array $fields): array
    {
        if (empty($fields['slug']) && !empty($fields['name'])) {
            $fields['slug'] = $this->slugify($fields['name']);
        }

        return $fields;
    }
}

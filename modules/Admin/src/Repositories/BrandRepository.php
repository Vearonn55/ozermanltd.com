<?php

declare(strict_types=1);

namespace Admin\Repositories;

/** Partnership brands (brands + brand_translations). */
class BrandRepository extends TranslatedEntityRepository
{
    protected string $table = 'brands';
    protected string $translationTable = 'brand_translations';
    protected string $foreignKey = 'brand_id';
    protected array $entityFields = [
        'slug' => null,
        'media_id' => null,
        'logo' => null,
        'website_url' => null,
        'sort_order' => 0,
        'is_active' => 1,
    ];
    protected array $translationFields = ['name', 'tagline', 'description'];
    protected string $requiredTranslationField = 'name';
    protected string $labelField = 'name';
}

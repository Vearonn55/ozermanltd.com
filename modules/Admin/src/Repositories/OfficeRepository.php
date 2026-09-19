<?php

declare(strict_types=1);

namespace Admin\Repositories;

class OfficeRepository extends TranslatedEntityRepository
{
    protected string $table = 'offices';
    protected string $translationTable = 'office_translations';
    protected string $foreignKey = 'office_id';
    protected array $entityFields = [
        'phone' => null,
        'email' => null,
        'whatsapp' => null,
        'is_headquarters' => 0,
        'is_active' => 1,
    ];
    protected array $translationFields = ['label', 'address', 'city', 'country', 'working_hours'];
    protected string $requiredTranslationField = 'label';
    protected string $labelField = 'label';
    protected string $orderBy = 'e.is_headquarters DESC, e.id';
}

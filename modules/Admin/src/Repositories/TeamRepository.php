<?php

declare(strict_types=1);

namespace Admin\Repositories;

class TeamRepository extends TranslatedEntityRepository
{
    protected string $table = 'team_members';
    protected string $translationTable = 'team_member_translations';
    protected string $foreignKey = 'member_id';
    protected array $entityFields = [
        'media_id' => null,
        'email' => null,
        'phone' => null,
        'type' => 'management',
        'sort_order' => 0,
        'is_active' => 1,
    ];
    protected array $translationFields = ['full_name', 'position', 'bio'];
    protected string $requiredTranslationField = 'full_name';
    protected string $labelField = 'full_name';
}

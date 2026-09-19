<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\TeamRepository;

class TeamController extends SimpleCrudController
{
    protected string $entityType = 'team_member';
    protected string $routePath = 'team';
    protected string $viewDir = 'team';
    protected string $singular = 'Team Member';
    protected string $plural = 'Team';

    public function __construct()
    {
        $this->repo = new TeamRepository();
    }

    protected function payloadFromRequest(): array
    {
        return [
            'media_id' => (($_POST['media_id'] ?? '') !== '' ? (int) $_POST['media_id'] : null),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'type' => in_array($_POST['type'] ?? '', ['founder', 'management', 'staff'], true) ? $_POST['type'] : 'management',
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['full_name', 'position', 'bio']),
        ];
    }
}

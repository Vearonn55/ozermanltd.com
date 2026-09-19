<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\StoreRepository;

class StoreController extends SimpleCrudController
{
    protected string $entityType = 'store';
    protected string $routePath = 'stores';
    protected string $viewDir = 'stores';
    protected string $singular = 'Store';
    protected string $plural = 'Stores';

    public function __construct()
    {
        $this->repo = new StoreRepository();
    }

    protected function payloadFromRequest(): array
    {
        return [
            'media_id' => (($_POST['media_id'] ?? '') !== '' ? (int) $_POST['media_id'] : null),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['name', 'slug', 'city', 'address', 'working_hours']),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\OfficeRepository;

class OfficeController extends SimpleCrudController
{
    protected string $entityType = 'office';
    protected string $routePath = 'offices';
    protected string $viewDir = 'offices';
    protected string $singular = 'Office';
    protected string $plural = 'Offices';

    public function __construct()
    {
        $this->repo = new OfficeRepository();
    }

    protected function payloadFromRequest(): array
    {
        return [
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'whatsapp' => trim($_POST['whatsapp'] ?? ''),
            'is_headquarters' => isset($_POST['is_headquarters']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['label', 'address', 'city', 'country', 'working_hours']),
        ];
    }
}

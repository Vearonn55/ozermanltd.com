<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\ContentBlockRepository;

class ContentBlockController extends SimpleCrudController
{
    protected string $entityType = 'content_block';
    protected string $routePath = 'site-sections';
    protected string $viewDir = 'site-sections';
    protected string $singular = 'Section Block';
    protected string $plural = 'Site Sections';

    public function __construct()
    {
        $this->repo = new ContentBlockRepository();
    }

    protected function indexData(): array
    {
        return ['areas' => ContentBlockRepository::AREAS];
    }

    protected function formData(): array
    {
        return ['areas' => ContentBlockRepository::AREAS];
    }

    protected function payloadFromRequest(): array
    {
        $area = $_POST['area'] ?? 'home_operations';
        if (!array_key_exists($area, ContentBlockRepository::AREAS)) {
            $area = 'home_operations';
        }

        return [
            'area' => $area,
            'icon' => trim($_POST['icon'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['title', 'body']),
        ];
    }
}

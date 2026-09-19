<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\CategoryRepository;

/**
 * Manages news and project categories; ?type=news|project switches the set.
 */
class CategoryController extends SimpleCrudController
{
    protected string $entityType = 'category';
    protected string $routePath = 'categories';
    protected string $viewDir = 'categories';
    protected string $singular = 'Category';
    protected string $plural = 'Categories';

    private string $type;

    public function __construct()
    {
        $this->type = in_array($_REQUEST['type'] ?? '', ['news', 'project'], true) ? $_REQUEST['type'] : 'news';
        $this->repo = new CategoryRepository($this->type);
        $this->entityType = $this->type . '_category';
        $this->routePath = 'categories?type=' . $this->type;
    }

    protected function indexData(): array
    {
        return ['type' => $this->type];
    }

    protected function formData(): array
    {
        return ['type' => $this->type];
    }

    protected function payloadFromRequest(): array
    {
        $payload = [
            'icon' => trim($_POST['icon'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['name', 'slug']),
        ];

        if ($this->type === 'news') {
            $payload['color'] = trim($_POST['color'] ?? '');
        }

        return $payload;
    }
}

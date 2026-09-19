<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\BrandRepository;

class PartnershipController extends SimpleCrudController
{
    protected string $entityType = 'brand';
    protected string $routePath = 'partnerships';
    protected string $viewDir = 'partnerships';
    protected string $singular = 'Partnership';
    protected string $plural = 'Partnerships';

    public function __construct()
    {
        $this->repo = new BrandRepository();
    }

    protected function payloadFromRequest(): array
    {
        $slug = trim($_POST['slug'] ?? '');
        if ($slug === '') {
            $name = trim($_POST['translations_en_name'] ?? '');
            $slug = $name !== '' ? $this->repo->slugify($name) : null;
        }

        return [
            'slug' => $slug,
            'media_id' => (($_POST['media_id'] ?? '') !== '' ? (int) $_POST['media_id'] : null),
            'logo' => trim($_POST['logo'] ?? ''),
            'website_url' => trim($_POST['website_url'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['name', 'tagline', 'description']),
        ];
    }
}

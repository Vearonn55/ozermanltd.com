<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\GalleryRepository;

class GalleryController extends SimpleCrudController
{
    protected string $entityType = 'gallery_collection';
    protected string $routePath = 'gallery';
    protected string $viewDir = 'gallery';
    protected string $singular = 'Collection';
    protected string $plural = 'Gallery';

    public function __construct()
    {
        $this->repo = new GalleryRepository();
    }

    public function store(): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $id = $this->repo->create($this->payloadFromRequest());
        $this->repo->saveItems($id, $this->itemsFromRequest());

        $this->logActivity('create', $this->entityType, $id);
        flash('success', 'Collection created.');
        redirect(admin_url('gallery/' . $id . '/edit'));
    }

    public function update(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', 'Collection not found.');
            redirect(admin_url('gallery'));
        }

        $this->repo->update($id, $this->payloadFromRequest());
        $this->repo->saveItems($id, $this->itemsFromRequest());

        $this->logActivity('update', $this->entityType, $id);
        flash('success', 'Collection updated.');
        redirect(admin_url('gallery/' . $id . '/edit'));
    }

    protected function payloadFromRequest(): array
    {
        return [
            'cover_image_id' => (($_POST['cover_image_id'] ?? '') !== '' ? (int) $_POST['cover_image_id'] : null),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['title', 'slug', 'description']),
        ];
    }

    /** @return array<int, array{media_id: int, caption: string, sort_order: int}> */
    private function itemsFromRequest(): array
    {
        $items = [];
        foreach ((array) ($_POST['items'] ?? []) as $item) {
            if (!is_array($item) || ($item['media_id'] ?? '') === '') {
                continue;
            }
            $items[] = [
                'media_id' => (int) $item['media_id'],
                'caption' => trim((string) ($item['caption'] ?? '')),
                'sort_order' => (int) ($item['sort_order'] ?? 0),
            ];
        }

        return $items;
    }
}

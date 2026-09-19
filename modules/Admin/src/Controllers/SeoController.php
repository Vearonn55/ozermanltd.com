<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\SeoMetaRepository;

class SeoController extends BaseAdminController
{
    private SeoMetaRepository $repo;

    public function __construct()
    {
        $this->repo = new SeoMetaRepository();
    }

    public function index(): void
    {
        $this->authorize('editor');
        $type = trim((string) ($_GET['type'] ?? ''));
        $this->render('seo.index', [
            'pageTitle' => 'SEO Meta',
            'items' => $this->repo->all($type !== '' ? $type : null),
            'filterType' => $type,
            'languages' => $this->repo->languages(),
        ]);
    }

    public function create(): void
    {
        $this->authorize('editor');
        $this->render('seo.form', [
            'pageTitle' => 'New SEO Meta',
            'item' => null,
            'languages' => $this->repo->languages(),
        ]);
    }

    public function store(): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $id = $this->repo->create($this->payload());
        $this->logActivity('create', 'seo_meta', $id);
        flash('success', 'SEO meta created.');
        redirect(admin_url('seo'));
    }

    public function edit(int $id): void
    {
        $this->authorize('editor');
        $item = $this->repo->find($id);
        if (!$item) {
            flash('error', 'SEO meta not found.');
            redirect(admin_url('seo'));
        }

        $this->render('seo.form', [
            'pageTitle' => 'Edit SEO Meta',
            'item' => $item,
            'languages' => $this->repo->languages(),
        ]);
    }

    public function update(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', 'SEO meta not found.');
            redirect(admin_url('seo'));
        }

        $this->repo->update($id, $this->payload());
        $this->logActivity('update', 'seo_meta', $id);
        flash('success', 'SEO meta updated.');
        redirect(admin_url('seo'));
    }

    public function destroy(int $id): void
    {
        $this->authorize('content_manager');
        $this->verifyCsrf();
        $this->repo->delete($id);
        $this->logActivity('delete', 'seo_meta', $id);
        flash('success', 'SEO meta deleted.');
        redirect(admin_url('seo'));
    }

    /** @return array<string, mixed> */
    private function payload(): array
    {
        return [
            'entity_type' => trim((string) ($_POST['entity_type'] ?? 'page')),
            'entity_id' => (int) ($_POST['entity_id'] ?? 0),
            'language_id' => (int) ($_POST['language_id'] ?? 0),
            'meta_title' => trim((string) ($_POST['meta_title'] ?? '')),
            'meta_description' => trim((string) ($_POST['meta_description'] ?? '')),
            'og_title' => trim((string) ($_POST['og_title'] ?? '')),
            'og_description' => trim((string) ($_POST['og_description'] ?? '')),
            'og_image_id' => (($_POST['og_image_id'] ?? '') !== '' ? (int) $_POST['og_image_id'] : null),
            'canonical_url' => trim((string) ($_POST['canonical_url'] ?? '')),
            'robots' => trim((string) ($_POST['robots'] ?? 'index, follow')),
        ];
    }
}

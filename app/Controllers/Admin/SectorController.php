<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Repositories\Admin\SectorRepository;

class SectorController extends BaseAdminController
{
    private SectorRepository $repo;

    public function __construct()
    {
        $this->repo = new SectorRepository();
    }

    public function index(): void
    {
        $this->requireAuth();
        $this->render('sectors.index', [
            'pageTitle' => 'Sectors',
            'items' => $this->repo->all(),
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->render('sectors.form', [
            'pageTitle' => 'New Sector',
            'item' => null,
            'languages' => $this->repo->languages(),
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = $this->repo->create([
            'icon' => trim($_POST['icon'] ?? ''),
            'color' => trim($_POST['color'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['name', 'slug', 'overview', 'services_text', 'meta_title', 'meta_description']),
        ]);

        $this->logActivity('create', 'sector', $id);
        flash('success', 'Sector created successfully.');
        redirect('/admin/sectors');
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Sector not found.');
            redirect('/admin/sectors');
        }

        $this->render('sectors.form', [
            'pageTitle' => 'Edit Sector',
            'item' => $item,
            'languages' => $this->repo->languages(),
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', 'Sector not found.');
            redirect('/admin/sectors');
        }

        $this->repo->update($id, [
            'icon' => trim($_POST['icon'] ?? ''),
            'color' => trim($_POST['color'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['name', 'slug', 'overview', 'services_text', 'meta_title', 'meta_description']),
        ]);

        $this->logActivity('update', 'sector', $id);
        flash('success', 'Sector updated successfully.');
        redirect('/admin/sectors');
    }

    public function destroy(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', 'sector', $id);
        flash('success', 'Sector deleted.');
        redirect('/admin/sectors');
    }
}

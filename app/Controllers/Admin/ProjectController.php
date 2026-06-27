<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Repositories\Admin\ProjectRepository;

class ProjectController extends BaseAdminController
{
    private ProjectRepository $repo;

    public function __construct()
    {
        $this->repo = new ProjectRepository();
    }

    public function index(): void
    {
        $this->requireAuth();
        $this->render('projects.index', [
            'pageTitle' => 'Projects',
            'items' => $this->repo->all(),
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->render('projects.form', [
            'pageTitle' => 'New Project',
            'item' => null,
            'languages' => $this->repo->languages(),
            'categories' => $this->repo->categories(),
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = $this->repo->create([
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'planning',
            'location' => trim($_POST['location'] ?? ''),
            'delivery_date' => trim($_POST['delivery_date'] ?? ''),
            'start_price' => $_POST['start_price'] ?? null,
            'currency' => $_POST['currency'] ?? 'GBP',
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['title', 'slug', 'description', 'features', 'payment_plan', 'meta_title', 'meta_description']),
        ]);

        $this->logActivity('create', 'project', $id);
        flash('success', 'Project created successfully.');
        redirect('/admin/projects');
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Project not found.');
            redirect('/admin/projects');
        }

        $this->render('projects.form', [
            'pageTitle' => 'Edit Project',
            'item' => $item,
            'languages' => $this->repo->languages(),
            'categories' => $this->repo->categories(),
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', 'Project not found.');
            redirect('/admin/projects');
        }

        $this->repo->update($id, [
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'planning',
            'location' => trim($_POST['location'] ?? ''),
            'delivery_date' => trim($_POST['delivery_date'] ?? ''),
            'start_price' => $_POST['start_price'] ?? null,
            'currency' => $_POST['currency'] ?? 'GBP',
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['title', 'slug', 'description', 'features', 'payment_plan', 'meta_title', 'meta_description']),
        ]);

        $this->logActivity('update', 'project', $id);
        flash('success', 'Project updated successfully.');
        redirect('/admin/projects');
    }

    public function destroy(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', 'project', $id);
        flash('success', 'Project deleted.');
        redirect('/admin/projects');
    }
}

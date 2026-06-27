<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Repositories\Admin\PageRepository;

class PageController extends BaseAdminController
{
    private PageRepository $repo;

    public function __construct()
    {
        $this->repo = new PageRepository();
    }

    public function index(): void
    {
        $this->requireAuth();
        $this->render('pages.index', [
            'pageTitle' => 'Pages',
            'items' => $this->repo->all(),
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->render('pages.form', [
            'pageTitle' => 'New Page',
            'item' => null,
            'languages' => $this->repo->languages(),
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = $this->repo->create([
            'slug' => trim($_POST['slug'] ?? ''),
            'template' => $_POST['template'] ?? 'default',
            'status' => $_POST['status'] ?? 'draft',
            'show_in_nav' => isset($_POST['show_in_nav']) ? 1 : 0,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'translations' => $this->parseTranslations($_POST, ['title', 'content', 'excerpt', 'meta_title', 'meta_description']),
        ]);

        $this->logActivity('create', 'page', $id);
        flash('success', 'Page created successfully.');
        redirect('/admin/pages');
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Page not found.');
            redirect('/admin/pages');
        }

        $this->render('pages.form', [
            'pageTitle' => 'Edit Page',
            'item' => $item,
            'languages' => $this->repo->languages(),
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', 'Page not found.');
            redirect('/admin/pages');
        }

        $this->repo->update($id, [
            'slug' => trim($_POST['slug'] ?? ''),
            'template' => $_POST['template'] ?? 'default',
            'status' => $_POST['status'] ?? 'draft',
            'show_in_nav' => isset($_POST['show_in_nav']) ? 1 : 0,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'translations' => $this->parseTranslations($_POST, ['title', 'content', 'excerpt', 'meta_title', 'meta_description']),
        ]);

        $this->logActivity('update', 'page', $id);
        flash('success', 'Page updated successfully.');
        redirect('/admin/pages');
    }

    public function destroy(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', 'page', $id);
        flash('success', 'Page deleted.');
        redirect('/admin/pages');
    }
}

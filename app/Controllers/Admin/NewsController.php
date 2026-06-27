<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Repositories\Admin\NewsRepository;

class NewsController extends BaseAdminController
{
    private NewsRepository $repo;

    public function __construct()
    {
        $this->repo = new NewsRepository();
    }

    public function index(): void
    {
        $this->requireAuth();
        $this->render('news.index', [
            'pageTitle' => 'News',
            'items' => $this->repo->all(),
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->render('news.form', [
            'pageTitle' => 'New Article',
            'item' => null,
            'languages' => $this->repo->languages(),
            'categories' => $this->repo->categories(),
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $user = $this->requireAuth();

        $id = $this->repo->create([
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'draft',
            'publish_date' => $_POST['publish_date'] ?? null,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_pinned' => isset($_POST['is_pinned']) ? 1 : 0,
            'author_id' => $user['id'],
            'translations' => $this->parseTranslations($_POST, ['title', 'slug', 'content', 'excerpt', 'meta_title', 'meta_description']),
        ]);

        $this->logActivity('create', 'news', $id);
        $status = $_POST['status'] ?? 'published';
        flash('success', $status === 'published'
            ? 'Article created and visible on the site.'
            : 'Article saved as draft — it will not appear on the site until published.');
        redirect('/admin/news');
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Article not found.');
            redirect('/admin/news');
        }

        $this->render('news.form', [
            'pageTitle' => 'Edit Article',
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
            flash('error', 'Article not found.');
            redirect('/admin/news');
        }

        $this->repo->update($id, [
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'draft',
            'publish_date' => $_POST['publish_date'] ?? null,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_pinned' => isset($_POST['is_pinned']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['title', 'slug', 'content', 'excerpt', 'meta_title', 'meta_description']),
        ]);

        $this->logActivity('update', 'news', $id);
        $status = $_POST['status'] ?? 'published';
        flash('success', $status === 'published'
            ? 'Article updated — visible on the site.'
            : 'Article saved as draft — hidden on the site.');
        redirect('/admin/news');
    }

    public function destroy(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', 'news', $id);
        flash('success', 'Article deleted.');
        redirect('/admin/news');
    }
}

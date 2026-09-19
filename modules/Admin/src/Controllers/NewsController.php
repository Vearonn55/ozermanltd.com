<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\NewsRepository;

class NewsController extends BaseAdminController
{
    private NewsRepository $repo;

    public function __construct()
    {
        $this->repo = new NewsRepository();
    }

    public function index(): void
    {
        $this->authorize('editor');
        $params = $this->listParams();
        $result = $this->repo->paginate($params['q'], $params['status'], $params['page']);

        $this->render('news.index', [
            'pageTitle' => 'News',
            'items' => $result['items'],
            'total' => $result['total'],
            'pages' => $result['pages'],
            'page' => $result['page'],
            'q' => $params['q'],
            'statusFilter' => $params['status'],
        ]);
    }

    public function create(): void
    {
        $this->authorize('editor');
        $this->render('news.form', [
            'pageTitle' => 'New Article',
            'item' => null,
            'languages' => $this->repo->languages(),
            'categories' => $this->repo->categories(),
            'revisions' => [],
            'restoredFrom' => null,
        ]);
    }

    public function store(): void
    {
        $user = $this->authorize('editor');
        $this->verifyCsrf();

        $data = $this->payloadFromRequest();
        $data['author_id'] = $user['id'];
        $id = $this->repo->create($data);

        $this->recordRevision('news', $id, $data);
        $this->logActivity('create', 'news', $id);
        $status = $_POST['status'] ?? 'published';
        flash('success', $status === 'published'
            ? 'Article created and visible on the site.'
            : 'Article saved as draft — it will not appear on the site until published.');
        redirect(admin_url('news'));
    }

    public function edit(int $id): void
    {
        $this->authorize('editor');
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Article not found.');
            redirect(admin_url('news'));
        }

        $context = $this->withRevisions('news', $id, $item);

        $this->render('news.form', [
            'pageTitle' => 'Edit Article',
            'item' => $context['item'],
            'languages' => $this->repo->languages(),
            'categories' => $this->repo->categories(),
            'revisions' => $context['revisions'],
            'restoredFrom' => $context['restoredFrom'],
        ]);
    }

    public function update(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', 'Article not found.');
            redirect(admin_url('news'));
        }

        $data = $this->payloadFromRequest();
        $this->repo->update($id, $data);

        $this->recordRevision('news', $id, $data);
        $this->logActivity('update', 'news', $id);
        $status = $_POST['status'] ?? 'published';
        flash('success', $status === 'published'
            ? 'Article updated — visible on the site.'
            : 'Article saved as draft — hidden on the site.');
        redirect(admin_url('news'));
    }

    public function autosave(int $id): void
    {
        $this->authorize('editor');

        if (!$this->repo->find($id)) {
            http_response_code(404);
            exit;
        }

        $this->handleAutosave('news', $id, $this->payloadFromRequest());
    }

    public function toggleStatus(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $item = $this->repo->find($id);
        if ($item) {
            $newStatus = ($item['status'] ?? 'draft') === 'published' ? 'draft' : 'published';
            $this->repo->setStatus($id, $newStatus);
            $this->logActivity('status_' . $newStatus, 'news', $id);
            flash('success', $newStatus === 'published' ? 'Article published.' : 'Article set to draft.');
        }

        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('news'));
    }

    public function bulk(): void
    {
        $this->authorize('editor');
        $this->handleBulkRequest('news', function (string $action, int $id): bool {
            switch ($action) {
                case 'publish':
                    $this->repo->setStatus($id, 'published');
                    return true;
                case 'draft':
                    $this->repo->setStatus($id, 'draft');
                    return true;
                case 'delete':
                    $this->repo->delete($id);
                    return true;
            }
            return false;
        }, 'news');
    }

    public function destroy(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', 'news', $id);
        flash('success', 'Article deleted.');
        redirect(admin_url('news'));
    }

    /** @return array<string, mixed> */
    private function payloadFromRequest(): array
    {
        $status = $_POST['status'] ?? 'draft';
        if (!in_array($status, ['draft', 'pending_review', 'published'], true)) {
            $status = 'draft';
        }

        $publishDate = trim((string) ($_POST['publish_date'] ?? ''));
        if ($publishDate !== '') {
            $publishDate = str_replace('T', ' ', $publishDate);
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $publishDate)) {
                $publishDate .= ':00';
            }
        } elseif ($status === 'published') {
            $publishDate = date('Y-m-d H:i:s');
        } else {
            $publishDate = null;
        }

        $translations = $this->parseTranslations($_POST, ['title', 'slug', 'content', 'excerpt', 'meta_title', 'meta_description']);
        $hasTitle = false;
        foreach ($translations as $fields) {
            if (!empty($fields['title'])) {
                $hasTitle = true;
                break;
            }
        }
        if (!$hasTitle) {
            flash('error', 'Please enter a title in at least one language.');
            redirect($_SERVER['HTTP_REFERER'] ?? admin_url('news/create'));
        }

        return [
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $status,
            'publish_date' => $publishDate,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_pinned' => isset($_POST['is_pinned']) ? 1 : 0,
            'featured_image_id' => (($_POST['featured_image_id'] ?? '') !== '' ? (int) $_POST['featured_image_id'] : null),
            'translations' => $translations,
        ];
    }
}

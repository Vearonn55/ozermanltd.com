<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\CommentRepository;

class CommentController extends BaseAdminController
{
    private CommentRepository $repo;

    public function __construct()
    {
        $this->repo = new CommentRepository();
    }

    public function index(): void
    {
        $this->authorize('editor');
        $status = trim((string) ($_GET['status'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $result = $this->repo->paginate($status, $page);

        $this->render('comments.index', [
            'pageTitle' => 'News Reviews',
            'items' => $result['items'],
            'total' => $result['total'],
            'pages' => $result['pages'],
            'page' => $result['page'],
            'statusFilter' => $status,
            'pendingCount' => $this->repo->countPending(),
        ]);
    }

    public function toggleStatus(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $status = trim((string) ($_POST['status'] ?? ''));
        if (!in_array($status, ['pending', 'approved', 'rejected', 'spam'], true)) {
            flash('error', 'Invalid review status.');
            redirect(admin_url('comments'));
        }

        $this->repo->setStatus($id, $status);
        $this->logActivity('comment_' . $status, 'news_comment', $id);
        flash('success', 'Review marked as ' . $status . '.');
        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('comments'));
    }

    public function destroy(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();
        $this->repo->delete($id);
        $this->logActivity('delete', 'news_comment', $id);
        flash('success', 'Review deleted.');
        redirect(admin_url('comments'));
    }
}

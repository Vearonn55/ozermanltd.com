<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Repositories\Admin\ContactRepository;

class ContactController extends BaseAdminController
{
    private ContactRepository $repo;

    public function __construct()
    {
        $this->repo = new ContactRepository();
    }

    public function index(): void
    {
        $this->requireAuth();
        $status = $_GET['status'] ?? null;

        $this->render('contacts.index', [
            'pageTitle' => 'Contact Messages',
            'items' => $this->repo->all($status),
            'currentStatus' => $status,
            'statusCounts' => [
                'all' => $this->repo->count(),
                'new' => $this->repo->count('new') + $this->repo->count('received'),
                'read' => $this->repo->count('read'),
                'replied' => $this->repo->count('replied'),
                'archived' => $this->repo->count('archived'),
            ],
        ]);
    }

    public function show(int $id): void
    {
        $this->requireAuth();
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Message not found.');
            redirect('/admin/contacts');
        }

        if (in_array($item['status'], ['new', 'received'], true)) {
            $this->repo->updateStatus($id, 'read');
            $item['status'] = 'read';
        }

        $this->render('contacts.show', [
            'pageTitle' => 'Message from ' . $item['name'],
            'item' => $item,
        ]);
    }

    public function updateStatus(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $status = $_POST['status'] ?? '';
        $allowed = ['new', 'read', 'replied', 'archived', 'received'];

        if (!in_array($status, $allowed, true)) {
            flash('error', 'Invalid status.');
            redirect('/admin/contacts/' . $id);
        }

        $this->repo->updateStatus($id, $status);
        $this->logActivity('update_status', 'contact_message', $id);
        flash('success', 'Status updated.');
        redirect('/admin/contacts/' . $id);
    }

    public function destroy(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', 'contact_message', $id);
        flash('success', 'Message deleted.');
        redirect('/admin/contacts');
    }
}

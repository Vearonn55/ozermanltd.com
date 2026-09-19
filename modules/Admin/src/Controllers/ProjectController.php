<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\ProjectRepository;

class ProjectController extends BaseAdminController
{
    private ProjectRepository $repo;

    public function __construct()
    {
        $this->repo = new ProjectRepository();
    }

    public function index(): void
    {
        $this->authorize('editor');
        $params = $this->listParams();
        $result = $this->repo->paginate($params['q'], $params['status'], $params['page']);

        $this->render('projects.index', [
            'pageTitle' => 'Projects',
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
        $this->render('projects.form', [
            'pageTitle' => 'New Project',
            'item' => null,
            'languages' => $this->repo->languages(),
            'categories' => $this->repo->categories(),
            'revisions' => [],
            'restoredFrom' => null,
        ]);
    }

    public function store(): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $data = $this->payloadFromRequest();
        $id = $this->repo->create($data);

        $this->recordRevision('project', $id, $data);
        $this->logActivity('create', 'project', $id);
        flash('success', 'Project created successfully.');
        redirect(admin_url('projects'));
    }

    public function edit(int $id): void
    {
        $this->authorize('editor');
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Project not found.');
            redirect(admin_url('projects'));
        }

        $context = $this->withRevisions('project', $id, $item);

        $this->render('projects.form', [
            'pageTitle' => 'Edit Project',
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
            flash('error', 'Project not found.');
            redirect(admin_url('projects'));
        }

        $data = $this->payloadFromRequest();
        $this->repo->update($id, $data);

        $this->recordRevision('project', $id, $data);
        $this->logActivity('update', 'project', $id);
        flash('success', 'Project updated successfully.');
        redirect(admin_url('projects'));
    }

    public function autosave(int $id): void
    {
        $this->authorize('editor');

        if (!$this->repo->find($id)) {
            http_response_code(404);
            exit;
        }

        $this->handleAutosave('project', $id, $this->payloadFromRequest());
    }

    public function toggleStatus(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $item = $this->repo->find($id);
        if ($item) {
            $active = !((int) ($item['is_active'] ?? 0) === 1);
            $this->repo->setActive($id, $active);
            $this->logActivity($active ? 'activate' : 'deactivate', 'project', $id);
            flash('success', $active ? 'Project is now visible on the site.' : 'Project hidden from the site.');
        }

        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('projects'));
    }

    public function bulk(): void
    {
        $this->authorize('editor');
        $this->handleBulkRequest('project', function (string $action, int $id): bool {
            switch ($action) {
                case 'activate':
                    $this->repo->setActive($id, true);
                    return true;
                case 'deactivate':
                    $this->repo->setActive($id, false);
                    return true;
                case 'delete':
                    $this->repo->delete($id);
                    return true;
            }
            return false;
        }, 'projects');
    }

    public function destroy(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', 'project', $id);
        flash('success', 'Project deleted.');
        redirect(admin_url('projects'));
    }

    /** @return array<string, mixed> */
    private function payloadFromRequest(): array
    {
        return [
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'planning',
            'location' => trim($_POST['location'] ?? ''),
            'delivery_date' => trim($_POST['delivery_date'] ?? ''),
            'start_price' => $_POST['start_price'] ?? null,
            'currency' => $_POST['currency'] ?? 'GBP',
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'featured_image_id' => (($_POST['featured_image_id'] ?? '') !== '' ? (int) $_POST['featured_image_id'] : null),
            'translations' => $this->parseTranslations($_POST, ['title', 'slug', 'description', 'features', 'payment_plan', 'meta_title', 'meta_description']),
        ];
    }
}

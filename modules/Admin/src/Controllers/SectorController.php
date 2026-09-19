<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\SectorRepository;

class SectorController extends BaseAdminController
{
    private SectorRepository $repo;

    public function __construct()
    {
        $this->repo = new SectorRepository();
    }

    public function index(): void
    {
        $this->authorize('editor');
        $params = $this->listParams();
        $result = $this->repo->paginate($params['q'], $params['status'], $params['page']);

        $this->render('sectors.index', [
            'pageTitle' => 'Sectors',
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
        $this->render('sectors.form', [
            'pageTitle' => 'New Sector',
            'item' => null,
            'languages' => $this->repo->languages(),
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

        $this->recordRevision('sector', $id, $data);
        $this->logActivity('create', 'sector', $id);
        flash('success', 'Sector created successfully.');
        redirect(admin_url('sectors'));
    }

    public function edit(int $id): void
    {
        $this->authorize('editor');
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Sector not found.');
            redirect(admin_url('sectors'));
        }

        $context = $this->withRevisions('sector', $id, $item);

        $this->render('sectors.form', [
            'pageTitle' => 'Edit Sector',
            'item' => $context['item'],
            'languages' => $this->repo->languages(),
            'revisions' => $context['revisions'],
            'restoredFrom' => $context['restoredFrom'],
        ]);
    }

    public function update(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', 'Sector not found.');
            redirect(admin_url('sectors'));
        }

        $data = $this->payloadFromRequest();
        $this->repo->update($id, $data);

        $this->recordRevision('sector', $id, $data);
        $this->logActivity('update', 'sector', $id);
        flash('success', 'Sector updated successfully.');
        redirect(admin_url('sectors'));
    }

    public function autosave(int $id): void
    {
        $this->authorize('editor');

        if (!$this->repo->find($id)) {
            http_response_code(404);
            exit;
        }

        $this->handleAutosave('sector', $id, $this->payloadFromRequest());
    }

    public function toggleStatus(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $item = $this->repo->find($id);
        if ($item) {
            $active = !((int) ($item['is_active'] ?? 0) === 1);
            $this->repo->setActive($id, $active);
            $this->logActivity($active ? 'activate' : 'deactivate', 'sector', $id);
            flash('success', $active ? 'Sector is now visible on the site.' : 'Sector hidden from the site.');
        }

        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('sectors'));
    }

    public function bulk(): void
    {
        $this->authorize('editor');
        $this->handleBulkRequest('sector', function (string $action, int $id): bool {
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
        }, 'sectors');
    }

    public function destroy(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', 'sector', $id);
        flash('success', 'Sector deleted.');
        redirect(admin_url('sectors'));
    }

    /** @return array<string, mixed> */
    private function payloadFromRequest(): array
    {
        return [
            'icon' => trim($_POST['icon'] ?? ''),
            'color' => trim($_POST['color'] ?? ''),
            'media_id' => (($_POST['media_id'] ?? '') !== '' ? (int) $_POST['media_id'] : null),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $this->parseTranslations($_POST, ['name', 'slug', 'overview', 'services_text', 'meta_title', 'meta_description']),
        ];
    }
}

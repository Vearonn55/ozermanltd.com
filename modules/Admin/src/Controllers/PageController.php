<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\PageRepository;
use Admin\Services\QrLandingSeeder;
use App\Infrastructure\Database;

class PageController extends BaseAdminController
{
    private PageRepository $repo;

    public function __construct()
    {
        $this->repo = new PageRepository();
    }

    public function index(): void
    {
        $this->authorize('editor');
        $params = $this->listParams();
        $result = $this->repo->paginate($params['q'], $params['status'], $params['page']);

        $missingQr = [];
        $pdo = Database::connection(true);
        if ($pdo !== null) {
            $missingQr = (new QrLandingSeeder($this->repo, $pdo))->missingSlugs();
        }

        $this->render('pages.index', [
            'pageTitle' => 'Pages',
            'items' => $result['items'],
            'total' => $result['total'],
            'pages' => $result['pages'],
            'page' => $result['page'],
            'q' => $params['q'],
            'statusFilter' => $params['status'],
            'missingQrLandings' => $missingQr,
        ]);
    }

    public function seedQrLandings(): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $pdo = Database::connection(true);
        if ($pdo === null) {
            flash('error', 'Database connection failed.');
            redirect(admin_url('pages'));
        }

        $messages = (new QrLandingSeeder($this->repo, $pdo))->run();
        $this->logActivity('seed_qr_landings', 'page', null, ['messages' => $messages]);
        flash('success', implode(' · ', $messages));
        redirect(admin_url('pages'));
    }

    public function create(): void
    {
        $this->authorize('editor');
        $this->render('pages.form', [
            'pageTitle' => 'New Page',
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

        $this->recordRevision('page', $id, $data);
        $this->logActivity('create', 'page', $id);
        flash('success', 'Page created successfully.');
        redirect(admin_url('pages'));
    }

    public function edit(int $id): void
    {
        $this->authorize('editor');
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Page not found.');
            redirect(admin_url('pages'));
        }

        $context = $this->withRevisions('page', $id, $item);

        $this->render('pages.form', [
            'pageTitle' => 'Edit Page',
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
            flash('error', 'Page not found.');
            redirect(admin_url('pages'));
        }

        $data = $this->payloadFromRequest();
        $this->repo->update($id, $data);

        $this->recordRevision('page', $id, $data);
        $this->logActivity('update', 'page', $id);
        flash('success', 'Page updated successfully.');
        redirect(admin_url('pages'));
    }

    public function autosave(int $id): void
    {
        $this->authorize('editor');

        if (!$this->repo->find($id)) {
            http_response_code(404);
            exit;
        }

        $this->handleAutosave('page', $id, $this->payloadFromRequest());
    }

    public function toggleStatus(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $item = $this->repo->find($id);
        if ($item) {
            $newStatus = ($item['status'] ?? 'draft') === 'published' ? 'draft' : 'published';
            $this->repo->setStatus($id, $newStatus);
            $this->logActivity('status_' . $newStatus, 'page', $id);
            flash('success', $newStatus === 'published' ? 'Page published.' : 'Page set to draft.');
        }

        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('pages'));
    }

    public function bulk(): void
    {
        $this->authorize('editor');
        $this->handleBulkRequest('page', function (string $action, int $id): bool {
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
        }, 'pages');
    }

    public function destroy(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', 'page', $id);
        flash('success', 'Page deleted.');
        redirect(admin_url('pages'));
    }

    /** @return array<string, mixed> */
    private function payloadFromRequest(): array
    {
        $slug = trim((string) ($_POST['slug'] ?? ''));
        $customPath = trim((string) ($_POST['custom_path'] ?? ''), '/');
        if ($customPath === '') {
            $customPath = $slug;
        }

        return [
            'slug' => $slug,
            'custom_path' => $customPath !== '' ? $customPath : null,
            'template' => $_POST['template'] ?? 'default',
            'embed_mode' => ($_POST['embed_mode'] ?? 'site') === 'blank' ? 'blank' : 'site',
            'status' => $_POST['status'] ?? 'draft',
            'show_in_nav' => isset($_POST['show_in_nav']) ? 1 : 0,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'translations' => $this->parseTranslations($_POST, [
                'title', 'content', 'excerpt', 'meta_title', 'meta_description',
                'html_embed', 'css_embed', 'js_embed', 'php_embed',
            ]),
        ];
    }
}

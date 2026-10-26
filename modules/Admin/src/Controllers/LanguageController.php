<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\LanguageRepository;
use PDOException;

class LanguageController extends BaseAdminController
{
    private LanguageRepository $repo;

    public function __construct()
    {
        $this->repo = new LanguageRepository();
    }

    public function index(): void
    {
        $this->authorize('content_manager');
        $this->render('locales.index', [
            'pageTitle' => 'Languages',
            'items' => $this->repo->all(),
        ]);
    }

    public function create(): void
    {
        $this->authorize('content_manager');
        $this->render('locales.form', [
            'pageTitle' => 'New language',
            'item' => null,
        ]);
    }

    public function store(): void
    {
        $this->authorize('content_manager');
        $this->verifyCsrf();

        $data = $this->payload();
        if ($data === null) {
            redirect(admin_url('locales/create'));
        }

        try {
            $id = $this->repo->create($data);
        } catch (PDOException $e) {
            flash('error', $this->dbMessage($e));
            redirect(admin_url('locales/create'));
        }

        $this->logActivity('create', 'language', $id);
        flash('success', 'Language added.');
        redirect(admin_url('locales'));
    }

    public function edit(int $id): void
    {
        $this->authorize('content_manager');
        $item = $this->repo->find($id);
        if ($item === null) {
            flash('error', 'Language not found.');
            redirect(admin_url('locales'));
        }

        $this->render('locales.form', [
            'pageTitle' => 'Edit language',
            'item' => $item,
        ]);
    }

    public function update(int $id): void
    {
        $this->authorize('content_manager');
        $this->verifyCsrf();

        $data = $this->payload();
        if ($data === null) {
            redirect(admin_url('locales/' . $id . '/edit'));
        }

        try {
            $error = $this->repo->update($id, $data);
        } catch (PDOException $e) {
            flash('error', $this->dbMessage($e));
            redirect(admin_url('locales/' . $id . '/edit'));
        }

        if ($error !== null) {
            flash('error', $error);
            redirect(admin_url('locales/' . $id . '/edit'));
        }

        $this->logActivity('update', 'language', $id);
        flash('success', 'Language updated.');
        redirect(admin_url('locales'));
    }

    public function toggleStatus(int $id): void
    {
        $this->authorize('content_manager');
        $this->verifyCsrf();

        $item = $this->repo->find($id);
        if ($item === null) {
            flash('error', 'Language not found.');
            redirect(admin_url('locales'));
        }

        $error = $this->repo->setActive($id, (int) $item['is_active'] !== 1);
        if ($error !== null) {
            flash('error', $error);
            redirect(admin_url('locales'));
        }

        $this->logActivity('update', 'language', $id);
        flash('success', (int) $item['is_active'] === 1 ? 'Language disabled.' : 'Language enabled.');
        redirect(admin_url('locales'));
    }

    public function destroy(int $id): void
    {
        $this->authorize('content_manager');
        $this->verifyCsrf();

        $error = $this->repo->delete($id);
        if ($error !== null) {
            flash('error', $error);
            redirect(admin_url('locales'));
        }

        $this->logActivity('delete', 'language', $id);
        flash('success', 'Language removed. Its translations were removed with it.');
        redirect(admin_url('locales'));
    }

    /** @return array{code: string, name: string, dir: string, sort_order: int, is_active: int, is_default: int}|null */
    private function payload(): ?array
    {
        $code = strtolower(trim((string) ($_POST['code'] ?? '')));
        $name = trim((string) ($_POST['name'] ?? ''));
        $dir = ($_POST['dir'] ?? 'ltr') === 'rtl' ? 'rtl' : 'ltr';

        if (preg_match('/^[a-z]{2,5}$/', $code) !== 1) {
            flash('error', 'Use a language code of 2 to 5 letters, such as de or fr.');
            return null;
        }
        if ($name === '') {
            flash('error', 'A language name is required.');
            return null;
        }

        return [
            'code' => $code,
            'name' => $name,
            'dir' => $dir,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'is_default' => isset($_POST['is_default']) ? 1 : 0,
        ];
    }

    private function dbMessage(PDOException $e): string
    {
        if ((string) $e->getCode() === '23000') {
            return 'That language code is already in use.';
        }

        return 'The language could not be saved.';
    }
}

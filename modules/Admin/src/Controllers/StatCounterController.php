<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\StatCounterRepository;

class StatCounterController extends BaseAdminController
{
    private StatCounterRepository $repo;

    public function __construct()
    {
        $this->repo = new StatCounterRepository();
    }

    public function index(): void
    {
        $this->authorize('editor');
        $this->render('stats.index', [
            'pageTitle' => 'Stat Counters',
            'items' => $this->repo->all(),
        ]);
    }

    public function create(): void
    {
        $this->authorize('editor');
        $this->render('stats.form', [
            'pageTitle' => 'New Stat Counter',
            'item' => null,
            'languages' => $this->repo->languages(),
        ]);
    }

    public function store(): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $id = $this->repo->create($this->payloadFromRequest());
        $this->logActivity('create', 'stat_counter', $id);
        flash('success', 'Stat counter created.');
        redirect(admin_url('stats'));
    }

    public function edit(int $id): void
    {
        $this->authorize('editor');
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Stat counter not found.');
            redirect(admin_url('stats'));
        }

        $this->render('stats.form', [
            'pageTitle' => 'Edit Stat Counter',
            'item' => $item,
            'languages' => $this->repo->languages(),
        ]);
    }

    public function update(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', 'Stat counter not found.');
            redirect(admin_url('stats'));
        }

        $this->repo->update($id, $this->payloadFromRequest());
        $this->logActivity('update', 'stat_counter', $id);
        flash('success', 'Stat counter updated.');
        redirect(admin_url('stats'));
    }

    public function destroy(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', 'stat_counter', $id);
        flash('success', 'Stat counter deleted.');
        redirect(admin_url('stats'));
    }

    /** @return array<string, mixed> */
    private function payloadFromRequest(): array
    {
        return [
            'value' => trim($_POST['value'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'translations' => $this->parseTranslations($_POST, ['label', 'suffix']),
        ];
    }
}

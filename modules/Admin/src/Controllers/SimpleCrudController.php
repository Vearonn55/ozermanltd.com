<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\TranslatedEntityRepository;

/**
 * Shared CRUD flow for resources backed by TranslatedEntityRepository.
 */
abstract class SimpleCrudController extends BaseAdminController
{
    protected TranslatedEntityRepository $repo;

    /** Entity type used in the activity log, e.g. 'store'. */
    protected string $entityType;

    /** Admin route path, e.g. 'stores'. */
    protected string $routePath;

    /** View directory under modules/Admin/views, e.g. 'stores'. */
    protected string $viewDir;

    /** Human labels. */
    protected string $singular = 'Item';
    protected string $plural = 'Items';

    /** @return array<string, mixed> payload passed to the repository */
    abstract protected function payloadFromRequest(): array;

    /** Extra data made available to the form view. */
    protected function formData(): array
    {
        return [];
    }

    /** Extra data made available to the index view. */
    protected function indexData(): array
    {
        return [];
    }

    public function index(): void
    {
        $this->authorize('editor');
        $this->render($this->viewDir . '.index', [
            'pageTitle' => $this->plural,
            'items' => $this->repo->all(),
        ] + $this->indexData());
    }

    public function create(): void
    {
        $this->authorize('editor');
        $this->render($this->viewDir . '.form', [
            'pageTitle' => 'New ' . $this->singular,
            'item' => null,
            'languages' => $this->repo->languages(),
        ] + $this->formData());
    }

    public function store(): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        try {
            $id = $this->repo->create($this->payloadFromRequest());
        } catch (\PDOException $e) {
            flash('error', $this->friendlyDbError($e));
            redirect($_SERVER['HTTP_REFERER'] ?? admin_url($this->routePath . '/create'));
        }

        $this->logActivity('create', $this->entityType, $id);
        flash('success', $this->singular . ' created.');
        redirect(admin_url($this->routePath));
    }

    public function edit(int $id): void
    {
        $this->authorize('editor');
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', $this->singular . ' not found.');
            redirect(admin_url($this->routePath));
        }

        $this->render($this->viewDir . '.form', [
            'pageTitle' => 'Edit ' . $this->singular,
            'item' => $item,
            'languages' => $this->repo->languages(),
        ] + $this->formData());
    }

    public function update(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', $this->singular . ' not found.');
            redirect(admin_url($this->routePath));
        }

        try {
            $this->repo->update($id, $this->payloadFromRequest());
        } catch (\PDOException $e) {
            flash('error', $this->friendlyDbError($e));
            redirect($_SERVER['HTTP_REFERER'] ?? admin_url($this->routePath . '/' . $id . '/edit'));
        }

        $this->logActivity('update', $this->entityType, $id);
        flash('success', $this->singular . ' updated.');
        redirect(admin_url($this->routePath));
    }

    protected function friendlyDbError(\PDOException $e): string
    {
        if ((string) $e->getCode() === '23000' || str_contains($e->getMessage(), '1062')) {
            return 'A record with that slug or unique value already exists. Please choose another.';
        }

        return 'Could not save ' . strtolower($this->singular) . '. Please check your input and try again.';
    }

    public function toggleStatus(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $item = $this->repo->find($id);
        if ($item && array_key_exists('is_active', $item)) {
            $active = !((int) $item['is_active'] === 1);
            $this->repo->setActive($id, $active);
            $this->logActivity($active ? 'activate' : 'deactivate', $this->entityType, $id);
            flash('success', $active ? $this->singular . ' is now visible.' : $this->singular . ' hidden.');
        }

        redirect($_SERVER['HTTP_REFERER'] ?? admin_url($this->routePath));
    }

    public function bulk(): void
    {
        $this->authorize('editor');
        $this->handleBulkRequest($this->entityType, function (string $action, int $id): bool {
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
        }, $this->routePath);
    }

    public function destroy(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', $this->entityType, $id);
        flash('success', $this->singular . ' deleted.');
        redirect(admin_url($this->routePath));
    }
}

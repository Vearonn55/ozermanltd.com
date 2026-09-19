<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\MenuRepository;

class MenuController extends BaseAdminController
{
    private MenuRepository $repo;

    public function __construct()
    {
        $this->repo = new MenuRepository();
    }

    public function index(): void
    {
        $this->authorize('content_manager');
        $this->render('menus.index', [
            'pageTitle' => 'Menus',
            'items' => $this->repo->all(),
            'locationLabels' => MenuRepository::LOCATION_LABELS,
        ]);
    }

    /** Menus are fixed per location; creating and deleting are not supported. */
    public function create(): void
    {
        redirect(admin_url('menus'));
    }

    public function store(): void
    {
        redirect(admin_url('menus'));
    }

    public function destroy(int $id): void
    {
        redirect(admin_url('menus'));
    }

    public function edit(int $id): void
    {
        $this->authorize('content_manager');
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Menu not found.');
            redirect(admin_url('menus'));
        }

        $this->render('menus.form', [
            'pageTitle' => 'Edit Menu — ' . (MenuRepository::LOCATION_LABELS[$item['location']] ?? $item['location']),
            'item' => $item,
            'languages' => $this->repo->languages(),
            'locationLabels' => MenuRepository::LOCATION_LABELS,
        ]);
    }

    public function update(int $id): void
    {
        $this->authorize('content_manager');
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', 'Menu not found.');
            redirect(admin_url('menus'));
        }

        $titles = [];
        foreach ((array) ($_POST['titles'] ?? []) as $code => $title) {
            $titles[(string) $code] = trim((string) $title);
        }

        $items = [];
        foreach ((array) ($_POST['items'] ?? []) as $item) {
            if (!is_array($item)) {
                continue;
            }
            $labels = [];
            foreach ((array) ($item['labels'] ?? []) as $code => $label) {
                $labels[(string) $code] = trim((string) $label);
            }
            $items[] = [
                'url' => trim((string) ($item['url'] ?? '')),
                'target' => ($item['target'] ?? '_self') === '_blank' ? '_blank' : '_self',
                'sort_order' => (int) ($item['sort_order'] ?? 0),
                'is_active' => isset($item['is_active']) ? 1 : 0,
                'labels' => $labels,
            ];
        }

        $this->repo->save($id, $titles, $items);
        $this->logActivity('update', 'menu', $id);
        flash('success', 'Menu updated. Changes are live on the site.');
        redirect(admin_url('menus/' . $id . '/edit'));
    }
}

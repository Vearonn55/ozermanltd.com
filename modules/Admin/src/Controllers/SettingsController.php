<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\SettingsRepository;

class SettingsController extends BaseAdminController
{
    private SettingsRepository $repo;

    public function __construct()
    {
        $this->repo = new SettingsRepository();
    }

    public function index(): void
    {
        $this->authorize('content_manager');

        // Ensure ops flags exist for every host install.
        if ($this->repo->get('system', 'maintenance_mode') === null) {
            $this->repo->set('system', 'maintenance_mode', '0');
        }

        $this->render('settings.index', [
            'pageTitle' => 'Settings',
            'grouped' => $this->repo->grouped(),
        ]);
    }

    public function update(): void
    {
        $user = $this->authorize('content_manager');
        $this->verifyCsrf();

        $values = $_POST['settings'] ?? [];
        if (!is_array($values)) {
            flash('error', 'Invalid settings payload.');
            redirect(admin_url('settings'));
        }

        $items = [];
        foreach ($values as $id => $value) {
            $items[] = [
                'id' => (int) $id,
                'value' => is_string($value) ? trim($value) : (string) $value,
            ];
        }

        // Ensure maintenance / feature flag keys exist for ops.
        if (isset($_POST['maintenance_mode'])) {
            $this->repo->set('system', 'maintenance_mode', $_POST['maintenance_mode'] === '1' ? '1' : '0', (int) $user['id']);
        }

        $this->repo->updateMany($items, (int) $user['id']);
        $this->logActivity('update', 'settings', null);
        flash('success', 'Settings saved.');
        redirect(admin_url('settings'));
    }
}

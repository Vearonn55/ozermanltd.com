<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\ActivityRepository;

class ActivityController extends BaseAdminController
{
    public function index(): void
    {
        $this->authorize('content_manager');
        $repo = new ActivityRepository();
        $q = trim((string) ($_GET['q'] ?? ''));
        $action = trim((string) ($_GET['action'] ?? ''));

        $this->render('activity.index', [
            'pageTitle' => 'Audit Log',
            'items' => $repo->search($q !== '' ? $q : null, $action !== '' ? $action : null),
            'actions' => $repo->distinctActions(),
            'q' => $q,
            'actionFilter' => $action,
        ]);
    }
}

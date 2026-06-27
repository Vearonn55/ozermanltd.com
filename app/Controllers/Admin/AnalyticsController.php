<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Repositories\Admin\AnalyticsRepository;

class AnalyticsController extends BaseAdminController
{
    public function index(): void
    {
        $this->requireAuth();
        $repo = new AnalyticsRepository();

        $this->render('analytics.index', [
            'pageTitle' => 'Analytics',
            'summary' => $repo->summary(),
            'recentEvents' => $repo->recentEvents(30),
            'topPages' => $repo->topPages(10),
            'consent' => $repo->consentBreakdown(),
            'eventsByDay' => $repo->eventsByDay(14),
        ]);
    }
}

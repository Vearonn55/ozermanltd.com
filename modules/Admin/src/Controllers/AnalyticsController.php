<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\AnalyticsRepository;

class AnalyticsController extends BaseAdminController
{
    public function index(): void
    {
        $this->authorize('editor');
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

    public function export(): void
    {
        $this->authorize('editor');
        $repo = new AnalyticsRepository();
        $rows = [];
        foreach ($repo->recentEvents(500) as $event) {
            $rows[] = [
                $event['id'] ?? '',
                $event['event_name'] ?? '',
                $event['page_path'] ?? '',
                $event['locale'] ?? '',
                $event['visitor_uuid'] ?? '',
                $event['created_at'] ?? '',
            ];
        }

        $this->exportCsv('analytics-events.csv', ['ID', 'Event', 'Path', 'Locale', 'Visitor', 'Created'], $rows);
    }
}

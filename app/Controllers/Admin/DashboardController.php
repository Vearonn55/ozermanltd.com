<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Repositories\Admin\AnalyticsRepository;
use App\Repositories\Admin\ContactRepository;
use App\Repositories\Admin\NewsRepository;
use App\Repositories\Admin\PageRepository;
use App\Repositories\Admin\ProjectRepository;
use App\Repositories\Admin\SectorRepository;

class DashboardController extends BaseAdminController
{
    public function index(): void
    {
        $this->requireAuth();

        $pages = new PageRepository();
        $news = new NewsRepository();
        $projects = new ProjectRepository();
        $sectors = new SectorRepository();
        $contacts = new ContactRepository();
        $analytics = new AnalyticsRepository();

        $this->render('dashboard.index', [
            'pageTitle' => 'Dashboard',
            'siteUsesDatabase' => content()->usingDatabase(),
            'counts' => [
                'pages' => $pages->count(),
                'news' => $news->count(),
                'news_published' => $news->countPublished(),
                'projects' => $projects->count(),
                'sectors' => $sectors->count(),
                'contacts_new' => $contacts->countNew(),
                'contacts_total' => $contacts->count(),
            ],
            'analytics' => $analytics->summary(),
            'recentContacts' => array_slice($contacts->all(), 0, 5),
        ]);
    }
}

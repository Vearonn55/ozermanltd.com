<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\AnalyticsRepository;
use Admin\Repositories\ContactRepository;
use Admin\Repositories\NewsRepository;
use Admin\Repositories\PageRepository;
use Admin\Repositories\ProjectRepository;
use Admin\Repositories\SectorRepository;

class DashboardController extends BaseAdminController
{
    public function index(): void
    {
        $this->authorize('editor');

        try {
            $pages = new PageRepository();
            $news = new NewsRepository();
            $projects = new ProjectRepository();
            $sectors = new SectorRepository();
            $contacts = new ContactRepository();
            $analytics = new AnalyticsRepository();

            $draftNews = $news->paginate('', 'draft', 1, 5)['items'];
            $draftPages = $pages->paginate('', 'draft', 1, 5)['items'];

            $drafts = [];
            foreach ($draftNews as $item) {
                $drafts[] = [
                    'type' => 'News',
                    'title' => $item['title'] ?? '(untitled)',
                    'href' => 'news/' . $item['id'] . '/edit',
                    'updated' => $item['updated_at'] ?? $item['publish_date'] ?? null,
                ];
            }
            foreach ($draftPages as $item) {
                $drafts[] = [
                    'type' => 'Page',
                    'title' => $item['title'] ?? $item['slug'] ?? '(untitled)',
                    'href' => 'pages/' . $item['id'] . '/edit',
                    'updated' => $item['updated_at'] ?? null,
                ];
            }

            usort($drafts, static function (array $a, array $b): int {
                return strcmp((string) ($b['updated'] ?? ''), (string) ($a['updated'] ?? ''));
            });
            $drafts = array_slice($drafts, 0, 8);

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
                'drafts' => $drafts,
            ]);
        } catch (\Throwable $e) {
            throw new \RuntimeException(
                'Dashboard failed: ' . $e->getMessage()
                . ' — Import schema.sql + seed.sql (+ analytics_schema.sql) into ozermanl_MAIN via phpMyAdmin.',
                0,
                $e
            );
        }
    }
}

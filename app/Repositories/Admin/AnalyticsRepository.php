<?php

declare(strict_types=1);

namespace App\Repositories\Admin;

use App\Infrastructure\AnalyticsStore;
use PDO;

class AnalyticsRepository extends BaseAdminRepository
{
    public function summary(): array
    {
        $store = new AnalyticsStore();
        $stats = $store->stats();

        $stats['contacts'] = (int) $this->pdo->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
        $stats['page_views'] = (int) $this->pdo->query(
            "SELECT COUNT(*) FROM visitor_events WHERE event_name = 'page_view'"
        )->fetchColumn();

        return $stats;
    }

    public function recentEvents(int $limit = 50): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, visitor_uuid, event_name, page_path, page_url, locale, created_at
             FROM visitor_events ORDER BY created_at DESC LIMIT :limit'
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function topPages(int $limit = 10): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT page_path, COUNT(*) AS views
             FROM visitor_events
             WHERE event_name = 'page_view' AND page_path IS NOT NULL AND page_path != ''
             GROUP BY page_path
             ORDER BY views DESC
             LIMIT :limit"
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consentBreakdown(): array
    {
        $stmt = $this->pdo->query(
            'SELECT
                SUM(analytics) AS analytics_yes,
                SUM(CASE WHEN analytics = 0 THEN 1 ELSE 0 END) AS analytics_no,
                SUM(marketing) AS marketing_yes,
                SUM(CASE WHEN marketing = 0 THEN 1 ELSE 0 END) AS marketing_no,
                COUNT(*) AS total
             FROM consent_records'
        );

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function eventsByDay(int $days = 14): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT DATE(created_at) AS day, COUNT(*) AS events
             FROM visitor_events
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
             GROUP BY DATE(created_at)
             ORDER BY day ASC"
        );
        $stmt->bindValue('days', $days, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Infrastructure\AnalyticsStore;
use App\Services\Analytics\ConsentService;
use App\Services\Analytics\EventCollector;
use App\Services\Analytics\ExternalSyncService;

class AnalyticsController
{
    public function consent(): void
    {
        $input = $this->jsonInput();
        try {
            $record = (new ConsentService())->record($input);
            $this->json(['ok' => true, 'consent' => $record]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'error' => $e->getMessage()], 422);
        }
    }

    public function events(): void
    {
        $input = $this->jsonInput();
        try {
            $result = (new EventCollector())->collect($input);
            $this->json(['ok' => true] + $result);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'error' => $e->getMessage()], 422);
        }
    }

    public function identify(): void
    {
        $input = $this->jsonInput();
        try {
            $profile = (new EventCollector())->identify($input);
            $this->json(['ok' => true, 'profile' => $profile]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'error' => $e->getMessage()], 422);
        }
    }

    public function stats(): void
    {
        $stats = (new AnalyticsStore())->stats();
        $sync = new ExternalSyncService();
        $this->json([
            'ok' => true,
            'stats' => $stats,
            'external_sync_configured' => $sync->isConfigured(),
        ]);
    }

    private function jsonInput(): array
    {
        $raw = file_get_contents('php://input');
        $decoded = json_decode($raw ?: '{}', true);
        return is_array($decoded) ? $decoded : [];
    }

    private function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

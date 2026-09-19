<?php

declare(strict_types=1);

namespace App\Services\Analytics;

use App\Infrastructure\AnalyticsStore;

class ExternalSyncService
{
    private AnalyticsStore $store;

    public function __construct(?AnalyticsStore $store = null)
    {
        $this->store = $store ?? new AnalyticsStore();
    }

    public function queue(string $type, array $payload): array
    {
        $entry = $this->store->enqueueSync($type, [
            'source' => config('url'),
            'type' => $type,
            'payload' => $payload,
            'sent_at' => gmdate('c'),
        ]);

        if ($this->isConfigured()) {
            $this->processPending(1);
        }

        return $entry;
    }

    public function processPending(int $limit = 50): array
    {
        if (!$this->isConfigured()) {
            return [
                'processed' => 0,
                'synced' => 0,
                'failed' => 0,
                'message' => 'External API URL not configured. Data queued locally.',
            ];
        }

        $items = $this->store->pendingSyncItems($limit);
        $synced = 0;
        $failed = 0;

        foreach ($items as $item) {
            $result = $this->send($item['payload']);
            if ($result['ok']) {
                $this->store->markSynced($item['id']);
                $synced++;
            } else {
                $this->store->markSynced($item['id'], $result['error']);
                $failed++;
            }
        }

        return [
            'processed' => count($items),
            'synced' => $synced,
            'failed' => $failed,
        ];
    }

    public function isConfigured(): bool
    {
        $config = analytics_config('external', []);
        return !empty($config['enabled']) && !empty($config['url']);
    }

    private function send(array $payload): array
    {
        $config = analytics_config('external', []);
        $url = rtrim((string) ($config['url'] ?? ''), '/') . (string) ($config['ingest_path'] ?? '/api/ingest');

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: OzermanAnalyticsSync/1.0',
        ];

        if (!empty($config['api_key'])) {
            $headers[] = 'Authorization: Bearer ' . $config['api_key'];
        }

        $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => (int) ($config['timeout'] ?? 10),
            ]);
            $response = curl_exec($ch);
            $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($response === false) {
                return ['ok' => false, 'error' => $error ?: 'Request failed'];
            }

            if ($status >= 200 && $status < 300) {
                return ['ok' => true];
            }

            return ['ok' => false, 'error' => 'HTTP ' . $status . ': ' . substr((string) $response, 0, 500)];
        }

        return ['ok' => false, 'error' => 'cURL not available'];
    }
}

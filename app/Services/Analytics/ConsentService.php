<?php

declare(strict_types=1);

namespace App\Services\Analytics;

use App\Infrastructure\AnalyticsStore;

class ConsentService
{
    private AnalyticsStore $store;
    private ExternalSyncService $sync;

    public function __construct(?AnalyticsStore $store = null, ?ExternalSyncService $sync = null)
    {
        $this->store = $store ?? new AnalyticsStore();
        $this->sync = $sync ?? new ExternalSyncService($this->store);
    }

    public function record(array $input): array
    {
        $payload = [
            'visitor_uuid' => $input['visitor_uuid'] ?? '',
            'policy_version' => $input['policy_version'] ?? analytics_config('policy_version'),
            'essential' => (bool) ($input['essential'] ?? true),
            'analytics' => (bool) ($input['analytics'] ?? false),
            'marketing' => (bool) ($input['marketing'] ?? false),
            'source' => $input['source'] ?? 'banner',
            'locale' => $input['locale'] ?? app_locale(),
            'ip_hash' => $this->hashIp($_SERVER['REMOTE_ADDR'] ?? null),
            'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
            'marketing_opt_in' => (bool) ($input['marketing'] ?? false),
            'analytics_opt_in' => (bool) ($input['analytics'] ?? false),
            'essential_opt_in' => (bool) ($input['essential'] ?? true),
        ];

        if ($payload['visitor_uuid'] === '') {
            throw new \InvalidArgumentException('visitor_uuid is required');
        }

        $record = $this->store->saveConsent($payload);
        $this->sync->queue('consent', $record);

        return $record;
    }

    private function hashIp(?string $ip): ?string
    {
        if ($ip === null || $ip === '') {
            return null;
        }

        return hash('sha256', $ip . '|' . (getenv('APP_KEY') ?: 'ozerman'));
    }
}

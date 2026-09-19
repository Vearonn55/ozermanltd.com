<?php

declare(strict_types=1);

namespace App\Services\Analytics;

use App\Infrastructure\AnalyticsStore;

class EventCollector
{
    private AnalyticsStore $store;
    private ExternalSyncService $sync;

    public function __construct(?AnalyticsStore $store = null, ?ExternalSyncService $sync = null)
    {
        $this->store = $store ?? new AnalyticsStore();
        $this->sync = $sync ?? new ExternalSyncService($this->store);
    }

    public function collect(array $input): array
    {
        $visitorUuid = (string) ($input['visitor_uuid'] ?? '');
        $events = $input['events'] ?? [];
        $consent = $input['consent'] ?? [];

        if ($visitorUuid === '') {
            throw new \InvalidArgumentException('visitor_uuid is required');
        }

        if (!($consent['analytics'] ?? false)) {
            return ['saved' => 0, 'skipped' => count($events), 'reason' => 'analytics_not_consented'];
        }

        $allowed = analytics_config('tracked_events', []);
        $prepared = [];
        $ipHash = $this->hashIp($_SERVER['REMOTE_ADDR'] ?? null);
        $userAgent = substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500);

        foreach ($events as $event) {
            $name = (string) ($event['name'] ?? $event['event_name'] ?? '');
            if ($name === '' || ($allowed !== [] && !in_array($name, $allowed, true))) {
                continue;
            }

            $prepared[] = [
                'visitor_uuid' => $visitorUuid,
                'event_name' => $name,
                'page_path' => $event['page_path'] ?? $event['path'] ?? null,
                'page_url' => $event['page_url'] ?? $event['url'] ?? null,
                'referrer' => $event['referrer'] ?? ($_SERVER['HTTP_REFERER'] ?? null),
                'locale' => $event['locale'] ?? ($input['locale'] ?? app_locale()),
                'properties' => is_array($event['properties'] ?? null) ? $event['properties'] : [],
                'timestamp' => $event['timestamp'] ?? gmdate('c'),
                'ip_hash' => $ipHash,
                'user_agent' => $userAgent,
            ];
        }

        if ($prepared === []) {
            return ['saved' => 0, 'skipped' => count($events)];
        }

        $saved = $this->store->saveEvents($prepared);
        $this->sync->queue('events', [
            'visitor_uuid' => $visitorUuid,
            'events' => $saved,
        ]);

        return ['saved' => count($saved), 'skipped' => count($events) - count($saved)];
    }

    public function identify(array $input): array
    {
        $visitorUuid = (string) ($input['visitor_uuid'] ?? '');
        if ($visitorUuid === '') {
            throw new \InvalidArgumentException('visitor_uuid is required');
        }

        $profile = $this->store->upsertVisitor([
            'visitor_uuid' => $visitorUuid,
            'name' => trim((string) ($input['name'] ?? '')) ?: null,
            'email' => trim((string) ($input['email'] ?? '')) ?: null,
            'phone' => trim((string) ($input['phone'] ?? '')) ?: null,
            'locale' => $input['locale'] ?? app_locale(),
            'marketing_opt_in' => (bool) ($input['marketing_opt_in'] ?? false),
            'analytics_opt_in' => (bool) ($input['analytics_opt_in'] ?? false),
            'essential_opt_in' => true,
            'policy_version' => $input['policy_version'] ?? analytics_config('policy_version'),
            'ip_hash' => $this->hashIp($_SERVER['REMOTE_ADDR'] ?? null),
            'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
        ]);

        $this->sync->queue('identify', $profile);

        if ($input['analytics_opt_in'] ?? false) {
            $this->collect([
                'visitor_uuid' => $visitorUuid,
                'consent' => ['analytics' => true],
                'events' => [[
                    'name' => 'identify',
                    'properties' => [
                        'has_name' => !empty($profile['name']),
                        'has_email' => !empty($profile['email']),
                        'marketing_opt_in' => (bool) ($input['marketing_opt_in'] ?? false),
                    ],
                ]],
            ]);
        }

        return $profile;
    }

    public function saveContact(array $input): array
    {
        $visitorUuid = (string) ($input['visitor_uuid'] ?? '');
        if ($visitorUuid === '') {
            throw new \InvalidArgumentException('visitor_uuid is required');
        }

        $contact = $this->store->saveContact([
            'visitor_uuid' => $visitorUuid,
            'name' => trim((string) ($input['name'] ?? '')),
            'email' => trim((string) ($input['email'] ?? '')),
            'phone' => trim((string) ($input['phone'] ?? '')) ?: null,
            'subject' => trim((string) ($input['subject'] ?? '')) ?: null,
            'message' => trim((string) ($input['message'] ?? '')),
            'locale' => $input['locale'] ?? app_locale(),
            'marketing_opt_in' => (bool) ($input['marketing_opt_in'] ?? false),
            'analytics_opt_in' => (bool) ($input['analytics_opt_in'] ?? false),
            'policy_version' => analytics_config('policy_version'),
            'ip_hash' => $this->hashIp($_SERVER['REMOTE_ADDR'] ?? null),
            'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
        ]);

        $this->sync->queue('contact', $contact);

        if ($input['analytics_opt_in'] ?? false) {
            $this->collect([
                'visitor_uuid' => $visitorUuid,
                'consent' => ['analytics' => true],
                'events' => [[
                    'name' => 'form_submit',
                    'properties' => [
                        'form' => 'contact',
                        'subject' => $contact['subject'] ?? null,
                    ],
                ]],
            ]);
        }

        return $contact;
    }

    private function hashIp(?string $ip): ?string
    {
        if ($ip === null || $ip === '') {
            return null;
        }

        return hash('sha256', $ip . '|' . (getenv('APP_KEY') ?: 'ozerman'));
    }
}

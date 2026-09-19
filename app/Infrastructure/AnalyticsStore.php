<?php

declare(strict_types=1);

namespace App\Infrastructure;

use PDO;

class AnalyticsStore
{
    private string $storageDir;

    public function __construct()
    {
        $this->storageDir = BASE_PATH . '/storage/analytics';
        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }
    }

    public function upsertVisitor(array $data): array
    {
        $pdo = Database::connection();
        if ($pdo !== null) {
            return $this->upsertVisitorDb($pdo, $data);
        }

        return $this->upsertVisitorFile($data);
    }

    public function saveConsent(array $data): array
    {
        $pdo = Database::connection();
        if ($pdo !== null) {
            return $this->saveConsentDb($pdo, $data);
        }

        return $this->saveConsentFile($data);
    }

    public function saveEvents(array $events): array
    {
        $pdo = Database::connection();
        if ($pdo !== null) {
            return $this->saveEventsDb($pdo, $events);
        }

        return $this->saveEventsFile($events);
    }

    public function saveContact(array $data): array
    {
        $pdo = Database::connection();
        if ($pdo !== null) {
            return $this->saveContactDb($pdo, $data);
        }

        return $this->saveContactFile($data);
    }

    public function enqueueSync(string $type, array $payload): array
    {
        $entry = [
            'id' => $this->uuid(),
            'payload_type' => $type,
            'payload' => $payload,
            'status' => 'pending',
            'attempts' => 0,
            'last_error' => null,
            'created_at' => gmdate('c'),
            'synced_at' => null,
        ];

        $pdo = Database::connection();
        if ($pdo !== null) {
            $stmt = $pdo->prepare(
                'INSERT INTO analytics_sync_queue (payload_type, payload_json, status, attempts, created_at)
                 VALUES (:payload_type, :payload_json, :status, 0, NOW())'
            );
            $stmt->execute([
                'payload_type' => $type,
                'payload_json' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'status' => 'pending',
            ]);
            $entry['id'] = (string) $pdo->lastInsertId();
            return $entry;
        }

        $queue = $this->readJson('sync_queue.json');
        $queue[] = $entry;
        $this->writeJson('sync_queue.json', $queue);

        return $entry;
    }

    public function pendingSyncItems(int $limit = 100): array
    {
        $pdo = Database::connection();
        if ($pdo !== null) {
            $stmt = $pdo->prepare(
                'SELECT id, payload_type, payload_json, attempts FROM analytics_sync_queue
                 WHERE status = :status ORDER BY id ASC LIMIT :limit'
            );
            $stmt->bindValue('status', 'pending');
            $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();

            return array_map(static function (array $row): array {
                return [
                    'id' => (string) $row['id'],
                    'payload_type' => $row['payload_type'],
                    'payload' => json_decode((string) $row['payload_json'], true) ?: [],
                    'attempts' => (int) $row['attempts'],
                ];
            }, $rows);
        }

        $queue = $this->readJson('sync_queue.json');
        return array_values(array_filter($queue, static fn(array $item): bool => ($item['status'] ?? '') === 'pending'));
    }

    public function markSynced(string $id, ?string $error = null): void
    {
        $pdo = Database::connection();
        if ($pdo !== null) {
            if ($error === null) {
                $stmt = $pdo->prepare(
                    'UPDATE analytics_sync_queue SET status = :status, synced_at = NOW(), last_error = NULL WHERE id = :id'
                );
                $stmt->execute(['status' => 'synced', 'id' => $id]);
                return;
            }

            $stmt = $pdo->prepare(
                'UPDATE analytics_sync_queue SET attempts = attempts + 1, last_error = :error WHERE id = :id'
            );
            $stmt->execute(['error' => $error, 'id' => $id]);
            return;
        }

        $queue = $this->readJson('sync_queue.json');
        foreach ($queue as &$item) {
            if ((string) ($item['id'] ?? '') !== $id) {
                continue;
            }
            if ($error === null) {
                $item['status'] = 'synced';
                $item['synced_at'] = gmdate('c');
                $item['last_error'] = null;
            } else {
                $item['attempts'] = (int) ($item['attempts'] ?? 0) + 1;
                $item['last_error'] = $error;
            }
        }
        unset($item);
        $this->writeJson('sync_queue.json', $queue);
    }

    public function stats(): array
    {
        $pdo = Database::connection();
        if ($pdo !== null) {
            return [
                'visitors' => (int) $pdo->query('SELECT COUNT(*) FROM visitor_profiles')->fetchColumn(),
                'events' => (int) $pdo->query('SELECT COUNT(*) FROM visitor_events')->fetchColumn(),
                'consents' => (int) $pdo->query('SELECT COUNT(*) FROM consent_records')->fetchColumn(),
                'queue_pending' => (int) $pdo->query("SELECT COUNT(*) FROM analytics_sync_queue WHERE status = 'pending'")->fetchColumn(),
            ];
        }

        return [
            'visitors' => count($this->readJson('visitors.json')),
            'events' => count($this->readJson('events.json')),
            'consents' => count($this->readJson('consents.json')),
            'contacts' => count($this->readJson('contacts.json')),
            'queue_pending' => count(array_filter(
                $this->readJson('sync_queue.json'),
                static fn(array $item): bool => ($item['status'] ?? '') === 'pending'
            )),
        ];
    }

    private function upsertVisitorDb(PDO $pdo, array $data): array
    {
        $stmt = $pdo->prepare(
            'INSERT INTO visitor_profiles
             (visitor_uuid, name, email, phone, locale, marketing_opt_in, analytics_opt_in, essential_opt_in, policy_version, last_ip_hash, user_agent)
             VALUES
             (:visitor_uuid, :name, :email, :phone, :locale, :marketing_opt_in, :analytics_opt_in, :essential_opt_in, :policy_version, :last_ip_hash, :user_agent)
             ON DUPLICATE KEY UPDATE
               name = COALESCE(VALUES(name), name),
               email = COALESCE(VALUES(email), email),
               phone = COALESCE(VALUES(phone), phone),
               locale = VALUES(locale),
               marketing_opt_in = VALUES(marketing_opt_in),
               analytics_opt_in = VALUES(analytics_opt_in),
               essential_opt_in = VALUES(essential_opt_in),
               policy_version = VALUES(policy_version),
               last_ip_hash = VALUES(last_ip_hash),
               user_agent = VALUES(user_agent),
               last_seen_at = CURRENT_TIMESTAMP'
        );
        $stmt->execute([
            'visitor_uuid' => $data['visitor_uuid'],
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'locale' => $data['locale'] ?? null,
            'marketing_opt_in' => (int) ($data['marketing_opt_in'] ?? 0),
            'analytics_opt_in' => (int) ($data['analytics_opt_in'] ?? 0),
            'essential_opt_in' => (int) ($data['essential_opt_in'] ?? 1),
            'policy_version' => $data['policy_version'] ?? analytics_config('policy_version'),
            'last_ip_hash' => $data['ip_hash'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
        ]);

        return $data;
    }

    private function saveConsentDb(PDO $pdo, array $data): array
    {
        $this->upsertVisitorDb($pdo, $data);
        $stmt = $pdo->prepare(
            'INSERT INTO consent_records
             (visitor_uuid, policy_version, essential, analytics, marketing, source, ip_hash, user_agent, locale)
             VALUES
             (:visitor_uuid, :policy_version, :essential, :analytics, :marketing, :source, :ip_hash, :user_agent, :locale)'
        );
        $stmt->execute([
            'visitor_uuid' => $data['visitor_uuid'],
            'policy_version' => $data['policy_version'],
            'essential' => (int) ($data['essential'] ?? 1),
            'analytics' => (int) ($data['analytics'] ?? 0),
            'marketing' => (int) ($data['marketing'] ?? 0),
            'source' => $data['source'] ?? 'banner',
            'ip_hash' => $data['ip_hash'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
            'locale' => $data['locale'] ?? null,
        ]);
        $data['id'] = (string) $pdo->lastInsertId();

        return $data;
    }

    private function saveEventsDb(PDO $pdo, array $events): array
    {
        $stmt = $pdo->prepare(
            'INSERT INTO visitor_events
             (visitor_uuid, event_name, page_path, page_url, referrer, locale, properties, created_at)
             VALUES
             (:visitor_uuid, :event_name, :page_path, :page_url, :referrer, :locale, :properties, :created_at)'
        );

        $saved = [];
        foreach ($events as $event) {
            $this->upsertVisitorDb($pdo, [
                'visitor_uuid' => $event['visitor_uuid'],
                'locale' => $event['locale'] ?? null,
                'analytics_opt_in' => 1,
                'essential_opt_in' => 1,
                'policy_version' => analytics_config('policy_version'),
                'ip_hash' => $event['ip_hash'] ?? null,
                'user_agent' => $event['user_agent'] ?? null,
            ]);

            $createdAt = $event['timestamp'] ?? gmdate('Y-m-d H:i:s');
            $stmt->execute([
                'visitor_uuid' => $event['visitor_uuid'],
                'event_name' => $event['event_name'],
                'page_path' => $event['page_path'] ?? null,
                'page_url' => $event['page_url'] ?? null,
                'referrer' => $event['referrer'] ?? null,
                'locale' => $event['locale'] ?? null,
                'properties' => json_encode($event['properties'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'created_at' => date('Y-m-d H:i:s', strtotime((string) $createdAt)),
            ]);
            $event['id'] = (string) $pdo->lastInsertId();
            $saved[] = $event;
        }

        return $saved;
    }

    private function saveContactDb(PDO $pdo, array $data): array
    {
        $this->upsertVisitorDb($pdo, $data);

        if ($this->tableExists($pdo, 'contact_messages')) {
            $stmt = $pdo->prepare(
                'INSERT INTO contact_messages (name, email, phone, subject, message, status, created_at)
                 VALUES (:name, :email, :phone, :subject, :message, :status, NOW())'
            );
            $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'subject' => $data['subject'] ?? null,
                'message' => $data['message'],
                'status' => 'received',
            ]);
            $data['contact_id'] = (string) $pdo->lastInsertId();
        }

        return $data;
    }

    private function upsertVisitorFile(array $data): array
    {
        $visitors = $this->readJson('visitors.json');
        $uuid = $data['visitor_uuid'];
        $existing = $visitors[$uuid] ?? [
            'visitor_uuid' => $uuid,
            'first_seen_at' => gmdate('c'),
        ];

        $visitors[$uuid] = array_merge($existing, array_filter($data, static fn($value) => $value !== null && $value !== ''), [
            'last_seen_at' => gmdate('c'),
        ]);

        $this->writeJson('visitors.json', $visitors);
        return $visitors[$uuid];
    }

    private function saveConsentFile(array $data): array
    {
        $this->upsertVisitorFile($data);
        $consents = $this->readJson('consents.json');
        $data['id'] = $this->uuid();
        $data['created_at'] = gmdate('c');
        $consents[] = $data;
        $this->writeJson('consents.json', $consents);

        return $data;
    }

    private function saveEventsFile(array $events): array
    {
        $stored = $this->readJson('events.json');
        foreach ($events as &$event) {
            $this->upsertVisitorFile([
                'visitor_uuid' => $event['visitor_uuid'],
                'locale' => $event['locale'] ?? null,
                'analytics_opt_in' => 1,
            ]);
            $event['id'] = $this->uuid();
            $event['created_at'] = $event['timestamp'] ?? gmdate('c');
            $stored[] = $event;
        }
        unset($event);
        $this->writeJson('events.json', $stored);

        return $events;
    }

    private function saveContactFile(array $data): array
    {
        $this->upsertVisitorFile($data);
        $contacts = $this->readJson('contacts.json');
        $data['id'] = $this->uuid();
        $data['created_at'] = gmdate('c');
        $contacts[] = $data;
        $this->writeJson('contacts.json', $contacts);

        return $data;
    }

    private function readJson(string $file): array
    {
        $path = $this->storageDir . '/' . $file;
        if (!file_exists($path)) {
            return str_ends_with($file, 'visitors.json') ? [] : [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);
        return is_array($decoded) ? $decoded : [];
    }

    private function writeJson(string $file, array $data): void
    {
        file_put_contents(
            $this->storageDir . '/' . $file,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    private function tableExists(PDO $pdo, string $table): bool
    {
        $stmt = $pdo->prepare('SHOW TABLES LIKE :table_name');
        $stmt->execute(['table_name' => $table]);
        return (bool) $stmt->fetchColumn();
    }

    private function uuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

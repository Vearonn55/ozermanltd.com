#!/usr/bin/env php
<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Infrastructure\AnalyticsStore;
use App\Services\Analytics\ExternalSyncService;

$sync = new ExternalSyncService();
$stats = (new AnalyticsStore())->stats();

echo "Ozerman Analytics Sync\n";
echo "External API configured: " . ($sync->isConfigured() ? 'yes' : 'no') . "\n";
echo "Pending queue items: {$stats['queue_pending']}\n\n";

$result = $sync->processPending(100);

echo "Processed: {$result['processed']}\n";
echo "Synced: {$result['synced']}\n";
echo "Failed: {$result['failed']}\n";

if (!empty($result['message'])) {
    echo "\n{$result['message']}\n";
}

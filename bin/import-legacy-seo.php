#!/usr/bin/env php
<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Services\Migration\SeoImporter;

$baseUrl = $argv[1] ?? null;

echo "Ozerman SEO Legacy Importer\n";
echo "Source: " . ($baseUrl ?: seo_config('legacy_import.base_url')) . "\n\n";

$report = (new SeoImporter())->importFromSite($baseUrl);

echo "Imported: {$report['imported']}\n";
echo "Skipped: {$report['skipped']}\n";

if ($report['errors'] !== []) {
    echo "\nErrors:\n";
    foreach ($report['errors'] as $error) {
        echo "  - {$error}\n";
    }
}

echo "\nFiles written:\n";
echo "  - storage/seo/meta.json\n";
echo "  - storage/seo/redirects.json\n";
echo "  - storage/seo/import.sql\n";

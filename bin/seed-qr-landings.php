<?php

declare(strict_types=1);

/**
 * Seed / update CMS pages for /qr and /catalogues.
 *
 * Prefer Admin → Pages → “Create QR & Catalogues pages” (no Terminal needed).
 *
 * CLI (if available):
 *   php bin/seed-qr-landings.php
 */

require dirname(__DIR__) . '/bootstrap.php';

use Admin\Repositories\PageRepository;
use Admin\Services\QrLandingSeeder;
use App\Infrastructure\Database;

$pdo = Database::connection(true);
if ($pdo === null) {
    fwrite(STDERR, "Cannot connect to MySQL. Set DB_* in .env (home directory .env on cPanel).\n");
    exit(1);
}

$messages = (new QrLandingSeeder(new PageRepository($pdo), $pdo))->run();
foreach ($messages as $line) {
    echo $line . "\n";
}

echo "\nDone. Edit in Admin → Pages → All Pages.\n";
echo "Upload PDFs in Media Library, then Insert media into the catalogues HTML embed.\n";

<?php

declare(strict_types=1);

/**
 * Diagnose DB connection on the server.
 * cPanel Terminal:  cd ~ && php bin/check-db.php
 */

require dirname(__DIR__) . '/bootstrap.php';

use App\Infrastructure\Database;

$host = env('DB_HOST', '(default 127.0.0.1)');
$db = env('DB_DATABASE', '(default ozermanltd)');
$user = env('DB_USERNAME', '(default root)');
$passSet = env('DB_PASSWORD', '') !== '';
$envFile = BASE_PATH . '/.env';

echo "BASE_PATH: " . BASE_PATH . "\n";
echo ".env path: {$envFile}\n";
echo ".env exists: " . (is_file($envFile) ? 'yes' : 'NO') . "\n";
echo "DB_HOST: {$host}\n";
echo "DB_DATABASE: {$db}\n";
echo "DB_USERNAME: {$user}\n";
echo "DB_PASSWORD set: " . ($passSet ? 'yes' : 'no') . "\n";
echo "PUBLIC_PATH: " . PUBLIC_PATH . "\n\n";

$pdo = Database::connection(true);
if ($pdo === null) {
    echo "RESULT: connection FAILED\n";
    if (Database::lastError() !== null) {
        echo "MySQL: " . Database::lastError() . "\n";
    }
    echo "Fix: edit {$envFile} — copy EXACT DB name/user from cPanel MySQL Databases (usually ozermanl_MAIN / ozermanl_SYSADMIN).\n";
    echo "Also: cPanel → MySQL Databases → Add User To Database → ALL PRIVILEGES.\n";
    exit(1);
}

echo "RESULT: connection OK\n";
try {
    $n = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    echo "users table rows: {$n}\n";
} catch (Throwable $e) {
    echo "Connected, but tables missing? Import database/install.sql. Error: " . $e->getMessage() . "\n";
    exit(1);
}

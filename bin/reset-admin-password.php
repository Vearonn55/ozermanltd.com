<?php

declare(strict_types=1);

/**
 * Create or update an admin password.
 * Usage: php bin/reset-admin-password.php admin@ozermanltd.com 'new-password'
 */

require dirname(__DIR__) . '/bootstrap.php';

use App\Infrastructure\Database;

$pdo = Database::connection(true);

if ($pdo === null) {
    fwrite(STDERR, "Error: Cannot connect to MySQL. Check .env DB_* settings.\n");
    exit(1);
}

$email = $argv[1] ?? '';
$password = $argv[2] ?? '';
if ($email === '' || $password === '') {
    fwrite(STDERR, "Usage: php bin/reset-admin-password.php email password\n");
    exit(1);
}
$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare('UPDATE users SET password_hash = :hash WHERE email = :email');
$stmt->execute(['hash' => $hash, 'email' => $email]);

if ($stmt->rowCount() === 0) {
    $insert = $pdo->prepare(
        'INSERT INTO users (name, email, password_hash, role, status) VALUES (:name, :email, :hash, :role, :status)'
    );
    $insert->execute([
        'name' => 'Admin User',
        'email' => $email,
        'hash' => $hash,
        'role' => 'super_admin',
        'status' => 'active',
    ]);
    echo "Created admin user: {$email}\n";
} else {
    echo "Password updated for: {$email}\n";
}

echo "Password: {$password}\n";

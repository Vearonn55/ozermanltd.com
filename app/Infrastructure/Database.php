<?php

declare(strict_types=1);

namespace App\Infrastructure;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;
    private static ?PDO $adminConnection = null;

    /**
     * @param bool $force When true, connect even if use_dummy_data is enabled (required for admin/auth).
     */
    public static function connection(bool $force = false): ?PDO
    {
        if (!$force && self::$connection !== null) {
            return self::$connection;
        }

        if ($force && self::$adminConnection !== null) {
            return self::$adminConnection;
        }

        $config = require CONFIG_PATH . '/database.php';

        if (!$force && !empty($config['use_dummy_data'])) {
            return null;
        }

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            $pdo = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException) {
            return null;
        }

        if ($force) {
            self::$adminConnection = $pdo;
        } else {
            self::$connection = $pdo;
        }

        return $pdo;
    }

    public static function reset(): void
    {
        self::$connection = null;
        self::$adminConnection = null;
    }
}

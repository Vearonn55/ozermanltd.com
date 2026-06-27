<?php

declare(strict_types=1);

namespace App\Repositories\Admin;

use App\Infrastructure\Database;
use PDO;
use RuntimeException;

abstract class BaseAdminRepository
{
    protected PDO $pdo;

    /** @var array<string, int>|null */
    private static ?array $languageIds = null;

    public function __construct(?PDO $pdo = null)
    {
        $pdo = $pdo ?? Database::connection(force: true);
        if ($pdo === null) {
            throw new RuntimeException('Database connection is required for admin operations.');
        }
        $this->pdo = $pdo;
    }

    /** @return array<int, array{code: string, name: string}> */
    public function languages(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, code, name FROM languages WHERE is_active = 1 ORDER BY sort_order, id'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function languageId(string $code): int
    {
        if (self::$languageIds === null) {
            self::$languageIds = [];
            foreach ($this->languages() as $lang) {
                self::$languageIds[$lang['code']] = (int) $lang['id'];
            }
        }

        if (!isset(self::$languageIds[$code])) {
            throw new RuntimeException('Unknown language: ' . $code);
        }

        return self::$languageIds[$code];
    }

    public function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text) ?? '';
        $text = preg_replace('/[\s-]+/', '-', $text) ?? '';

        return trim($text, '-');
    }

    /** @param array<string, mixed> $translations */
    protected function indexTranslationsByCode(array $translations, string $codeKey = 'code'): array
    {
        $indexed = [];
        foreach ($translations as $row) {
            $indexed[$row[$codeKey]] = $row;
        }

        return $indexed;
    }
}

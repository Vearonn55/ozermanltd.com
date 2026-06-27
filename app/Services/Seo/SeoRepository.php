<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Infrastructure\Database;
use PDO;

class SeoRepository
{
    private ?array $fileCache = null;

    public function find(string $entityType, string $entityKey, string $locale): ?array
    {
        $dbRecord = $this->findInDatabase($entityType, $entityKey, $locale);
        if ($dbRecord !== null) {
            return $dbRecord;
        }

        return $this->findInFile($entityType, $entityKey, $locale);
    }

    public function saveToFile(string $entityType, string $entityKey, string $locale, array $data): void
    {
        $store = $this->loadFileStore();
        $store[$this->cacheKey($entityType, $entityKey, $locale)] = array_merge(
            $store[$this->cacheKey($entityType, $entityKey, $locale)] ?? [],
            $data
        );

        $this->writeFileStore($store);
    }

    public function allFromFile(): array
    {
        return $this->loadFileStore();
    }

    public function replaceFileStore(array $records): void
    {
        $this->writeFileStore($records);
    }

    private function findInDatabase(string $entityType, string $entityKey, string $locale): ?array
    {
        $pdo = Database::connection();
        if ($pdo === null) {
            return null;
        }

        $languageId = $this->languageId($pdo, $locale);
        $entityId = $this->resolveEntityId($pdo, $entityType, $entityKey);

        if ($languageId === null || $entityId === null) {
            return null;
        }

        $stmt = $pdo->prepare(
            'SELECT meta_title, meta_description, og_title, og_description, canonical_url, robots, structured_data
             FROM seo_meta
             WHERE entity_type = :entity_type AND entity_id = :entity_id AND language_id = :language_id
             LIMIT 1'
        );
        $stmt->execute([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'language_id' => $languageId,
        ]);

        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }

        if (!empty($row['structured_data']) && is_string($row['structured_data'])) {
            $row['structured_data'] = json_decode($row['structured_data'], true) ?: [];
        }

        return $row;
    }

    private function findInFile(string $entityType, string $entityKey, string $locale): ?array
    {
        $store = $this->loadFileStore();
        return $store[$this->cacheKey($entityType, $entityKey, $locale)] ?? null;
    }

    private function loadFileStore(): array
    {
        if ($this->fileCache !== null) {
            return $this->fileCache;
        }

        $path = $this->storagePath();
        if (!file_exists($path)) {
            $this->fileCache = [];
            return $this->fileCache;
        }

        $decoded = json_decode((string) file_get_contents($path), true);
        $this->fileCache = is_array($decoded) ? $decoded : [];

        return $this->fileCache;
    }

    private function writeFileStore(array $records): void
    {
        $path = $this->storagePath();
        $dir = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($path, json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->fileCache = $records;
    }

    private function storagePath(): string
    {
        return BASE_PATH . '/storage/seo/meta.json';
    }

    private function cacheKey(string $entityType, string $entityKey, string $locale): string
    {
        return $entityType . ':' . $entityKey . ':' . $locale;
    }

    private function languageId(PDO $pdo, string $locale): ?int
    {
        $stmt = $pdo->prepare('SELECT id FROM languages WHERE code = :code LIMIT 1');
        $stmt->execute(['code' => $locale]);
        $row = $stmt->fetch();

        return $row ? (int) $row['id'] : null;
    }

    private function resolveEntityId(PDO $pdo, string $entityType, string $entityKey): ?int
    {
        if (ctype_digit($entityKey)) {
            return (int) $entityKey;
        }

        return match ($entityType) {
            'page' => $this->lookupId($pdo, 'SELECT id FROM pages WHERE slug = :slug LIMIT 1', $entityKey),
            'sector' => $this->lookupTranslationId($pdo, 'sector_translations', 'sector_id', $entityKey),
            'project' => $this->lookupTranslationId($pdo, 'project_translations', 'project_id', $entityKey),
            'news' => $this->lookupTranslationId($pdo, 'news_translations', 'news_id', $entityKey),
            default => null,
        };
    }

    private function lookupId(PDO $pdo, string $sql, string $slug): ?int
    {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();

        return $row ? (int) $row['id'] : null;
    }

    private function lookupTranslationId(PDO $pdo, string $table, string $idColumn, string $slug): ?int
    {
        $sql = sprintf('SELECT %s AS id FROM %s WHERE slug = :slug LIMIT 1', $idColumn, $table);
        return $this->lookupId($pdo, $sql, $slug);
    }
}

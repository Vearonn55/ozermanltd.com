<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

/**
 * Generic repository for the "entity table + per-language translation table" pattern
 * used across the schema (stores, brands, team_members, offices, content_blocks, ...).
 */
abstract class TranslatedEntityRepository extends BaseAdminRepository
{
    /** Entity table name. */
    protected string $table;

    /** Translation table name. */
    protected string $translationTable;

    /** FK column in the translation table pointing at the entity. */
    protected string $foreignKey;

    /** Entity columns editable via forms, column => default value. */
    protected array $entityFields = [];

    /** Translation columns (besides the FK and language_id). */
    protected array $translationFields = [];

    /** Translation column that must be non-empty for the row to be saved. */
    protected string $requiredTranslationField = 'name';

    /** Translation column shown in list views. */
    protected string $labelField = 'name';

    /** ORDER BY clause for lists. */
    protected string $orderBy = 'e.sort_order, e.id';

    public function all(): array
    {
        $stmt = $this->pdo->query(
            "SELECT e.*, t.{$this->labelField} AS label
             FROM {$this->table} e
             LEFT JOIN {$this->translationTable} t ON t.{$this->foreignKey} = e.id AND t.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             ORDER BY {$this->orderBy}"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $entity = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$entity) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            "SELECT t.*, l.code
             FROM {$this->translationTable} t
             INNER JOIN languages l ON l.id = t.language_id
             WHERE t.{$this->foreignKey} = :id"
        );
        $stmt->execute(['id' => $id]);
        $entity['translations'] = $this->indexTranslationsByCode($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $entity;
    }

    public function create(array $data): int
    {
        $columns = array_keys($this->entityFields);
        $placeholders = array_map(static fn(string $c): string => ':' . $c, $columns);

        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->table} (" . implode(', ', $columns) . ')
             VALUES (' . implode(', ', $placeholders) . ')'
        );
        $stmt->execute($this->entityParams($data));

        $id = (int) $this->pdo->lastInsertId();
        $this->saveTranslations($id, $data['translations'] ?? []);

        return $id;
    }

    public function update(int $id, array $data): void
    {
        $sets = array_map(static fn(string $c): string => "{$c} = :{$c}", array_keys($this->entityFields));

        $stmt = $this->pdo->prepare(
            "UPDATE {$this->table} SET " . implode(', ', $sets) . ' WHERE id = :id'
        );
        $stmt->execute($this->entityParams($data) + ['id' => $id]);

        $this->saveTranslations($id, $data['translations'] ?? []);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function setActive(int $id, bool $active): void
    {
        if (!array_key_exists('is_active', $this->entityFields)) {
            return;
        }
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET is_active = :active WHERE id = :id");
        $stmt->execute(['id' => $id, 'active' => $active ? 1 : 0]);
    }

    public function count(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }

    /** Hook for slug handling etc. before a translation row is written. */
    protected function prepareTranslation(array $fields): array
    {
        return $fields;
    }

    private function entityParams(array $data): array
    {
        $params = [];
        foreach ($this->entityFields as $column => $default) {
            $value = $data[$column] ?? $default;
            if ($value === '' && $default === null) {
                $value = null;
            }
            $params[$column] = $value;
        }

        return $params;
    }

    protected function saveTranslations(int $id, array $translations): void
    {
        foreach ($translations as $code => $fields) {
            if (empty($fields[$this->requiredTranslationField])) {
                continue;
            }

            $fields = $this->prepareTranslation($fields);
            $languageId = $this->languageId($code);

            $columns = [$this->foreignKey, 'language_id'];
            $params = [$this->foreignKey => $id, 'language_id' => $languageId];
            $updates = [];

            foreach ($this->translationFields as $column) {
                $columns[] = $column;
                $params[$column] = ($fields[$column] ?? '') !== '' ? $fields[$column] : null;
                $updates[] = "{$column} = VALUES({$column})";
            }

            $placeholders = array_map(static fn(string $c): string => ':' . $c, $columns);

            $stmt = $this->pdo->prepare(
                "INSERT INTO {$this->translationTable} (" . implode(', ', $columns) . ')
                 VALUES (' . implode(', ', $placeholders) . ')
                 ON DUPLICATE KEY UPDATE ' . implode(', ', $updates)
            );
            $stmt->execute($params);
        }
    }
}

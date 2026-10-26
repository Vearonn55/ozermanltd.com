<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDOException;

class LanguageRepository extends BaseAdminRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureDirColumn();
    }

    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, code, name, dir, is_default, is_active, sort_order
             FROM languages
             ORDER BY sort_order, id'
        );

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, code, name, dir, is_default, is_active, sort_order
             FROM languages WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /** @param array{code: string, name: string, dir: string, sort_order: int, is_active: int, is_default: int} $data */
    public function create(array $data): int
    {
        if ((int) $data['is_default'] === 1) {
            $this->pdo->exec('UPDATE languages SET is_default = 0');
            $data['is_active'] = 1;
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO languages (code, name, dir, is_default, is_active, sort_order)
             VALUES (:code, :name, :dir, :is_default, :is_active, :sort_order)'
        );
        $stmt->execute([
            'code' => $data['code'],
            'name' => $data['name'],
            'dir' => $data['dir'],
            'is_default' => (int) $data['is_default'],
            'is_active' => (int) $data['is_active'],
            'sort_order' => (int) $data['sort_order'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /** @param array{code: string, name: string, dir: string, sort_order: int, is_active: int, is_default: int} $data */
    public function update(int $id, array $data): ?string
    {
        $current = $this->find($id);
        if ($current === null) {
            return 'Language not found.';
        }

        $makingDefault = (int) $data['is_default'] === 1;
        if ((int) $current['is_default'] === 1 && !$makingDefault) {
            return 'Choose another language as the main one before changing this.';
        }
        if ((int) $current['is_default'] === 1 && (int) $data['is_active'] !== 1) {
            return 'The main language stays enabled.';
        }
        if ((int) $current['is_active'] === 1 && (int) $data['is_active'] !== 1 && $this->activeCount() <= 1) {
            return 'At least one language has to stay enabled.';
        }

        if ($makingDefault) {
            $this->pdo->exec('UPDATE languages SET is_default = 0');
            $data['is_active'] = 1;
        }

        $stmt = $this->pdo->prepare(
            'UPDATE languages
             SET code = :code, name = :name, dir = :dir, is_default = :is_default,
                 is_active = :is_active, sort_order = :sort_order
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'code' => $data['code'],
            'name' => $data['name'],
            'dir' => $data['dir'],
            'is_default' => $makingDefault ? 1 : (int) $current['is_default'],
            'is_active' => (int) $data['is_active'],
            'sort_order' => (int) $data['sort_order'],
        ]);

        return null;
    }

    public function setActive(int $id, bool $active): ?string
    {
        $current = $this->find($id);
        if ($current === null) {
            return 'Language not found.';
        }
        if (!$active && (int) $current['is_default'] === 1) {
            return 'The main language stays enabled.';
        }
        if (!$active && (int) $current['is_active'] === 1 && $this->activeCount() <= 1) {
            return 'At least one language has to stay enabled.';
        }

        $stmt = $this->pdo->prepare('UPDATE languages SET is_active = :active WHERE id = :id');
        $stmt->execute(['id' => $id, 'active' => $active ? 1 : 0]);

        return null;
    }

    public function delete(int $id): ?string
    {
        $current = $this->find($id);
        if ($current === null) {
            return 'Language not found.';
        }
        if ((int) $current['is_default'] === 1) {
            return 'The main language cannot be removed.';
        }
        if ((int) $current['is_active'] === 1 && $this->activeCount() <= 1) {
            return 'At least one language has to stay enabled.';
        }

        $stmt = $this->pdo->prepare('DELETE FROM languages WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return null;
    }

    public function activeCount(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM languages WHERE is_active = 1')->fetchColumn();
    }

    private function ensureDirColumn(): void
    {
        $column = $this->pdo->query("SHOW COLUMNS FROM languages LIKE 'dir'")->fetch();
        if ($column) {
            return;
        }

        $this->pdo->exec("ALTER TABLE languages ADD COLUMN dir VARCHAR(3) NOT NULL DEFAULT 'ltr' AFTER name");
        $this->pdo->exec("UPDATE languages SET dir = 'rtl' WHERE code = 'ar'");
    }
}

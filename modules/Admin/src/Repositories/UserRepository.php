<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class UserRepository extends BaseAdminRepository
{
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, name, email, role, status, last_login_at, created_at
             FROM users ORDER BY id ASC'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, email, role, status, last_login_at, created_at FROM users WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (name, email, password_hash, role, status)
             VALUES (:name, :email, :password_hash, :role, :status)'
        );
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
            'role' => $data['role'] ?? 'editor',
            'status' => $data['status'] ?? 'active',
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $sql = 'UPDATE users SET name = :name, email = :email, role = :role, status = :status';
        $params = [
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'status' => $data['status'],
        ];

        if (!empty($data['password_hash'])) {
            $sql .= ', password_hash = :password_hash';
            $params['password_hash'] = $data['password_hash'];
        }

        $sql .= ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    public function revokeSessions(int $userId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM user_sessions WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
    }

    public function delete(int $id): void
    {
        $this->revokeSessions($id);
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

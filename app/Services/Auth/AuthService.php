<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Infrastructure\Database;
use PDO;

class AuthService
{
    private const SESSION_USER_KEY = 'admin_user_id';
    private const SESSION_TOKEN_KEY = 'admin_session_token';

    /** Attempt login. Returns null on success, or an error message string. */
    public function attempt(string $email, string $password): ?string
    {
        $pdo = Database::connection(force: true);
        if ($pdo === null) {
            return 'Cannot connect to MySQL on ' . (getenv('DB_HOST') ?: '127.0.0.1') . '. '
                . 'Start MySQL (brew services start mysql@8.0) or run make db-docker-setup, then make db-setup.';
        }

        $stmt = $pdo->prepare(
            'SELECT id, name, email, password_hash, role, status FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return 'No account found with that email address.';
        }

        if ($user['status'] !== 'active') {
            return 'This account is inactive. Contact an administrator.';
        }

        if (!password_verify($password, $user['password_hash'])) {
            return 'Incorrect password.';
        }

        $this->startSession();
        session_regenerate_id(true);

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 86400 * 7);

        $sessionStmt = $pdo->prepare(
            'INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent, expires_at)
             VALUES (:user_id, :session_token, :ip_address, :user_agent, :expires_at)'
        );
        $sessionStmt->execute([
            'user_id' => $user['id'],
            'session_token' => $token,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'expires_at' => $expiresAt,
        ]);

        $pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id')
            ->execute(['id' => $user['id']]);

        $_SESSION[self::SESSION_USER_KEY] = (int) $user['id'];
        $_SESSION[self::SESSION_TOKEN_KEY] = $token;

        (new ActivityLogger())->log((int) $user['id'], 'login');

        return null;
    }

    public function logout(): void
    {
        $this->startSession();
        $userId = $_SESSION[self::SESSION_USER_KEY] ?? null;
        $token = $_SESSION[self::SESSION_TOKEN_KEY] ?? null;

        if ($token !== null) {
            $pdo = Database::connection(force: true);
            if ($pdo !== null) {
                $pdo->prepare('DELETE FROM user_sessions WHERE session_token = :token')
                    ->execute(['token' => $token]);
            }
        }

        if ($userId !== null) {
            (new ActivityLogger())->log((int) $userId, 'logout');
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function user(): ?array
    {
        $this->startSession();

        $userId = $_SESSION[self::SESSION_USER_KEY] ?? null;
        $token = $_SESSION[self::SESSION_TOKEN_KEY] ?? null;

        if ($userId === null || $token === null) {
            return null;
        }

        $pdo = Database::connection(force: true);
        if ($pdo === null) {
            return null;
        }

        $stmt = $pdo->prepare(
            'SELECT u.id, u.name, u.email, u.role, u.status, u.avatar
             FROM users u
             INNER JOIN user_sessions s ON s.user_id = u.id
             WHERE u.id = :user_id AND s.session_token = :token AND s.expires_at > NOW() AND u.status = :status
             LIMIT 1'
        );
        $stmt->execute([
            'user_id' => $userId,
            'token' => $token,
            'status' => 'active',
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
            ]);
        }
    }
}

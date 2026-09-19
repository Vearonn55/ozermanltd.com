<?php

declare(strict_types=1);

namespace Admin\Support;

/**
 * Simple file-based rate limiter for login and sensitive POSTs.
 */
class RateLimiter
{
    public function __construct(
        private readonly string $storageDir
    ) {
        if (!is_dir($this->storageDir)) {
            @mkdir($this->storageDir, 0755, true);
        }
    }

    public static function forAdmin(): self
    {
        $base = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 3);
        return new self($base . '/storage/admin/rate_limits');
    }

    public function tooManyAttempts(string $key, int $maxAttempts, int $windowSeconds): bool
    {
        $data = $this->read($key);
        $now = time();

        if ($data === null || ($now - $data['started_at']) > $windowSeconds) {
            return false;
        }

        return $data['attempts'] >= $maxAttempts;
    }

    public function hit(string $key, int $windowSeconds): void
    {
        $data = $this->read($key);
        $now = time();

        if ($data === null || ($now - $data['started_at']) > $windowSeconds) {
            $data = ['started_at' => $now, 'attempts' => 0];
        }

        $data['attempts']++;
        $this->write($key, $data);
    }

    public function clear(string $key): void
    {
        $file = $this->path($key);
        if (is_file($file)) {
            @unlink($file);
        }
    }

    public function retryAfterSeconds(string $key, int $windowSeconds): int
    {
        $data = $this->read($key);
        if ($data === null) {
            return 0;
        }

        $elapsed = time() - $data['started_at'];
        return max(0, $windowSeconds - $elapsed);
    }

    /** @return array{started_at: int, attempts: int}|null */
    private function read(string $key): ?array
    {
        $file = $this->path($key);
        if (!is_file($file)) {
            return null;
        }

        $raw = file_get_contents($file);
        if ($raw === false) {
            return null;
        }

        $data = json_decode($raw, true);
        if (!is_array($data) || !isset($data['started_at'], $data['attempts'])) {
            return null;
        }

        return [
            'started_at' => (int) $data['started_at'],
            'attempts' => (int) $data['attempts'],
        ];
    }

    /** @param array{started_at: int, attempts: int} $data */
    private function write(string $key, array $data): void
    {
        file_put_contents($this->path($key), json_encode($data), LOCK_EX);
    }

    private function path(string $key): string
    {
        return $this->storageDir . '/' . hash('sha256', $key) . '.json';
    }
}

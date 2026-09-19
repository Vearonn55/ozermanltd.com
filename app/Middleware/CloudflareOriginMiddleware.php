<?php

declare(strict_types=1);

namespace App\Middleware;

/**
 * Production gate: only accept traffic that arrived via Cloudflare.
 * When CLOUDFLARE_ENFORCE is true, REMOTE_ADDR must be in Cloudflare IP ranges
 * and CF-Ray (or CF-Connecting-IP) must be present. Trust CF-Connecting-IP for
 * the visitor address once the request is validated.
 */
class CloudflareOriginMiddleware
{
    public function handle(): void
    {
        if (!$this->shouldEnforce()) {
            return;
        }

        $remoteAddr = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
        $cfRay = trim((string) ($_SERVER['HTTP_CF_RAY'] ?? ''));
        $cfConnectingIp = trim((string) ($_SERVER['HTTP_CF_CONNECTING_IP'] ?? ''));

        if ($remoteAddr === '' || !$this->isCloudflareIp($remoteAddr)) {
            $this->deny();
        }

        if ($cfRay === '' && $cfConnectingIp === '') {
            $this->deny();
        }

        if ($cfConnectingIp !== '' && filter_var($cfConnectingIp, FILTER_VALIDATE_IP)) {
            $_SERVER['REMOTE_ADDR'] = $cfConnectingIp;
            $GLOBALS['client_ip'] = $cfConnectingIp;
        }
    }

    private function shouldEnforce(): bool
    {
        $env = strtolower((string) (getenv('APP_ENV') ?: config('env', 'development')));
        if ($env === 'development' || $env === 'local' || $env === 'testing') {
            return false;
        }

        return (bool) config('cloudflare.enforce', false);
    }

    private function isCloudflareIp(string $ip): bool
    {
        $ranges = require CONFIG_PATH . '/cloudflare-ips.php';
        $list = str_contains($ip, ':')
            ? ($ranges['ipv6'] ?? [])
            : ($ranges['ipv4'] ?? []);

        foreach ($list as $cidr) {
            if ($this->ipInCidr($ip, $cidr)) {
                return true;
            }
        }

        return false;
    }

    private function ipInCidr(string $ip, string $cidr): bool
    {
        if (!str_contains($cidr, '/')) {
            return $ip === $cidr;
        }

        [$subnet, $mask] = explode('/', $cidr, 2);
        $mask = (int) $mask;

        $ipBin = @inet_pton($ip);
        $subnetBin = @inet_pton($subnet);
        if ($ipBin === false || $subnetBin === false || strlen($ipBin) !== strlen($subnetBin)) {
            return false;
        }

        $len = strlen($ipBin);
        $fullBytes = intdiv($mask, 8);
        $remainingBits = $mask % 8;

        if ($fullBytes > 0 && substr($ipBin, 0, $fullBytes) !== substr($subnetBin, 0, $fullBytes)) {
            return false;
        }

        if ($remainingBits === 0) {
            return true;
        }

        if ($fullBytes >= $len) {
            return true;
        }

        $maskByte = (0xFF << (8 - $remainingBits)) & 0xFF;

        return (ord($ipBin[$fullBytes]) & $maskByte) === (ord($subnetBin[$fullBytes]) & $maskByte);
    }

    private function deny(): never
    {
        http_response_code(403);
        header('Content-Type: text/plain; charset=UTF-8');
        echo "Forbidden\n";
        exit;
    }
}

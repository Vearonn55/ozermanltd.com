<?php

declare(strict_types=1);

namespace Admin\Controllers;

class LogController extends BaseAdminController
{
    public function index(): void
    {
        $this->authorize('super_admin');

        $base = defined('BASE_PATH') ? BASE_PATH . '/storage/logs' : '';
        $files = [];
        if ($base !== '' && is_dir($base)) {
            foreach (glob($base . '/*.{log,txt}', GLOB_BRACE) ?: [] as $path) {
                $files[] = [
                    'name' => basename($path),
                    'size' => filesize($path) ?: 0,
                    'mtime' => filemtime($path) ?: 0,
                ];
            }
        }

        usort($files, static fn ($a, $b) => $b['mtime'] <=> $a['mtime']);

        $selected = basename((string) ($_GET['file'] ?? ($files[0]['name'] ?? '')));
        $content = '';
        $full = $base . '/' . $selected;

        if ($selected !== '' && is_file($full) && str_starts_with(realpath($full) ?: '', realpath($base) ?: '___')) {
            $raw = file_get_contents($full);
            if ($raw !== false) {
                // Tail last ~100KB for UI safety.
                if (strlen($raw) > 100000) {
                    $raw = substr($raw, -100000);
                }
                $content = $raw;
            }
        }

        $this->render('logs.index', [
            'pageTitle' => 'App Logs',
            'files' => $files,
            'selected' => $selected,
            'content' => $content,
            'logDir' => $base,
        ]);
    }
}

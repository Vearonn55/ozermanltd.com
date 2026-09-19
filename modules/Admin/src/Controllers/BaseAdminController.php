<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Middleware\AuthMiddleware;
use Admin\Repositories\RevisionRepository;
use Admin\Services\Auth\ActivityLogger;
use Admin\Services\Auth\AuthService;

abstract class BaseAdminController
{
    protected ?array $user = null;

    protected function requireAuth(): array
    {
        if ($this->user === null) {
            $this->user = (new AuthMiddleware())->handle();
        }

        return $this->user;
    }

    /**
     * Require authentication and a minimum role level.
     *
     * @return array<string, mixed>
     */
    protected function authorize(string $minRole = 'editor'): array
    {
        $user = $this->requireAuth();

        if (!admin_can($user, $minRole)) {
            flash('error', 'You do not have permission to perform this action.');
            redirect(admin_url());
        }

        return $user;
    }

    protected function render(string $view, array $data = []): void
    {
        $data['user'] = $this->user ?? (new AuthService())->user();
        $data['pageTitle'] = $data['pageTitle'] ?? 'Admin';
        admin_view($view, $data);
    }

    protected function verifyCsrf(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!verify_csrf($token)) {
            flash('error', 'Invalid security token. Please try again.');
            redirect($_SERVER['HTTP_REFERER'] ?? admin_url());
        }
    }

    protected function logActivity(string $action, ?string $entityType = null, ?int $entityId = null, ?array $payload = null): void
    {
        $user = $this->requireAuth();
        (new ActivityLogger())->log((int) $user['id'], $action, $entityType, $entityId, $payload);
    }

    /** @return array<string, array<string, string>> */
    protected function parseTranslations(array $input, array $fields): array
    {
        $translations = [];
        $locales = array_keys(config('locales', []));

        foreach ($locales as $code) {
            foreach ($fields as $field) {
                $key = "translations_{$code}_{$field}";
                if (isset($input[$key])) {
                    $translations[$code][$field] = trim((string) $input[$key]);
                }
            }
        }

        return $translations;
    }

    /**
     * Read common list query parameters (search, status filter, page).
     *
     * @return array{q: string, status: string, page: int}
     */
    protected function listParams(): array
    {
        return [
            'q' => trim((string) ($_GET['q'] ?? '')),
            'status' => trim((string) ($_GET['status'] ?? '')),
            'page' => max(1, (int) ($_GET['page'] ?? 1)),
        ];
    }

    /**
     * Apply a bulk action to a set of posted IDs.
     *
     * @param callable(string, int): bool $apply receives (action, id), returns true when handled
     */
    protected function handleBulkRequest(string $entityType, callable $apply, string $redirectPath): void
    {
        $this->verifyCsrf();

        $action = (string) ($_POST['bulk_action'] ?? '');
        $ids = array_filter(array_map('intval', (array) ($_POST['ids'] ?? [])));

        if ($action === '' || $ids === []) {
            flash('error', 'Select at least one item and a bulk action.');
            redirect(admin_url($redirectPath));
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($apply($action, $id)) {
                $count++;
            }
        }

        $this->logActivity('bulk_' . $action, $entityType, null, ['ids' => $ids]);
        flash('success', "Bulk action \"{$action}\" applied to {$count} item(s).");
        redirect(admin_url($redirectPath));
    }

    /** Snapshot the submitted payload as a revision (called on every save). */
    protected function recordRevision(string $entityType, int $entityId, array $payload, bool $isAutosave = false): void
    {
        try {
            $user = $this->requireAuth();
            (new RevisionRepository())->record($entityType, $entityId, (int) $user['id'], $payload, $isAutosave);
        } catch (\Throwable $e) {
            // Revisions are best-effort; never block a save because of them.
            error_log('Revision snapshot failed: ' . $e->getMessage());
        }
    }

    /** Handle an AJAX autosave request: store an autosave revision and respond with JSON. */
    protected function handleAutosave(string $entityType, int $entityId, array $payload): void
    {
        $this->requireAuth();

        $token = $_POST['_csrf'] ?? '';
        if (!verify_csrf($token)) {
            http_response_code(419);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => 'csrf']);
            exit;
        }

        $this->recordRevision($entityType, $entityId, $payload, true);

        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'saved_at' => date('H:i:s')]);
        exit;
    }

    /**
     * Load revisions for a form sidebar and, when ?revision= is present,
     * overlay the selected revision payload onto the item.
     *
     * @return array{item: array<string, mixed>, revisions: array, restoredFrom: int|null}
     */
    protected function withRevisions(string $entityType, int $entityId, array $item): array
    {
        $repo = new RevisionRepository();
        $revisions = $repo->listFor($entityType, $entityId);
        $restoredFrom = null;

        $revisionId = (int) ($_GET['revision'] ?? 0);
        if ($revisionId > 0) {
            $revision = $repo->find($revisionId, $entityType, $entityId);
            if ($revision) {
                $payload = $revision['payload'];
                $translations = $payload['translations'] ?? [];
                unset($payload['translations']);

                $item = array_merge($item, $payload);
                foreach ($translations as $code => $fields) {
                    $item['translations'][$code] = array_merge($item['translations'][$code] ?? [], $fields);
                }
                $restoredFrom = $revisionId;
            }
        }

        return ['item' => $item, 'revisions' => $revisions, 'restoredFrom' => $restoredFrom];
    }

    protected function exportCsv(string $filename, array $headers, array $rows): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        if ($out === false) {
            http_response_code(500);
            echo 'Unable to export CSV.';
            return;
        }

        fputcsv($out, $headers);
        foreach ($rows as $row) {
            fputcsv($out, $row);
        }
        fclose($out);
        exit;
    }
}

<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class PageRepository extends BaseAdminRepository
{
    public function __construct(?PDO $pdo = null)
    {
        parent::__construct($pdo);
        $this->ensureEmbedSchema();
    }

    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT p.*, pt.title
             FROM pages p
             LEFT JOIN page_translations pt ON pt.page_id = p.id AND pt.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             )
             ORDER BY p.sort_order, p.id'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array{items: array, total: int, pages: int, page: int}
     */
    public function paginate(string $q = '', string $status = '', int $page = 1, int $perPage = 15): array
    {
        $where = [];
        $params = [];
        if ($q !== '') {
            $where[] = '(pt.title LIKE :q OR p.slug LIKE :q2 OR p.custom_path LIKE :q3)';
            $params['q'] = '%' . $q . '%';
            $params['q2'] = '%' . $q . '%';
            $params['q3'] = '%' . $q . '%';
        }
        if ($status !== '') {
            $where[] = 'p.status = :status';
            $params['status'] = $status;
        }
        $whereSql = $where !== [] ? 'WHERE ' . implode(' AND ', $where) : '';

        $base = "FROM pages p
             LEFT JOIN page_translations pt ON pt.page_id = p.id AND pt.language_id = (
                 SELECT id FROM languages WHERE is_default = 1 LIMIT 1
             ) {$whereSql}";

        $countStmt = $this->pdo->prepare("SELECT COUNT(*) {$base}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $pages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $pages);
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare(
            "SELECT p.*, pt.title {$base}
             ORDER BY p.sort_order, p.id
             LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);

        return ['items' => $stmt->fetchAll(PDO::FETCH_ASSOC), 'total' => $total, 'pages' => $pages, 'page' => $page];
    }

    public function setStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE pages SET status = :status,
             published_at = IF(:published = 1 AND published_at IS NULL, NOW(), published_at)
             WHERE id = :id'
        );
        $stmt->execute(['id' => $id, 'status' => $status, 'published' => $status === 'published' ? 1 : 0]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM pages WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $page = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$page) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT pt.*, l.code
             FROM page_translations pt
             INNER JOIN languages l ON l.id = pt.language_id
             WHERE pt.page_id = :page_id'
        );
        $stmt->execute(['page_id' => $id]);
        $page['translations'] = $this->indexTranslationsByCode($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $page;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO pages (slug, custom_path, template, embed_mode, status, show_in_nav, sort_order, published_at)
             VALUES (:slug, :custom_path, :template, :embed_mode, :status, :show_in_nav, :sort_order, :published_at)'
        );
        $stmt->execute([
            'slug' => $data['slug'],
            'custom_path' => $data['custom_path'] ?? null,
            'template' => $data['template'] ?? 'default',
            'embed_mode' => $data['embed_mode'] ?? 'site',
            'status' => $data['status'] ?? 'draft',
            'show_in_nav' => (int) ($data['show_in_nav'] ?? 1),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'published_at' => ($data['status'] ?? 'draft') === 'published' ? date('Y-m-d H:i:s') : null,
        ]);

        $pageId = (int) $this->pdo->lastInsertId();
        $this->saveTranslations($pageId, $data['translations'] ?? []);

        return $pageId;
    }

    public function update(int $id, array $data): void
    {
        $publishedAt = null;
        if (($data['status'] ?? 'draft') === 'published') {
            $existing = $this->find($id);
            $publishedAt = $existing['published_at'] ?? date('Y-m-d H:i:s');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE pages SET slug = :slug, custom_path = :custom_path, template = :template, embed_mode = :embed_mode,
             status = :status, show_in_nav = :show_in_nav, sort_order = :sort_order, published_at = :published_at
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'slug' => $data['slug'],
            'custom_path' => $data['custom_path'] ?? null,
            'template' => $data['template'] ?? 'default',
            'embed_mode' => $data['embed_mode'] ?? 'site',
            'status' => $data['status'] ?? 'draft',
            'show_in_nav' => (int) ($data['show_in_nav'] ?? 1),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'published_at' => $publishedAt,
        ]);

        $this->saveTranslations($id, $data['translations'] ?? []);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM pages WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM pages')->fetchColumn();
    }

    private function saveTranslations(int $pageId, array $translations): void
    {
        foreach ($translations as $code => $fields) {
            if (empty($fields['title'])) {
                continue;
            }

            $languageId = $this->languageId($code);
            $stmt = $this->pdo->prepare(
                'INSERT INTO page_translations
                    (page_id, language_id, title, content, excerpt, html_embed, css_embed, js_embed, php_embed, meta_title, meta_description)
                 VALUES
                    (:page_id, :language_id, :title, :content, :excerpt, :html_embed, :css_embed, :js_embed, :php_embed, :meta_title, :meta_description)
                 ON DUPLICATE KEY UPDATE
                   title = VALUES(title), content = VALUES(content), excerpt = VALUES(excerpt),
                   html_embed = VALUES(html_embed), css_embed = VALUES(css_embed),
                   js_embed = VALUES(js_embed), php_embed = VALUES(php_embed),
                   meta_title = VALUES(meta_title), meta_description = VALUES(meta_description)'
            );
            $stmt->execute([
                'page_id' => $pageId,
                'language_id' => $languageId,
                'title' => $fields['title'],
                'content' => $fields['content'] ?? null,
                'excerpt' => $fields['excerpt'] ?? null,
                'html_embed' => $fields['html_embed'] ?? null,
                'css_embed' => $fields['css_embed'] ?? null,
                'js_embed' => $fields['js_embed'] ?? null,
                'php_embed' => $fields['php_embed'] ?? null,
                'meta_title' => $fields['meta_title'] ?? null,
                'meta_description' => $fields['meta_description'] ?? null,
            ]);
        }
    }

    private function ensureEmbedSchema(): void
    {
        static $migrated = false;
        if ($migrated) {
            return;
        }
        $migrated = true;

        $this->addColumnIfMissing('pages', 'custom_path', 'VARCHAR(255) NULL DEFAULT NULL AFTER slug');
        $this->addColumnIfMissing('pages', 'embed_mode', "VARCHAR(20) NOT NULL DEFAULT 'site' AFTER template");
        $this->addColumnIfMissing('page_translations', 'html_embed', 'LONGTEXT NULL AFTER excerpt');
        $this->addColumnIfMissing('page_translations', 'css_embed', 'LONGTEXT NULL AFTER html_embed');
        $this->addColumnIfMissing('page_translations', 'js_embed', 'LONGTEXT NULL AFTER css_embed');
        $this->addColumnIfMissing('page_translations', 'php_embed', 'LONGTEXT NULL AFTER js_embed');

        try {
            $this->pdo->exec('CREATE UNIQUE INDEX uq_pages_custom_path ON pages (custom_path)');
        } catch (\Throwable) {
            // Index already exists or engine does not allow a second unique on nullable path.
        }
    }

    private function addColumnIfMissing(string $table, string $column, string $definition): void
    {
        $stmt = $this->pdo->prepare("SHOW COLUMNS FROM `{$table}` LIKE :column");
        $stmt->execute(['column' => $column]);
        if ($stmt->fetch()) {
            return;
        }

        $this->pdo->exec("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
    }
}

<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class MediaRepository extends BaseAdminRepository
{
    public function folders(?int $parentId = null): array
    {
        if ($parentId === null) {
            $stmt = $this->pdo->query(
                'SELECT * FROM media_folders WHERE parent_id IS NULL ORDER BY name'
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $stmt = $this->pdo->prepare(
            'SELECT * FROM media_folders WHERE parent_id = :parent_id ORDER BY name'
        );
        $stmt->execute(['parent_id' => $parentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allFolders(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM media_folders ORDER BY path, name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findFolder(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM media_folders WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function createFolder(string $name, ?int $parentId = null): int
    {
        $path = $this->slugify($name);
        if ($parentId !== null) {
            $parent = $this->findFolder($parentId);
            if ($parent) {
                $path = rtrim($parent['path'], '/') . '/' . $path;
            }
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO media_folders (parent_id, name, path) VALUES (:parent_id, :name, :path)'
        );
        $stmt->execute([
            'parent_id' => $parentId,
            'name' => $name,
            'path' => $path,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function list(?int $folderId = null, ?string $search = null, int $limit = 100): array
    {
        return $this->paginate($folderId, $search, 1, $limit)['items'];
    }

    /**
     * @return array{items: array, total: int, pages: int, page: int}
     */
    public function paginate(?int $folderId = null, ?string $search = null, int $page = 1, int $perPage = 24): array
    {
        $where = 'WHERE m.is_active = 1';
        $params = [];

        if ($folderId !== null) {
            $where .= ' AND m.folder_id = :folder_id';
            $params['folder_id'] = $folderId;
        } elseif ($search === null || $search === '') {
            $where .= ' AND m.folder_id IS NULL';
        }

        if ($search !== null && $search !== '') {
            $where .= ' AND (m.original_name LIKE :q OR m.alt_text LIKE :q OR m.caption LIKE :q)';
            $params['q'] = '%' . $search . '%';
        }

        $countStmt = $this->pdo->prepare("SELECT COUNT(*) FROM media m {$where}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $pages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $pages));
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare(
            "SELECT m.*, u.name AS uploader_name
             FROM media m
             LEFT JOIN users u ON u.id = m.uploaded_by
             {$where}
             ORDER BY m.id DESC
             LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);

        return ['items' => $stmt->fetchAll(PDO::FETCH_ASSOC), 'total' => $total, 'pages' => $pages, 'page' => $page];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM media WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO media (folder_id, uploaded_by, file_name, original_name, file_path, file_type, mime_type, file_size, width, height, alt_text, caption, is_active)
             VALUES (:folder_id, :uploaded_by, :file_name, :original_name, :file_path, :file_type, :mime_type, :file_size, :width, :height, :alt_text, :caption, 1)'
        );
        $stmt->execute([
            'folder_id' => $data['folder_id'] ?? null,
            'uploaded_by' => $data['uploaded_by'] ?? null,
            'file_name' => $data['file_name'],
            'original_name' => $data['original_name'],
            'file_path' => $data['file_path'],
            'file_type' => $data['file_type'],
            'mime_type' => $data['mime_type'] ?? null,
            'file_size' => $data['file_size'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'alt_text' => $data['alt_text'] ?? null,
            'caption' => $data['caption'] ?? null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE media SET folder_id = :folder_id, alt_text = :alt_text, caption = :caption WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'folder_id' => $data['folder_id'] !== '' && $data['folder_id'] !== null ? (int) $data['folder_id'] : null,
            'alt_text' => $data['alt_text'] ?? null,
            'caption' => $data['caption'] ?? null,
        ]);
    }

    public function delete(int $id): ?array
    {
        $item = $this->find($id);
        if (!$item) {
            return null;
        }

        $stmt = $this->pdo->prepare('DELETE FROM media WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $item;
    }

    public function referenceCount(int $mediaId): int
    {
        $tables = [
            'SELECT COUNT(*) FROM news WHERE featured_image_id = :id',
            'SELECT COUNT(*) FROM projects WHERE featured_image_id = :id',
            'SELECT COUNT(*) FROM seo_meta WHERE og_image_id = :id',
            'SELECT COUNT(*) FROM hero_slides WHERE media_id = :id',
            'SELECT COUNT(*) FROM gallery_items WHERE media_id = :id',
            'SELECT COUNT(*) FROM gallery_collections WHERE cover_image_id = :id',
            'SELECT COUNT(*) FROM stores WHERE media_id = :id',
            'SELECT COUNT(*) FROM brands WHERE media_id = :id',
            'SELECT COUNT(*) FROM team_members WHERE media_id = :id',
            'SELECT COUNT(*) FROM sectors WHERE media_id = :id',
            'SELECT COUNT(*) FROM site_banners WHERE media_id = :id',
            'SELECT COUNT(*) FROM pages WHERE banner_media_id = :id',
        ];

        $total = 0;
        foreach ($tables as $sql) {
            try {
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['id' => $mediaId]);
                $total += (int) $stmt->fetchColumn();
            } catch (\Throwable) {
                // Table may not exist on a trimmed host schema.
            }
        }

        return $total;
    }
}

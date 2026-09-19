<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\MediaRepository;

class MediaController extends BaseAdminController
{
    private MediaRepository $repo;

    public function __construct()
    {
        $this->repo = new MediaRepository();
    }

    public function index(): void
    {
        $this->authorize('editor');

        $folderId = isset($_GET['folder']) && $_GET['folder'] !== '' ? (int) $_GET['folder'] : null;
        $search = trim((string) ($_GET['q'] ?? ''));
        $picker = ($_GET['picker'] ?? '') === '1';
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $result = $this->repo->paginate(
            $search !== '' ? null : $folderId,
            $search !== '' ? $search : null,
            $page,
            $picker ? 24 : 24
        );

        $view = $picker ? 'media.picker' : 'media.index';
        $data = [
            'pageTitle' => $picker ? 'Select Media' : 'Media Library',
            'items' => $result['items'],
            'total' => $result['total'],
            'pages' => $result['pages'],
            'page' => $result['page'],
            'folders' => $this->repo->folders($folderId),
            'allFolders' => $this->repo->allFolders(),
            'currentFolderId' => $folderId,
            'currentFolder' => $folderId ? $this->repo->findFolder($folderId) : null,
            'search' => $search,
            'picker' => $picker,
            'field' => $_GET['field'] ?? 'featured_image_id',
            'canDelete' => admin_can($this->user, 'content_manager'),
            'user' => $this->user,
        ];

        if ($picker) {
            admin_guest_view($view, $data);
            return;
        }

        $this->render($view, $data);
    }

    public function store(): void
    {
        $user = $this->authorize('editor');
        $this->verifyCsrf();

        if (!isset($_FILES['file']) || !is_array($_FILES['file'])) {
            flash('error', 'No file uploaded.');
            redirect(admin_url('media'));
        }

        $file = $_FILES['file'];
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            flash('error', 'Upload failed. Check PHP upload limits in cPanel.');
            redirect(admin_url('media'));
        }

        $maxBytes = (int) admin_config('max_upload_bytes', 10 * 1024 * 1024);
        if (($file['size'] ?? 0) > $maxBytes) {
            flash('error', 'File exceeds maximum upload size.');
            redirect(admin_url('media'));
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: ($file['type'] ?? 'application/octet-stream');
        $allowed = admin_config('allowed_mime_types', []);
        if (!in_array($mime, $allowed, true)) {
            flash('error', 'File type not allowed: ' . $mime);
            redirect(admin_url('media'));
        }

        $uploadPath = (string) admin_config('upload_path');
        $yearMonth = date('Y/m');
        $destDir = rtrim($uploadPath, '/') . '/' . $yearMonth;
        if (!is_dir($destDir) && !mkdir($destDir, 0755, true) && !is_dir($destDir)) {
            flash('error', 'Unable to create upload directory.');
            redirect(admin_url('media'));
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'bin');
        $safeName = bin2hex(random_bytes(8)) . '.' . $ext;
        $destFile = $destDir . '/' . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $destFile)) {
            flash('error', 'Failed to store uploaded file.');
            redirect(admin_url('media'));
        }

        $width = null;
        $height = null;
        if (str_starts_with($mime, 'image/')) {
            $info = @getimagesize($destFile);
            if ($info) {
                $width = $info[0];
                $height = $info[1];
            }
        }

        $relativePath = $yearMonth . '/' . $safeName;
        $folderId = isset($_POST['folder_id']) && $_POST['folder_id'] !== '' ? (int) $_POST['folder_id'] : null;

        $id = $this->repo->create([
            'folder_id' => $folderId,
            'uploaded_by' => (int) $user['id'],
            'file_name' => $safeName,
            'original_name' => $file['name'],
            'file_path' => $relativePath,
            'file_type' => str_starts_with($mime, 'image/') ? 'image' : 'document',
            'mime_type' => $mime,
            'file_size' => (int) $file['size'],
            'width' => $width,
            'height' => $height,
            'alt_text' => trim((string) ($_POST['alt_text'] ?? '')),
            'caption' => trim((string) ($_POST['caption'] ?? '')),
        ]);

        $this->logActivity('upload', 'media', $id);
        flash('success', 'File uploaded.');

        if (($_POST['picker'] ?? '') === '1') {
            redirect(admin_url('media') . '?picker=1&field=' . urlencode((string) ($_POST['field'] ?? 'featured_image_id')));
        }

        $redirect = admin_url('media') . ($folderId ? '?folder=' . $folderId : '');
        redirect($redirect);
    }

    public function storeFolder(): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name === '') {
            flash('error', 'Folder name is required.');
            redirect(admin_url('media'));
        }

        $parentId = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? (int) $_POST['parent_id'] : null;
        $id = $this->repo->createFolder($name, $parentId);
        $this->logActivity('create', 'media_folder', $id);
        flash('success', 'Folder created.');
        redirect(admin_url('media') . ($parentId ? '?folder=' . $parentId : ''));
    }

    public function update(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', 'Media not found.');
            redirect(admin_url('media'));
        }

        $this->repo->update($id, [
            'folder_id' => $_POST['folder_id'] ?? null,
            'alt_text' => trim((string) ($_POST['alt_text'] ?? '')),
            'caption' => trim((string) ($_POST['caption'] ?? '')),
        ]);

        $this->logActivity('update', 'media', $id);
        flash('success', 'Media updated.');
        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('media'));
    }

    public function bulk(): void
    {
        $this->authorize('content_manager');
        $this->handleBulkRequest('media', function (string $action, int $id): bool {
            if ($action !== 'delete') {
                return false;
            }
            if ($this->repo->referenceCount($id) > 0) {
                return false;
            }
            $item = $this->repo->delete($id);
            if ($item) {
                $uploadPath = rtrim((string) admin_config('upload_path'), '/');
                $full = $uploadPath . '/' . ltrim($item['file_path'], '/');
                if (is_file($full)) {
                    @unlink($full);
                }
            }
            return (bool) $item;
        }, 'media');
    }

    public function destroy(int $id): void
    {
        $this->authorize('content_manager');
        $this->verifyCsrf();

        $refs = $this->repo->referenceCount($id);
        if ($refs > 0) {
            flash('error', "Cannot delete: media is referenced by {$refs} record(s).");
            redirect(admin_url('media'));
        }

        $item = $this->repo->delete($id);
        if ($item) {
            $uploadPath = rtrim((string) admin_config('upload_path'), '/');
            $full = $uploadPath . '/' . ltrim($item['file_path'], '/');
            if (is_file($full)) {
                @unlink($full);
            }
            $this->logActivity('delete', 'media', $id);
            flash('success', 'Media deleted.');
        }

        redirect(admin_url('media'));
    }
}

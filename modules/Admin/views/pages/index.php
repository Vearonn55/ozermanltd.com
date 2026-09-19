<?php
admin_partial('list-toolbar', [
    'action' => admin_url('pages'),
    'q' => $q,
    'statusFilter' => $statusFilter,
    'statusOptions' => ['published' => 'Published', 'draft' => 'Draft'],
    'total' => $total,
    'createUrl' => admin_url('pages/create'),
    'createLabel' => 'New Page',
]);
?>

<?php if (!empty($missingQrLandings)): ?>
<div class="alert alert-warning mb-4">
    <p class="mb-2"><strong>QR / Catalogues pages are missing</strong> (<?= e(implode(', ', $missingQrLandings)) ?>). Public <code>/qr</code> and <code>/catalogues</code> still work from templates, but you cannot edit them here until created.</p>
    <form method="POST" action="<?= admin_url('pages/seed-qr-landings') ?>">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-primary">Create QR &amp; Catalogues pages</button>
    </form>
</div>
<?php endif; ?>

<div x-data="adminBulk()">
    <form id="bulk-form" method="POST" action="<?= admin_url('pages/bulk') ?>"
          onsubmit="return this.elements.bulk_action.value !== 'delete' || confirm('Delete the selected pages?')"
          class="flex items-center gap-2 mb-3" x-show="selected.length > 0" x-cloak>
        <?= csrf_field() ?>
        <span class="text-sm" x-text="selected.length + ' selected'"></span>
        <select name="bulk_action" class="form-select" style="width:auto">
            <option value="publish">Publish</option>
            <option value="draft">Set to draft</option>
            <option value="delete">Delete</option>
        </select>
        <button class="btn btn-secondary">Apply</button>
    </form>

    <div class="card overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th class="w-8"><input type="checkbox" class="rounded" @change="toggleAll($event)"></th>
                    <th>Title</th>
                    <th>Public URL</th>
                    <th>Template</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item):
                    $publicPath = trim((string) (($item['custom_path'] ?? '') !== '' ? $item['custom_path'] : $item['slug']), '/');
                    if (($item['slug'] ?? '') === 'home') {
                        $publicPath = '';
                    }
                    $publicUrl = admin_preview_url($publicPath);
                ?>
                <tr>
                    <td>
                        <input type="checkbox" form="bulk-form" name="ids[]" value="<?= (int) $item['id'] ?>" class="rounded"
                               :checked="has(<?= (int) $item['id'] ?>)" @change="toggle(<?= (int) $item['id'] ?>)">
                    </td>
                    <td>
                        <a href="<?= admin_url('pages/' . $item['id'] . '/edit') ?>"><?= e($item['title'] ?? 'Untitled') ?></a>
                    </td>
                    <td>
                        <code><?= e($publicUrl) ?></code>
                        <button type="button" class="btn btn-link" data-copy="<?= e($publicUrl) ?>">Copy</button>
                    </td>
                    <td><?= e($item['template']) ?><?= ($item['embed_mode'] ?? 'site') === 'blank' ? ' · blank' : '' ?></td>
                    <td><?php $status = $item['status']; require ADMIN_MODULE_PATH . '/views/partials/status-badge.php'; ?></td>
                    <td class="text-right whitespace-nowrap space-x-2">
                        <?php if ($item['status'] === 'published'): ?>
                        <a href="<?= e($publicUrl) ?>" target="_blank">View</a>
                        <?php endif; ?>
                        <form method="POST" action="<?= admin_url('pages/' . $item['id'] . '/status') ?>" class="inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-link">
                                <?= $item['status'] === 'published' ? 'Unpublish' : 'Publish' ?>
                            </button>
                        </form>
                        <a href="<?= admin_url('pages/' . $item['id'] . '/edit') ?>">Edit</a>
                        <form method="POST" action="<?= admin_url('pages/' . $item['id']) ?>" class="inline" onsubmit="return confirm('Delete this page?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$items): ?>
                <tr><td colspan="6" class="text-center" style="padding:2rem">No pages found. Create one to assign a public URL and embed HTML/CSS/JS.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php admin_partial('pagination', ['page' => $page, 'pages' => $pages, 'action' => admin_url('pages'), 'q' => $q, 'statusFilter' => $statusFilter]); ?>

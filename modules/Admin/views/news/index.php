<?php
admin_partial('list-toolbar', [
    'action' => admin_url('news'),
    'q' => $q,
    'statusFilter' => $statusFilter,
    'statusOptions' => ['published' => 'Published', 'scheduled' => 'Scheduled', 'pending_review' => 'Pending review', 'draft' => 'Draft'],
    'total' => $total,
    'createUrl' => admin_url('news/create'),
    'createLabel' => 'New Article',
]);
?>

<div x-data="adminBulk()">
    <form id="bulk-form" method="POST" action="<?= admin_url('news/bulk') ?>"
          onsubmit="return this.elements.bulk_action.value !== 'delete' || confirm('Delete the selected articles?')"
          class="flex items-center gap-2 mb-3" x-show="selected.length > 0" x-cloak>
        <?= csrf_field() ?>
        <span class="text-sm text-slate-600" x-text="selected.length + ' selected'"></span>
        <select name="bulk_action" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm">
            <option value="publish">Publish</option>
            <option value="draft">Set to draft</option>
            <option value="delete">Delete</option>
        </select>
        <button class="bg-slate-800 text-white text-sm px-4 py-1.5 rounded-lg">Apply</button>
    </form>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 w-8"><input type="checkbox" class="rounded" @change="toggleAll($event)"></th>
                    <th class="text-left px-4 py-3 font-medium text-slate-600">Title</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-600">Category</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-600">Status</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-600">Published</th>
                    <th class="text-right px-4 py-3 font-medium text-slate-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($items as $item): ?>
                <?php $isScheduled = ($item['status'] === 'published') && !empty($item['publish_date']) && strtotime($item['publish_date']) > time(); ?>
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <input type="checkbox" form="bulk-form" name="ids[]" value="<?= (int) $item['id'] ?>" class="rounded"
                               :checked="has(<?= (int) $item['id'] ?>)" @change="toggle(<?= (int) $item['id'] ?>)">
                    </td>
                    <td class="px-4 py-3">
                        <a href="<?= admin_url('news/' . $item['id'] . '/edit') ?>" class="font-medium hover:text-blue-600"><?= e($item['title'] ?? 'Untitled') ?></a>
                        <?php if ($item['is_featured']): ?><span class="ml-1 text-xs text-amber-600">★</span><?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-slate-500"><?= e($item['category_name'] ?? '—') ?></td>
                    <td class="px-4 py-3">
                        <?php if ($isScheduled): ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">Scheduled</span>
                        <?php else: ?>
                        <?php $status = $item['status']; require ADMIN_MODULE_PATH . '/views/partials/status-badge.php'; ?>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-slate-500"><?= $item['publish_date'] ? date('M j, Y', strtotime($item['publish_date'])) : '—' ?></td>
                    <td class="px-4 py-3 text-right whitespace-nowrap space-x-2">
                        <?php if ($item['status'] === 'published' && !$isScheduled && !empty($item['slug'])): ?>
                        <a href="<?= e(admin_preview_url('news/' . $item['slug'])) ?>" target="_blank" class="text-slate-500 hover:underline">View</a>
                        <?php endif; ?>
                        <form method="POST" action="<?= admin_url('news/' . $item['id'] . '/status') ?>" class="inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-slate-600 hover:underline">
                                <?= $item['status'] === 'published' ? 'Unpublish' : 'Publish' ?>
                            </button>
                        </form>
                        <a href="<?= admin_url('news/' . $item['id'] . '/edit') ?>" class="text-blue-600 hover:underline">Edit</a>
                        <form method="POST" action="<?= admin_url('news/' . $item['id']) ?>" class="inline" onsubmit="return confirm('Delete this article?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$items): ?>
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No articles found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php admin_partial('pagination', ['page' => $page, 'pages' => $pages, 'action' => admin_url('news'), 'q' => $q, 'statusFilter' => $statusFilter]); ?>

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500"><?= count($items) ?> partnership brand(s)</p>
    <a href="<?= admin_url('partnerships/create') ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">+ New Partnership</a>
</div>

<div x-data="adminBulk()">
    <form id="bulk-form" method="POST" action="<?= admin_url('partnerships/bulk') ?>"
          onsubmit="return this.elements.bulk_action.value !== 'delete' || confirm('Delete the selected partnerships?')"
          class="flex items-center gap-2 mb-3" x-show="selected.length > 0" x-cloak>
        <?= csrf_field() ?>
        <span class="text-sm text-slate-600" x-text="selected.length + ' selected'"></span>
        <select name="bulk_action" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm">
            <option value="activate">Show on site</option>
            <option value="deactivate">Hide from site</option>
            <option value="delete">Delete</option>
        </select>
        <button class="bg-slate-800 text-white text-sm px-4 py-1.5 rounded-lg">Apply</button>
    </form>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 w-8"><input type="checkbox" class="rounded" @change="toggleAll($event)"></th>
                    <th class="text-left px-4 py-3 font-medium text-slate-600">Brand</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-600">Website</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-600">Order</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-600">Visible</th>
                    <th class="text-right px-4 py-3 font-medium text-slate-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($items as $item): ?>
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <input type="checkbox" form="bulk-form" name="ids[]" value="<?= (int) $item['id'] ?>" class="rounded"
                               :checked="has(<?= (int) $item['id'] ?>)" @change="toggle(<?= (int) $item['id'] ?>)">
                    </td>
                    <td class="px-4 py-3">
                        <a href="<?= admin_url('partnerships/' . $item['id'] . '/edit') ?>" class="font-medium hover:text-blue-600"><?= e($item['label'] ?? 'Untitled') ?></a>
                    </td>
                    <td class="px-4 py-3 text-slate-500"><?= e($item['website_url'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-slate-500"><?= (int) $item['sort_order'] ?></td>
                    <td class="px-4 py-3"><?php $status = $item['is_active'] ? 'active' : 'inactive'; require ADMIN_MODULE_PATH . '/views/partials/status-badge.php'; ?></td>
                    <td class="px-4 py-3 text-right whitespace-nowrap space-x-2">
                        <form method="POST" action="<?= admin_url('partnerships/' . $item['id'] . '/status') ?>" class="inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-slate-600 hover:underline"><?= $item['is_active'] ? 'Hide' : 'Show' ?></button>
                        </form>
                        <a href="<?= admin_url('partnerships/' . $item['id'] . '/edit') ?>" class="text-blue-600 hover:underline">Edit</a>
                        <form method="POST" action="<?= admin_url('partnerships/' . $item['id']) ?>" class="inline" onsubmit="return confirm('Delete this partnership?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$items): ?>
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No partnerships yet. Create the first one.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-2">
        <?php foreach (['news' => 'News Categories', 'project' => 'Project Categories'] as $value => $label): ?>
        <a href="<?= admin_url('categories') ?>?type=<?= $value ?>"
           class="px-4 py-2 rounded-lg text-sm font-medium <?= $type === $value ? 'bg-blue-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
            <?= $label ?>
        </a>
        <?php endforeach; ?>
    </div>
    <a href="<?= admin_url('categories/create') ?>?type=<?= e($type) ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">+ New Category</a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Name</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Icon</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Order</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Visible</th>
                <th class="text-right px-4 py-3 font-medium text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($items as $item): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3">
                    <a href="<?= admin_url('categories/' . $item['id'] . '/edit') ?>?type=<?= e($type) ?>" class="font-medium hover:text-blue-600"><?= e($item['label'] ?? 'Untitled') ?></a>
                </td>
                <td class="px-4 py-3 text-slate-500"><?= e($item['icon'] ?? '—') ?></td>
                <td class="px-4 py-3 text-slate-500"><?= (int) $item['sort_order'] ?></td>
                <td class="px-4 py-3"><?php $status = $item['is_active'] ? 'active' : 'inactive'; require ADMIN_MODULE_PATH . '/views/partials/status-badge.php'; ?></td>
                <td class="px-4 py-3 text-right whitespace-nowrap space-x-2">
                    <form method="POST" action="<?= admin_url('categories/' . $item['id'] . '/status') ?>?type=<?= e($type) ?>" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="text-slate-600 hover:underline"><?= $item['is_active'] ? 'Hide' : 'Show' ?></button>
                    </form>
                    <a href="<?= admin_url('categories/' . $item['id'] . '/edit') ?>?type=<?= e($type) ?>" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="<?= admin_url('categories/' . $item['id']) ?>?type=<?= e($type) ?>" class="inline" onsubmit="return confirm('Delete this category? Content assigned to it keeps working but loses the category.')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$items): ?>
            <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">No categories yet. Create the first one.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

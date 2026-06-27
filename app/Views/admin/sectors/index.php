<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500"><?= count($items) ?> sector(s)</p>
    <a href="<?= admin_url('sectors/create') ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
        + New Sector
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Name</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Slug</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Icon</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Active</th>
                <th class="text-right px-4 py-3 font-medium text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($items as $item): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-medium"><?= e($item['name'] ?? 'Untitled') ?></td>
                <td class="px-4 py-3 text-slate-500 font-mono text-xs"><?= e($item['slug'] ?? '—') ?></td>
                <td class="px-4 py-3 text-slate-500"><?= e($item['icon'] ?? '—') ?></td>
                <td class="px-4 py-3"><?php $status = $item['is_active'] ? 'active' : 'inactive'; require APP_PATH . '/Views/admin/partials/status-badge.php'; ?></td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="<?= admin_url('sectors/' . $item['id'] . '/edit') ?>" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="<?= admin_url('sectors/' . $item['id']) ?>" class="inline" onsubmit="return confirm('Delete this sector?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

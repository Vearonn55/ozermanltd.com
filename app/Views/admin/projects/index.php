<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500"><?= count($items) ?> project(s)</p>
    <a href="<?= admin_url('projects/create') ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
        + New Project
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Title</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Location</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Status</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Category</th>
                <th class="text-right px-4 py-3 font-medium text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($items as $item): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3">
                    <span class="font-medium"><?= e($item['title'] ?? 'Untitled') ?></span>
                    <?php if ($item['is_featured']): ?><span class="ml-1 text-xs text-amber-600">★</span><?php endif; ?>
                </td>
                <td class="px-4 py-3 text-slate-500"><?= e($item['location'] ?? '—') ?></td>
                <td class="px-4 py-3"><?php $status = $item['status']; require APP_PATH . '/Views/admin/partials/status-badge.php'; ?></td>
                <td class="px-4 py-3 text-slate-500"><?= e($item['category_name'] ?? '—') ?></td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="<?= admin_url('projects/' . $item['id'] . '/edit') ?>" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="<?= admin_url('projects/' . $item['id']) ?>" class="inline" onsubmit="return confirm('Delete this project?')">
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

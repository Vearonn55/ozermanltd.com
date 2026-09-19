<?php
$byArea = [];
foreach ($items as $item) {
    $byArea[$item['area']][] = $item;
}
?>
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500">Reusable content blocks rendered on the public site (Home &amp; About sections).</p>
    <a href="<?= admin_url('site-sections/create') ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">+ New Block</a>
</div>

<div class="space-y-8">
    <?php foreach ($areas as $areaKey => $areaLabel): ?>
    <div>
        <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-2"><?= e($areaLabel) ?></h2>
        <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-slate-600">Title</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-600">Icon</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-600">Order</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-600">Visible</th>
                        <th class="text-right px-4 py-3 font-medium text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($byArea[$areaKey] ?? [] as $item): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <a href="<?= admin_url('site-sections/' . $item['id'] . '/edit') ?>" class="font-medium hover:text-blue-600"><?= e($item['label'] ?? 'Untitled') ?></a>
                        </td>
                        <td class="px-4 py-3 text-slate-500"><?= e($item['icon'] ?? '—') ?></td>
                        <td class="px-4 py-3 text-slate-500"><?= (int) $item['sort_order'] ?></td>
                        <td class="px-4 py-3"><?php $status = $item['is_active'] ? 'active' : 'inactive'; require ADMIN_MODULE_PATH . '/views/partials/status-badge.php'; ?></td>
                        <td class="px-4 py-3 text-right whitespace-nowrap space-x-2">
                            <form method="POST" action="<?= admin_url('site-sections/' . $item['id'] . '/status') ?>" class="inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="text-slate-600 hover:underline"><?= $item['is_active'] ? 'Hide' : 'Show' ?></button>
                            </form>
                            <a href="<?= admin_url('site-sections/' . $item['id'] . '/edit') ?>" class="text-blue-600 hover:underline">Edit</a>
                            <form method="POST" action="<?= admin_url('site-sections/' . $item['id']) ?>" class="inline" onsubmit="return confirm('Delete this block?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($byArea[$areaKey])): ?>
                    <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">No blocks in this area yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500"><?= count($items) ?> stat counter(s) shown on the home page</p>
    <a href="<?= admin_url('stats/create') ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">+ New Counter</a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Label</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Value</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Order</th>
                <th class="text-right px-4 py-3 font-medium text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($items as $item): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3">
                    <a href="<?= admin_url('stats/' . $item['id'] . '/edit') ?>" class="font-medium hover:text-blue-600"><?= e($item['label'] ?? 'Untitled') ?></a>
                </td>
                <td class="px-4 py-3 text-slate-500"><?= e($item['value'] ?? '—') ?></td>
                <td class="px-4 py-3 text-slate-500"><?= (int) $item['sort_order'] ?></td>
                <td class="px-4 py-3 text-right whitespace-nowrap space-x-2">
                    <a href="<?= admin_url('stats/' . $item['id'] . '/edit') ?>" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="<?= admin_url('stats/' . $item['id']) ?>" class="inline" onsubmit="return confirm('Delete this counter (all languages)?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$items): ?>
            <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">No stat counters yet. Create the first one.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

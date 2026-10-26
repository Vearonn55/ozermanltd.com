<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500 max-w-2xl">Enabled languages appear in the site switcher and in content forms. The main language is the one visitors see first. Removing a language also removes the text stored for it.</p>
    <a href="<?= admin_url('locales/create') ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">+ Add language</a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Code</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Name</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Direction</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Order</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Main</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Status</th>
                <th class="text-right px-4 py-3 font-medium text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($items as $item): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-mono uppercase"><?= e($item['code']) ?></td>
                <td class="px-4 py-3 font-medium"><?= e($item['name']) ?></td>
                <td class="px-4 py-3 text-slate-500"><?= ($item['dir'] ?? 'ltr') === 'rtl' ? 'Right to left' : 'Left to right' ?></td>
                <td class="px-4 py-3 text-slate-500"><?= (int) $item['sort_order'] ?></td>
                <td class="px-4 py-3 text-slate-500"><?= (int) $item['is_default'] === 1 ? 'Yes' : '—' ?></td>
                <td class="px-4 py-3"><?php $status = (int) $item['is_active'] === 1 ? 'active' : 'inactive'; require ADMIN_MODULE_PATH . '/views/partials/status-badge.php'; ?></td>
                <td class="px-4 py-3 text-right whitespace-nowrap space-x-2">
                    <form method="POST" action="<?= admin_url('locales/' . $item['id'] . '/status') ?>" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="text-slate-600 hover:underline"><?= (int) $item['is_active'] === 1 ? 'Disable' : 'Enable' ?></button>
                    </form>
                    <a href="<?= admin_url('locales/' . $item['id'] . '/edit') ?>" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="<?= admin_url('locales/' . $item['id']) ?>" class="inline" onsubmit="return confirm('Remove this language and its translations?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="text-red-600 hover:underline">Remove</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$items): ?>
            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">No languages yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

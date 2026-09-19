<p class="text-sm text-slate-500 mb-6">Menus control the header navigation and the three footer link columns. Edit a menu to change its links; changes go live immediately.</p>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Menu</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Location key</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Links</th>
                <th class="text-right px-4 py-3 font-medium text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($items as $item): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3">
                    <a href="<?= admin_url('menus/' . $item['id'] . '/edit') ?>" class="font-medium hover:text-blue-600">
                        <?= e($locationLabels[$item['location']] ?? $item['location']) ?>
                    </a>
                </td>
                <td class="px-4 py-3 text-slate-500 font-mono text-xs"><?= e($item['location']) ?></td>
                <td class="px-4 py-3 text-slate-500"><?= (int) $item['item_count'] ?></td>
                <td class="px-4 py-3 text-right">
                    <a href="<?= admin_url('menus/' . $item['id'] . '/edit') ?>" class="text-blue-600 hover:underline">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$items): ?>
            <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">No menus found. Reseed the database to create the default menu locations.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

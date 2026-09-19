<div class="space-y-4">
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search user, entity, IP…" class="rounded-lg border border-slate-300 px-3 py-2 text-sm w-64">
        <select name="action" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
            <option value="">All actions</option>
            <?php foreach ($actions as $action): ?>
            <option value="<?= e($action) ?>" <?= $actionFilter === $action ? 'selected' : '' ?>><?= e($action) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="bg-slate-800 text-white text-sm px-4 py-2 rounded-lg">Search</button>
    </form>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-left px-4 py-2">When</th>
                    <th class="text-left px-4 py-2">User</th>
                    <th class="text-left px-4 py-2">Action</th>
                    <th class="text-left px-4 py-2">Entity</th>
                    <th class="text-left px-4 py-2">IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr class="border-t">
                    <td class="px-4 py-3 whitespace-nowrap"><?= e($item['created_at']) ?></td>
                    <td class="px-4 py-3"><?= e($item['user_name'] ?? '—') ?><div class="text-xs text-slate-400"><?= e($item['user_email'] ?? '') ?></div></td>
                    <td class="px-4 py-3"><?= e($item['action']) ?></td>
                    <td class="px-4 py-3"><?= e(($item['entity_type'] ?? '') . ($item['entity_id'] ? ' #' . $item['entity_id'] : '')) ?: '—' ?></td>
                    <td class="px-4 py-3"><?= e($item['ip_address'] ?? '—') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!$items): ?>
        <p class="p-4 text-sm text-slate-500">No activity yet.</p>
        <?php endif; ?>
    </div>
</div>

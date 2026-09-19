<div class="space-y-4">
    <div class="flex items-center justify-between">
        <form method="GET" class="flex gap-2">
            <input type="text" name="type" value="<?= e($filterType) ?>" placeholder="Filter entity type" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
            <button class="bg-slate-800 text-white text-sm px-4 py-2 rounded-lg">Filter</button>
        </form>
        <a href="<?= admin_url('seo/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">New SEO meta</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-left px-4 py-2">Entity</th>
                    <th class="text-left px-4 py-2">Lang</th>
                    <th class="text-left px-4 py-2">Title</th>
                    <th class="text-left px-4 py-2">Robots</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr class="border-t">
                    <td class="px-4 py-3"><?= e($item['entity_type']) ?> #<?= (int) $item['entity_id'] ?></td>
                    <td class="px-4 py-3"><?= e($item['language_code'] ?? '') ?></td>
                    <td class="px-4 py-3"><?= e($item['meta_title'] ?? '—') ?></td>
                    <td class="px-4 py-3"><?= e($item['robots'] ?? '') ?></td>
                    <td class="px-4 py-3 text-right space-x-3">
                        <a href="<?= admin_url('seo/' . $item['id'] . '/edit') ?>" class="text-blue-600 hover:underline">Edit</a>
                        <form method="POST" action="<?= admin_url('seo/' . $item['id']) ?>" class="inline" onsubmit="return confirm('Delete?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

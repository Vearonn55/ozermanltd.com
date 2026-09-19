<?php
$isFooter = str_starts_with((string) $item['location'], 'footer_');
$langCodes = array_map(static fn(array $l): string => $l['code'], $languages);
$existingRows = [];
foreach ($item['items'] as $menuItem) {
    $labels = [];
    foreach ($langCodes as $code) {
        $labels[$code] = (string) ($menuItem['labels'][$code] ?? '');
    }
    $existingRows[] = [
        'url' => (string) ($menuItem['url'] ?? ''),
        'target' => (string) ($menuItem['target'] ?? '_self'),
        'is_active' => (int) ($menuItem['is_active'] ?? 1) === 1,
        'labels' => $labels,
    ];
}
$emptyLabels = array_fill_keys($langCodes, '');
?>
<form method="POST" action="<?= admin_url('menus/' . $item['id']) ?>" class="space-y-6 max-w-5xl"
      x-data='{ rows: <?= json_encode($existingRows, JSON_HEX_APOS | JSON_HEX_QUOT) ?> }'>
    <?= csrf_field() ?>

    <?php if ($isFooter): ?>
    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold">Column Title</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($languages as $lang): ?>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1"><?= e($lang['name']) ?></label>
                <input type="text" name="titles[<?= e($lang['code']) ?>]" value="<?= e($item['titles'][$lang['code']] ?? '') ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Links</h2>
            <button type="button" class="text-sm text-blue-600 hover:underline"
                    @click='rows.push({ url: "", target: "_self", is_active: true, labels: <?= json_encode($emptyLabels, JSON_HEX_APOS | JSON_HEX_QUOT) ?> })'>
                + Add link
            </button>
        </div>

        <p class="text-sm text-slate-500" x-show="rows.length === 0">No links yet. Add the first one.</p>

        <div class="space-y-3">
            <template x-for="(row, idx) in rows" :key="idx">
                <div class="border border-slate-200 rounded-lg p-4 space-y-3">
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="flex-1 min-w-48">
                            <label class="block text-xs font-medium text-slate-500 mb-1">URL</label>
                            <input type="text" :name="'items[' + idx + '][url]'" x-model="row.url" placeholder="/about or https://…"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Opens in</label>
                            <select :name="'items[' + idx + '][target]'" x-model="row.target"
                                    class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <option value="_self">Same tab</option>
                                <option value="_blank">New tab</option>
                            </select>
                        </div>
                        <label class="flex items-center gap-2 text-sm pb-2">
                            <input type="checkbox" :name="'items[' + idx + '][is_active]'" value="1" x-model="row.is_active" class="rounded">
                            Visible
                        </label>
                        <input type="hidden" :name="'items[' + idx + '][sort_order]'" :value="idx">
                        <div class="flex items-center gap-3 pb-2 text-sm ml-auto">
                            <button type="button" class="text-slate-500 hover:underline" @click="if (idx > 0) rows.splice(idx - 1, 0, rows.splice(idx, 1)[0])">↑ Up</button>
                            <button type="button" class="text-slate-500 hover:underline" @click="if (idx < rows.length - 1) rows.splice(idx + 1, 0, rows.splice(idx, 1)[0])">↓ Down</button>
                            <button type="button" class="text-red-600 hover:underline" @click="rows.splice(idx, 1)">Remove</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <?php foreach ($languages as $lang): ?>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Label (<?= e($lang['name']) ?>)</label>
                            <input type="text" :name="'items[' + idx + '][labels][<?= e($lang['code']) ?>]'"
                                   x-model="row.labels['<?= e($lang['code']) ?>']"
                                   <?= $lang['code'] === 'ar' ? 'dir="rtl"' : '' ?>
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">Save Menu</button>
        <a href="<?= admin_url('menus') ?>" class="text-slate-600 hover:text-slate-800 text-sm">Cancel</a>
    </div>
</form>

<?php $isEdit = $item !== null; ?>
<?php admin_partial('visibility-notice', ['message' => 'Blocks render in their site area when Active is enabled. Vision/Mission areas use only the first active block.']); ?>
<form method="POST" action="<?= $isEdit ? admin_url('site-sections/' . $item['id']) : admin_url('site-sections') ?>" class="space-y-6 max-w-4xl">
    <?= csrf_field() ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold">Block Settings</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Site Area</label>
                <select name="area" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <?php foreach ($areas as $value => $label): ?>
                    <option value="<?= e($value) ?>" <?= ($item['area'] ?? 'home_operations') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Icon</label>
                <input type="text" name="icon" value="<?= e($item['icon'] ?? '') ?>" placeholder="globe"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="<?= e((string) ($item['sort_order'] ?? 0)) ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" <?= ($item['is_active'] ?? 1) ? 'checked' : '' ?> class="rounded">
            Active
        </label>
    </div>

    <?php
    $fields = [
        'title' => 'text',
        'body' => 'textarea',
    ];
    admin_partial('lang-tabs', compact('languages', 'item', 'fields'));
    ?>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
            <?= $isEdit ? 'Update Block' : 'Create Block' ?>
        </button>
        <a href="<?= admin_url('site-sections') ?>" class="text-slate-600 hover:text-slate-800 text-sm">Cancel</a>
    </div>
</form>

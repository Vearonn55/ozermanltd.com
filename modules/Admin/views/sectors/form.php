<?php $isEdit = $item !== null; ?>
<?php admin_partial('visibility-notice', ['message' => 'Sectors only appear when the Active checkbox is enabled.']); ?>
<form method="POST" action="<?= $isEdit ? admin_url('sectors/' . $item['id']) : admin_url('sectors') ?>" class="space-y-6 max-w-4xl"
      <?= $isEdit ? 'data-autosave-url="' . admin_url('sectors/' . $item['id'] . '/autosave') . '"' : '' ?>>
    <?= csrf_field() ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Sector Settings</h2>
            <?php $viewSlug = $item['translations']['en']['slug'] ?? null; ?>
            <?php if ($isEdit && !empty($item['is_active']) && $viewSlug): ?>
            <a href="<?= e(admin_preview_url('sectors/' . $viewSlug)) ?>" target="_blank" class="text-sm text-blue-600 hover:underline">View on site ↗</a>
            <?php endif; ?>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Icon</label>
                <input type="text" name="icon" value="<?= e($item['icon'] ?? '') ?>" placeholder="globe"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Color</label>
                <input type="text" name="color" value="<?= e($item['color'] ?? '') ?>" placeholder="#1e3a5f"
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
        <?php admin_partial('media-picker', ['fieldName' => 'media_id', 'value' => $item['media_id'] ?? null, 'label' => 'Sector image']); ?>
    </div>

    <?php
    $fields = [
        'name' => 'text',
        'slug' => 'text',
        'overview' => 'richtext',
        'services_text' => ['type' => 'textarea', 'label' => 'Services (one per line, prefix with •)'],
        'meta_title' => 'text',
        'meta_description' => 'textarea',
    ];
    admin_partial('lang-tabs', compact('languages', 'item', 'fields'));
    ?>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
            <?= $isEdit ? 'Update Sector' : 'Create Sector' ?>
        </button>
        <a href="<?= admin_url('sectors') ?>" class="text-slate-600 hover:text-slate-800 text-sm">Cancel</a>
    </div>
</form>

<?php if ($isEdit): ?>
<div class="max-w-4xl mt-6">
    <?php admin_partial('revisions', [
        'revisions' => $revisions ?? [],
        'restoredFrom' => $restoredFrom ?? null,
        'editUrl' => admin_url('sectors/' . $item['id'] . '/edit'),
    ]); ?>
</div>
<?php endif; ?>

<?php $isEdit = $item !== null; ?>
<?php admin_partial('visibility-notice', ['message' => 'Partnership brands appear on the public Partnerships page when Active is enabled.']); ?>
<form method="POST" action="<?= $isEdit ? admin_url('partnerships/' . $item['id']) : admin_url('partnerships') ?>" class="space-y-6 max-w-4xl">
    <?= csrf_field() ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold">Brand Settings</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Slug</label>
                <input type="text" name="slug" value="<?= e($item['slug'] ?? '') ?>" placeholder="auto-generated from name"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Website URL</label>
                <input type="text" name="website_url" value="<?= e($item['website_url'] ?? '') ?>" placeholder="https://…"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="<?= e((string) ($item['sort_order'] ?? 0)) ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
        <?php admin_partial('media-picker', ['fieldName' => 'media_id', 'value' => $item['media_id'] ?? null, 'label' => 'Brand logo / image']); ?>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Logo text (fallback shown when no image)</label>
            <input type="text" name="logo" value="<?= e($item['logo'] ?? '') ?>"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" <?= ($item['is_active'] ?? 1) ? 'checked' : '' ?> class="rounded">
            Active
        </label>
    </div>

    <?php
    $fields = [
        'name' => 'text',
        'tagline' => 'text',
        'description' => 'textarea',
    ];
    admin_partial('lang-tabs', compact('languages', 'item', 'fields'));
    ?>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
            <?= $isEdit ? 'Update Partnership' : 'Create Partnership' ?>
        </button>
        <a href="<?= admin_url('partnerships') ?>" class="text-slate-600 hover:text-slate-800 text-sm">Cancel</a>
    </div>
</form>

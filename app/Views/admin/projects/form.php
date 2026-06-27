<?php $isEdit = $item !== null; ?>
<?php admin_partial('visibility-notice', ['message' => 'Projects only appear when the Active checkbox is enabled.']); ?>
<form method="POST" action="<?= $isEdit ? admin_url('projects/' . $item['id']) : admin_url('projects') ?>" class="space-y-6 max-w-4xl">
    <?= csrf_field() ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold">Project Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                <select name="category_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">— None —</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($item['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <?php foreach (['planning', 'ongoing', 'completed'] as $st): ?>
                    <option value="<?= $st ?>" <?= ($item['status'] ?? 'planning') === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Location</label>
                <input type="text" name="location" value="<?= e($item['location'] ?? '') ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Delivery Date</label>
                <input type="text" name="delivery_date" value="<?= e($item['delivery_date'] ?? '') ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="June 2027">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Start Price</label>
                <input type="number" step="0.01" name="start_price" value="<?= e((string) ($item['start_price'] ?? '')) ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Currency</label>
                <input type="text" name="currency" value="<?= e($item['currency'] ?? 'GBP') ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
        <div class="flex gap-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_featured" value="1" <?= ($item['is_featured'] ?? 0) ? 'checked' : '' ?> class="rounded">
                Featured
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" <?= ($item['is_active'] ?? 1) ? 'checked' : '' ?> class="rounded">
                Active
            </label>
        </div>
    </div>

    <?php
    $fields = ['title' => 'text', 'slug' => 'text', 'description' => 'textarea', 'features' => 'textarea', 'payment_plan' => 'textarea', 'meta_title' => 'text', 'meta_description' => 'textarea'];
    admin_partial('lang-tabs', compact('languages', 'item', 'fields'));
    ?>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
            <?= $isEdit ? 'Update Project' : 'Create Project' ?>
        </button>
        <a href="<?= admin_url('projects') ?>" class="text-slate-600 hover:text-slate-800 text-sm">Cancel</a>
    </div>
</form>

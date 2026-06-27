<?php $isEdit = $item !== null; ?>
<?php admin_partial('visibility-notice', ['message' => 'News articles only appear when Status is Published. Fill in the English tab at minimum.']); ?>
<form method="POST" action="<?= $isEdit ? admin_url('news/' . $item['id']) : admin_url('news') ?>" class="space-y-6 max-w-4xl">
    <?= csrf_field() ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold">Article Settings</h2>
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
                    <option value="published" <?= ($item['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft (hidden on site)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Publish Date</label>
                <input type="datetime-local" name="publish_date"
                       value="<?= $item['publish_date'] ? date('Y-m-d\TH:i', strtotime($item['publish_date'])) : '' ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
        <div class="flex gap-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_featured" value="1" <?= ($item['is_featured'] ?? 0) ? 'checked' : '' ?> class="rounded">
                Featured
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_pinned" value="1" <?= ($item['is_pinned'] ?? 0) ? 'checked' : '' ?> class="rounded">
                Pinned
            </label>
        </div>
    </div>

    <?php
    $fields = ['title' => 'text', 'slug' => 'text', 'excerpt' => 'textarea', 'content' => 'textarea', 'meta_title' => 'text', 'meta_description' => 'textarea'];
    admin_partial('lang-tabs', compact('languages', 'item', 'fields'));
    ?>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
            <?= $isEdit ? 'Update Article' : 'Create Article' ?>
        </button>
        <a href="<?= admin_url('news') ?>" class="text-slate-600 hover:text-slate-800 text-sm">Cancel</a>
    </div>
</form>

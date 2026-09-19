<?php $isEdit = $item !== null; ?>
<?php admin_partial('visibility-notice', ['message' => 'News articles only appear when Status is Published and the publish date is not in the future (future dates schedule the article). Fill in the English tab at minimum.']); ?>
<form method="POST" action="<?= $isEdit ? admin_url('news/' . $item['id']) : admin_url('news') ?>" class="space-y-6 max-w-4xl"
      <?= $isEdit ? 'data-autosave-url="' . admin_url('news/' . $item['id'] . '/autosave') . '"' : '' ?>>
    <?= csrf_field() ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Article Settings</h2>
            <?php $viewSlug = $item['translations']['en']['slug'] ?? null; ?>
            <?php if ($isEdit && ($item['status'] ?? '') === 'published' && $viewSlug): ?>
            <a href="<?= e(admin_preview_url('news/' . $viewSlug)) ?>" target="_blank" class="text-sm text-blue-600 hover:underline">View on site ↗</a>
            <?php endif; ?>
        </div>
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
                    <option value="draft" <?= ($item['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft (hidden)</option>
                    <option value="pending_review" <?= ($item['status'] ?? '') === 'pending_review' ? 'selected' : '' ?>>Pending review</option>
                    <option value="published" <?= ($item['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Publish Date</label>
                <input type="datetime-local" name="publish_date"
                       value="<?= !empty($item['publish_date']) ? date('Y-m-d\TH:i', strtotime($item['publish_date'])) : '' ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <p class="text-xs text-slate-400 mt-1">Set a future date to schedule — the article goes live automatically at that time.</p>
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
        <?php
        $fieldName = 'featured_image_id';
        $value = $item['featured_image_id'] ?? null;
        $label = 'Featured image';
        admin_partial('media-picker', compact('fieldName', 'value', 'label'));
        ?>
    </div>

    <?php
    $fields = ['title' => 'text', 'slug' => 'text', 'excerpt' => 'textarea', 'content' => 'richtext', 'meta_title' => 'text', 'meta_description' => 'textarea'];
    admin_partial('lang-tabs', compact('languages', 'item', 'fields'));
    ?>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
            <?= $isEdit ? 'Update Article' : 'Create Article' ?>
        </button>
        <a href="<?= admin_url('news') ?>" class="text-slate-600 hover:text-slate-800 text-sm">Cancel</a>
    </div>
</form>

<?php if ($isEdit): ?>
<div class="max-w-4xl mt-6">
    <?php admin_partial('revisions', [
        'revisions' => $revisions ?? [],
        'restoredFrom' => $restoredFrom ?? null,
        'editUrl' => admin_url('news/' . $item['id'] . '/edit'),
    ]); ?>
</div>
<?php endif; ?>

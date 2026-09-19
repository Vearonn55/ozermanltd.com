<?php
$isEdit = $item !== null;
$slug = (string) ($item['slug'] ?? '');
$customPath = (string) ($item['custom_path'] ?? $slug);
$localePrefix = rtrim((string) admin_config('view_site_url', '/en'), '/');
?>
<form method="POST" action="<?= $isEdit ? admin_url('pages/' . $item['id']) : admin_url('pages') ?>" class="space-y-6"
      x-data="pageEditor({ slug: <?= json_encode($slug) ?>, path: <?= json_encode($customPath) ?>, prefix: <?= json_encode($localePrefix) ?> })"
      <?= $isEdit ? 'data-autosave-url="' . admin_url('pages/' . $item['id'] . '/autosave') . '"' : '' ?>>
    <?= csrf_field() ?>

    <div class="card">
        <div class="card-header flex items-center justify-between">
            <span>Page &amp; URL</span>
            <?php if ($isEdit && ($item['status'] ?? '') === 'published'): ?>
            <a href="<?= e(admin_preview_url($customPath === 'home' ? '' : $customPath)) ?>" target="_blank">View on site ↗</a>
            <?php endif; ?>
        </div>
        <div class="card-body space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" required x-model="slug" data-slug-target="single" class="form-control">
                </div>
                <div>
                    <label class="form-label">Public path</label>
                    <input type="text" name="custom_path" x-model="path" placeholder="campaigns/summer" class="form-control">
                    <p class="text-xs mt-1" style="color:var(--cui-muted)">Leave blank to use the slug. Nested paths are allowed (e.g. <code>offers/black-friday</code>).</p>
                </div>
                <div>
                    <label class="form-label">Template</label>
                    <select name="template" class="form-select">
                        <?php foreach (['custom' => 'Custom HTML embed', 'default' => 'Default (site content)', 'home' => 'Home', 'contact' => 'Contact'] as $tpl => $label): ?>
                        <option value="<?= $tpl ?>" <?= ($item['template'] ?? 'custom') === $tpl ? 'selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Render mode</label>
                    <select name="embed_mode" class="form-select">
                        <option value="site" <?= ($item['embed_mode'] ?? 'site') === 'site' ? 'selected' : '' ?>>Inside site layout (header/footer)</option>
                        <option value="blank" <?= ($item['embed_mode'] ?? '') === 'blank' ? 'selected' : '' ?>>Blank document (full HTML/CSS/JS/PHP)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= ($item['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= ($item['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Sort order</label>
                    <input type="number" name="sort_order" value="<?= e((string) ($item['sort_order'] ?? 0)) ?>" class="form-control">
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="show_in_nav" value="1" <?= ($item['show_in_nav'] ?? 0) ? 'checked' : '' ?>>
                Show in navigation
            </label>
            <div class="url-preview">
                Public URL:
                <strong x-text="fullUrl"></strong>
                <button type="button" class="btn btn-link" :data-copy="fullUrl">Copy</button>
            </div>
        </div>
    </div>

    <?php
    $fields = [
        'title' => 'text',
        'excerpt' => 'text',
        'content' => ['type' => 'richtext', 'label' => 'WYSIWYG content (optional)'],
        'html_embed' => ['type' => 'code', 'label' => 'HTML embed', 'lang' => 'html'],
        'css_embed' => ['type' => 'code', 'label' => 'CSS', 'lang' => 'css'],
        'js_embed' => ['type' => 'code', 'label' => 'JavaScript', 'lang' => 'js'],
        'php_embed' => ['type' => 'code', 'label' => 'PHP (runs on the server when the page loads)', 'lang' => 'php'],
        'meta_title' => 'text',
        'meta_description' => 'textarea',
    ];
    admin_partial('lang-tabs', compact('languages', 'item', 'fields'));
    ?>

    <p class="text-sm" style="color:var(--cui-muted)">
        Insert media from the library into the active HTML/CSS/JS/PHP editor with <strong>Insert media</strong>.
        PHP output is printed where the embed runs; HTML/CSS/JS are output as WordPress-style custom code.
    </p>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Page' : 'Create Page' ?></button>
        <a href="<?= admin_url('pages') ?>" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<?php if ($isEdit): ?>
<div class="mt-6">
    <?php admin_partial('revisions', [
        'revisions' => $revisions ?? [],
        'restoredFrom' => $restoredFrom ?? null,
        'editUrl' => admin_url('pages/' . $item['id'] . '/edit'),
    ]); ?>
</div>
<?php endif; ?>

<?php admin_partial('media-picker', ['fieldName' => 'embed-insert', 'label' => '', 'value' => null, 'pickerOnly' => true]); ?>

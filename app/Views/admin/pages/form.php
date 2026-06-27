<?php $isEdit = $item !== null; ?>
<?php admin_partial('visibility-notice', ['message' => 'Page content applies to the About page (slug: about-us) history section. Home, Contact, and other pages use fixed templates — edit News, Projects, or Sectors for those listings.']); ?>
<form method="POST" action="<?= $isEdit ? admin_url('pages/' . $item['id']) : admin_url('pages') ?>" class="space-y-6 max-w-4xl">
    <?= csrf_field() ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold">Page Settings</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Slug</label>
                <input type="text" name="slug" required value="<?= e($item['slug'] ?? '') ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Template</label>
                <select name="template" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <?php foreach (['default', 'home', 'contact', 'sector', 'project'] as $tpl): ?>
                    <option value="<?= $tpl ?>" <?= ($item['template'] ?? 'default') === $tpl ? 'selected' : '' ?>><?= ucfirst($tpl) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= ($item['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="<?= e((string) ($item['sort_order'] ?? 0)) ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="show_in_nav" value="1" <?= ($item['show_in_nav'] ?? 1) ? 'checked' : '' ?> class="rounded">
            Show in navigation
        </label>
    </div>

    <?php
    $fields = ['title' => 'text', 'excerpt' => 'text', 'content' => 'textarea', 'meta_title' => 'text', 'meta_description' => 'textarea'];
    admin_partial('lang-tabs', compact('languages', 'item', 'fields'));
    ?>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
            <?= $isEdit ? 'Update Page' : 'Create Page' ?>
        </button>
        <a href="<?= admin_url('pages') ?>" class="text-slate-600 hover:text-slate-800 text-sm">Cancel</a>
    </div>
</form>

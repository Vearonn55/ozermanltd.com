<?php $isEdit = $item !== null; ?>
<form method="POST" action="<?= $isEdit ? admin_url('seo/' . $item['id']) : admin_url('seo') ?>" class="space-y-6 max-w-3xl">
    <?= csrf_field() ?>
    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Entity type</label>
                <input type="text" name="entity_type" required value="<?= e($item['entity_type'] ?? 'page') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="page | news | project">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Entity ID</label>
                <input type="number" name="entity_id" required value="<?= e((string) ($item['entity_id'] ?? '')) ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Language</label>
                <select name="language_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <?php foreach ($languages as $lang): ?>
                    <option value="<?= (int) $lang['id'] ?>" <?= (int) ($item['language_id'] ?? 0) === (int) $lang['id'] ? 'selected' : '' ?>><?= e($lang['code']) ?> — <?= e($lang['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Meta title</label>
            <input type="text" name="meta_title" value="<?= e($item['meta_title'] ?? '') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Meta description</label>
            <textarea name="meta_description" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"><?= e($item['meta_description'] ?? '') ?></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">OG title</label>
                <input type="text" name="og_title" value="<?= e($item['og_title'] ?? '') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Canonical URL</label>
                <input type="text" name="canonical_url" value="<?= e($item['canonical_url'] ?? '') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">OG description</label>
            <textarea name="og_description" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"><?= e($item['og_description'] ?? '') ?></textarea>
        </div>
        <?php
        $fieldName = 'og_image_id';
        $value = $item['og_image_id'] ?? null;
        $label = 'OG image';
        admin_partial('media-picker', compact('fieldName', 'value', 'label'));
        ?>
        <div>
            <label class="block text-sm font-medium mb-1">Robots</label>
            <input type="text" name="robots" value="<?= e($item['robots'] ?? 'index, follow') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
    </div>
    <div class="flex gap-3">
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg"><?= $isEdit ? 'Update' : 'Create' ?></button>
        <a href="<?= admin_url('seo') ?>" class="text-sm self-center text-slate-600 hover:underline">Cancel</a>
    </div>
</form>

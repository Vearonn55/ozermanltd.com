<?php $isEdit = $item !== null; ?>
<form method="POST" action="<?= $isEdit ? admin_url('locales/' . $item['id']) : admin_url('locales') ?>" class="space-y-6 max-w-xl">
    <?= csrf_field() ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Code</label>
            <input type="text" name="code" value="<?= e($item['code'] ?? '') ?>" maxlength="5" required
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm uppercase"
                   placeholder="de">
            <p class="text-xs text-slate-500 mt-1">2 to 5 letters. This becomes the address prefix, such as /de.</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Name</label>
            <input type="text" name="name" value="<?= e($item['name'] ?? '') ?>" required
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                   placeholder="Deutsch">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Direction</label>
            <select name="dir" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="ltr" <?= ($item['dir'] ?? 'ltr') === 'ltr' ? 'selected' : '' ?>>Left to right</option>
                <option value="rtl" <?= ($item['dir'] ?? '') === 'rtl' ? 'selected' : '' ?>>Right to left</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Sort order</label>
            <input type="number" name="sort_order" value="<?= e((string) ($item['sort_order'] ?? 0)) ?>"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" <?= ($item['is_active'] ?? 1) ? 'checked' : '' ?> class="rounded">
            Enabled on the website
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_default" value="1" <?= !empty($item['is_default']) ? 'checked' : '' ?> class="rounded">
            Main language
        </label>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
            <?= $isEdit ? 'Update language' : 'Add language' ?>
        </button>
        <a href="<?= admin_url('locales') ?>" class="text-slate-600 hover:text-slate-800 text-sm">Cancel</a>
    </div>
</form>

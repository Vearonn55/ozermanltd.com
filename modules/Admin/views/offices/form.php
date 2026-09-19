<?php $isEdit = $item !== null; ?>
<?php admin_partial('visibility-notice', ['message' => 'Offices appear on the public Contact page when Active is enabled.']); ?>
<form method="POST" action="<?= $isEdit ? admin_url('offices/' . $item['id']) : admin_url('offices') ?>" class="space-y-6 max-w-4xl">
    <?= csrf_field() ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold">Office Settings</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                <input type="text" name="phone" value="<?= e($item['phone'] ?? '') ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="<?= e($item['email'] ?? '') ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">WhatsApp</label>
                <input type="text" name="whatsapp" value="<?= e($item['whatsapp'] ?? '') ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_headquarters" value="1" <?= !empty($item['is_headquarters']) ? 'checked' : '' ?> class="rounded">
                Headquarters
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" <?= ($item['is_active'] ?? 1) ? 'checked' : '' ?> class="rounded">
                Active
            </label>
        </div>
    </div>

    <?php
    $fields = [
        'label' => 'text',
        'address' => 'textarea',
        'city' => 'text',
        'country' => 'text',
        'working_hours' => 'text',
    ];
    admin_partial('lang-tabs', compact('languages', 'item', 'fields'));
    ?>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
            <?= $isEdit ? 'Update Office' : 'Create Office' ?>
        </button>
        <a href="<?= admin_url('offices') ?>" class="text-slate-600 hover:text-slate-800 text-sm">Cancel</a>
    </div>
</form>

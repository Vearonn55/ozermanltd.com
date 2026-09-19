<?php
$fieldName = $fieldName ?? 'featured_image_id';
$value = $value ?? null;
$label = $label ?? 'Featured image';
$pickerOnly = !empty($pickerOnly);
$eventName = 'admin-media-picked-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $fieldName);

$currentUrl = '';
if ($value) {
    try {
        $currentMedia = (new \Admin\Repositories\MediaRepository())->find((int) $value);
        if ($currentMedia && ($currentMedia['file_type'] ?? '') === 'image') {
            $currentUrl = admin_media_url($currentMedia['file_path']);
        }
    } catch (\Throwable) {
        // Preview is optional; ignore lookup failures.
    }
}
?>
<div class="space-y-2"
     x-data="{ open: false, id: '<?= e((string) ($value ?? '')) ?>', url: '<?= e($currentUrl) ?>' }"
     @<?= e($eventName) ?>.window="id = String($event.detail.id); url = String($event.detail.url || ''); open = false"
     @admin-open-embed-picker.window="open = true">
    <?php if (!$pickerOnly && $label !== ''): ?>
    <label class="block text-sm font-medium text-slate-700"><?= e($label) ?></label>
    <?php endif; ?>
    <div class="flex flex-wrap items-center gap-3" <?= $pickerOnly ? 'style="display:none"' : '' ?>>
        <input type="hidden" name="<?= e($fieldName) ?>" :value="id">
        <template x-if="url">
            <img :src="url" alt="" class="w-16 h-16 object-cover rounded-lg border border-slate-200">
        </template>
        <span class="text-sm text-slate-500" x-text="id ? ('Media #' + id) : 'None selected'"></span>
        <button type="button" @click="open = true" class="text-sm text-blue-600 hover:underline">Browse library</button>
        <button type="button" @click="id = ''; url = ''" class="text-sm text-slate-500 hover:underline" x-show="id" x-cloak>Clear</button>
    </div>
    <div x-show="open" x-cloak class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl h-[80vh] flex flex-col overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b">
                <h3 class="font-semibold text-slate-800">Media Library</h3>
                <button type="button" @click="open = false" class="text-slate-500 hover:text-slate-800">Close</button>
            </div>
            <iframe class="flex-1 w-full border-0" src="<?= e(admin_url('media') . '?picker=1&field=' . urlencode($fieldName)) ?>"></iframe>
        </div>
    </div>
</div>
<script>
(function () {
    if (window.__adminMediaPickerBound) return;
    window.__adminMediaPickerBound = true;
    window.addEventListener('message', function (event) {
        if (!event.data || event.data.type !== 'admin-media-select') return;
        var name = 'admin-media-picked-' + String(event.data.field || '').replace(/[^a-zA-Z0-9_-]/g, '');
        window.dispatchEvent(new CustomEvent(name, { detail: event.data }));
    });
})();
</script>

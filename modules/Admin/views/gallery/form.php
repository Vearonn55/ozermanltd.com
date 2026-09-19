<?php
$isEdit = $item !== null;
$existingItems = [];
foreach (($item['items'] ?? []) as $galleryItem) {
    $existingItems[] = [
        'media_id' => (int) $galleryItem['media_id'],
        'caption' => (string) ($galleryItem['caption'] ?? ''),
        'url' => ($galleryItem['file_type'] ?? '') === 'image' && !empty($galleryItem['file_path'])
            ? admin_media_url($galleryItem['file_path'])
            : '',
    ];
}
?>
<?php admin_partial('visibility-notice', ['message' => 'Collections appear on the public Gallery page when Active is enabled.']); ?>
<form method="POST" action="<?= $isEdit ? admin_url('gallery/' . $item['id']) : admin_url('gallery') ?>" class="space-y-6 max-w-4xl">
    <?= csrf_field() ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold">Collection Settings</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="<?= e((string) ($item['sort_order'] ?? 0)) ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
        <?php admin_partial('media-picker', ['fieldName' => 'cover_image_id', 'value' => $item['cover_image_id'] ?? null, 'label' => 'Cover image']); ?>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" <?= ($item['is_active'] ?? 1) ? 'checked' : '' ?> class="rounded">
            Active
        </label>
    </div>

    <?php
    $fields = [
        'title' => 'text',
        'slug' => 'text',
        'description' => 'textarea',
    ];
    admin_partial('lang-tabs', compact('languages', 'item', 'fields'));
    ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4"
         x-data='{ open: false, rows: <?= json_encode($existingItems, JSON_HEX_APOS | JSON_HEX_QUOT) ?> }'
         @admin-media-picked-gallery_add.window="rows.push({ media_id: $event.detail.id, caption: '', url: $event.detail.url || '' })">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Images</h2>
            <button type="button" @click="open = true" class="text-sm text-blue-600 hover:underline">+ Add from library</button>
        </div>

        <p class="text-sm text-slate-500" x-show="rows.length === 0">No images yet. Use “Add from library” to pick images; you can select several in a row.</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <template x-for="(row, idx) in rows" :key="idx">
                <div class="border border-slate-200 rounded-lg overflow-hidden">
                    <template x-if="row.url">
                        <img :src="row.url" alt="" class="w-full h-24 object-cover">
                    </template>
                    <template x-if="!row.url">
                        <div class="h-24 flex items-center justify-center text-xs text-slate-400" x-text="'Media #' + row.media_id"></div>
                    </template>
                    <div class="p-2 space-y-1">
                        <input type="hidden" :name="'items[' + idx + '][media_id]'" :value="row.media_id">
                        <input type="hidden" :name="'items[' + idx + '][sort_order]'" :value="idx">
                        <input type="text" :name="'items[' + idx + '][caption]'" x-model="row.caption" placeholder="Caption"
                               class="w-full rounded border border-slate-300 px-2 py-1 text-xs">
                        <div class="flex justify-between text-xs">
                            <button type="button" class="text-slate-500 hover:underline" @click="if (idx > 0) rows.splice(idx - 1, 0, rows.splice(idx, 1)[0])">← Move</button>
                            <button type="button" class="text-red-600 hover:underline" @click="rows.splice(idx, 1)">Remove</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="open" x-cloak class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl h-[80vh] flex flex-col overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="font-semibold text-slate-800">Media Library — click images to add, then close</h3>
                    <button type="button" @click="open = false" class="text-slate-500 hover:text-slate-800">Done</button>
                </div>
                <iframe class="flex-1 w-full border-0" src="<?= e(admin_url('media') . '?picker=1&field=gallery_add') ?>"></iframe>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
            <?= $isEdit ? 'Update Collection' : 'Create Collection' ?>
        </button>
        <a href="<?= admin_url('gallery') ?>" class="text-slate-600 hover:text-slate-800 text-sm">Cancel</a>
    </div>
</form>
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

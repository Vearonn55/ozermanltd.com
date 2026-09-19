<?php admin_partial('visibility-notice', ['message' => 'These banners appear on public page headers and home extras. Upload via Media Library, then select here. Fallback URL is used when no media is selected.']); ?>

<div class="space-y-4 max-w-5xl">
    <?php foreach ($items as $item): ?>
    <form method="POST" action="<?= admin_url('banners/' . $item['location']) ?>" class="bg-white rounded-xl border border-slate-200 p-5 grid md:grid-cols-[1fr_220px] gap-5 items-start">
        <?= csrf_field() ?>
        <div class="space-y-3">
            <div>
                <h2 class="font-semibold text-slate-900"><?= e($item['label']) ?></h2>
                <p class="text-xs text-slate-500 mt-0.5">Location key: <code><?= e($item['location']) ?></code></p>
            </div>
            <?php
            admin_partial('media-picker', [
                'fieldName' => 'media_id',
                'value' => $item['media_id'] ?? null,
                'label' => 'Banner image',
            ]);
            ?>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fallback image URL</label>
                <input type="url" name="fallback_url" value="<?= e((string) ($item['fallback_url'] ?? '')) ?>"
                       placeholder="https://..."
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">Save banner</button>
        </div>
        <div class="rounded-lg border border-slate-200 overflow-hidden bg-slate-50">
            <?php if (!empty($item['preview'])): ?>
            <img src="<?= e($item['preview']) ?>" alt="" class="w-full h-36 object-cover">
            <?php else: ?>
            <div class="h-36 flex items-center justify-center text-xs text-slate-400">No image</div>
            <?php endif; ?>
        </div>
    </form>
    <?php endforeach; ?>
</div>

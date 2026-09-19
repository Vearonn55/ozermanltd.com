<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="<?= admin_url('media') ?>" class="flex gap-2">
            <input type="hidden" name="picker" value="1">
            <input type="hidden" name="field" value="<?= e($field) ?>">
            <input type="search" name="q" value="<?= e($search) ?>" placeholder="Search media…"
                   class="rounded-lg border border-slate-300 px-3 py-2 text-sm w-56">
            <button class="bg-slate-800 text-white text-sm px-3 py-2 rounded-lg">Search</button>
        </form>

        <form method="POST" action="<?= admin_url('media') ?>" enctype="multipart/form-data" class="flex items-center gap-2">
            <?= csrf_field() ?>
            <input type="hidden" name="picker" value="1">
            <input type="hidden" name="field" value="<?= e($field) ?>">
            <input type="file" name="file" required class="text-xs w-48">
            <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-2 rounded-lg">Upload</button>
        </form>
    </div>

    <p class="text-sm text-slate-500">Click an item to select it.</p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <?php foreach ($items as $item): ?>
        <button type="button"
                class="bg-white border border-slate-200 rounded-lg overflow-hidden text-left hover:border-blue-500"
                onclick="window.parent.postMessage({type:'admin-media-select',field:'<?= e($field) ?>',id:<?= (int) $item['id'] ?>,url:'<?= e(admin_media_url($item['file_path'])) ?>',name:'<?= e($item['original_name']) ?>'}, '*')">
            <?php if (($item['file_type'] ?? '') === 'image'): ?>
            <img src="<?= e(admin_media_url($item['file_path'])) ?>" alt="" loading="lazy" class="w-full h-24 object-cover">
            <?php else: ?>
            <div class="h-24 flex items-center justify-center text-xs text-slate-400 px-2"><?= e($item['original_name']) ?></div>
            <?php endif; ?>
            <div class="p-2 text-xs truncate">#<?= (int) $item['id'] ?> · <?= e($item['original_name']) ?></div>
        </button>
        <?php endforeach; ?>
    </div>

    <?php if (!$items): ?>
    <p class="text-sm text-slate-500">No media found<?= $search !== '' ? ' for “' . e($search) . '”' : '' ?>. Upload a file above.</p>
    <?php endif; ?>

    <?php if (($pages ?? 1) > 1): ?>
    <nav class="flex items-center justify-between text-sm">
        <div>
            <?php if ($page > 1): ?>
            <a href="<?= admin_url('media') . '?' . http_build_query(array_filter(['q' => $search]) + ['picker' => 1, 'field' => $field, 'page' => $page - 1]) ?>"
               class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50">← Previous</a>
            <?php endif; ?>
        </div>
        <span class="text-slate-500">Page <?= (int) $page ?> of <?= (int) $pages ?></span>
        <div>
            <?php if ($page < $pages): ?>
            <a href="<?= admin_url('media') . '?' . http_build_query(array_filter(['q' => $search]) + ['picker' => 1, 'field' => $field, 'page' => $page + 1]) ?>"
               class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50">Next →</a>
            <?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>
</div>

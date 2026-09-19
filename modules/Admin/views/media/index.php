<div class="space-y-6" x-data="adminBulk()">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="<?= admin_url('media') ?>" class="flex gap-2">
            <?php if ($currentFolderId): ?><input type="hidden" name="folder" value="<?= (int) $currentFolderId ?>"><?php endif; ?>
            <input type="search" name="q" value="<?= e($search) ?>" placeholder="Search media…"
                   class="rounded-lg border border-slate-300 px-3 py-2 text-sm w-64">
            <button class="bg-slate-800 text-white text-sm px-4 py-2 rounded-lg">Search</button>
        </form>
        <span class="text-sm text-slate-500"><?= (int) $total ?> file(s)</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
            <h2 class="font-semibold">Upload</h2>
            <form method="POST" action="<?= admin_url('media') ?>" enctype="multipart/form-data" class="space-y-3">
                <?= csrf_field() ?>
                <?php if ($currentFolderId): ?>
                <input type="hidden" name="folder_id" value="<?= (int) $currentFolderId ?>">
                <?php endif; ?>
                <input type="file" name="file" required class="block w-full text-sm">
                <input type="text" name="alt_text" placeholder="Alt text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <input type="text" name="caption" placeholder="Caption (optional)" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">Upload</button>
            </form>

            <hr class="border-slate-100">

            <h2 class="font-semibold">New folder</h2>
            <form method="POST" action="<?= admin_url('media/folders') ?>" class="space-y-3">
                <?= csrf_field() ?>
                <?php if ($currentFolderId): ?>
                <input type="hidden" name="parent_id" value="<?= (int) $currentFolderId ?>">
                <?php endif; ?>
                <input type="text" name="name" required placeholder="Folder name" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <button class="bg-slate-700 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2 rounded-lg">Create folder</button>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <a href="<?= admin_url('media') ?>" class="hover:underline">Library</a>
                <?php if ($currentFolder): ?>
                <span>/</span>
                <span class="text-slate-800"><?= e($currentFolder['name']) ?></span>
                <?php endif; ?>
            </div>

            <?php if ($folders): ?>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($folders as $folder): ?>
                <a href="<?= admin_url('media?folder=' . $folder['id']) ?>"
                   class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-200 text-sm hover:bg-slate-300"><?= e($folder['name']) ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($canDelete): ?>
            <form id="bulk-form" method="POST" action="<?= admin_url('media/bulk') ?>"
                  onsubmit="return confirm('Delete the selected files? Files still referenced by content will be skipped.')"
                  class="flex items-center gap-2" x-show="selected.length > 0" x-cloak>
                <?= csrf_field() ?>
                <input type="hidden" name="bulk_action" value="delete">
                <span class="text-sm text-slate-600" x-text="selected.length + ' selected'"></span>
                <button class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-1.5 rounded-lg">Delete selected</button>
            </form>
            <?php endif; ?>

            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                <?php foreach ($items as $item): ?>
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                    <div class="relative">
                        <?php if (($item['file_type'] ?? '') === 'image'): ?>
                        <img src="<?= e(admin_media_url($item['file_path'])) ?>" alt="<?= e($item['alt_text'] ?? '') ?>" loading="lazy" class="w-full h-32 object-cover">
                        <?php else: ?>
                        <div class="h-32 flex items-center justify-center bg-slate-50 text-slate-400 text-xs px-2 text-center"><?= e($item['original_name']) ?></div>
                        <?php endif; ?>
                        <?php if ($canDelete): ?>
                        <label class="absolute top-2 left-2 bg-white/90 rounded p-1">
                            <input type="checkbox" form="bulk-form" name="ids[]" value="<?= (int) $item['id'] ?>" class="rounded"
                                   :checked="has(<?= (int) $item['id'] ?>)" @change="toggle(<?= (int) $item['id'] ?>)">
                        </label>
                        <?php endif; ?>
                    </div>
                    <div class="p-3 space-y-2">
                        <p class="text-xs font-medium truncate" title="<?= e($item['original_name']) ?>"><?= e($item['original_name']) ?></p>
                        <p class="text-[11px] text-slate-400">
                            #<?= (int) $item['id'] ?>
                            · <button type="button" data-copy="<?= e(admin_media_url($item['file_path'])) ?>" class="text-blue-600 hover:underline">Copy URL</button>
                        </p>
                        <form method="POST" action="<?= admin_url('media/' . $item['id']) ?>" class="space-y-2">
                            <?= csrf_field() ?>
                            <input type="text" name="alt_text" value="<?= e($item['alt_text'] ?? '') ?>" placeholder="Alt" class="w-full rounded border border-slate-300 px-2 py-1 text-xs">
                            <input type="text" name="caption" value="<?= e($item['caption'] ?? '') ?>" placeholder="Caption" class="w-full rounded border border-slate-300 px-2 py-1 text-xs">
                            <select name="folder_id" class="w-full rounded border border-slate-300 px-2 py-1 text-xs">
                                <option value="">— Root —</option>
                                <?php foreach ($allFolders as $folder): ?>
                                <option value="<?= (int) $folder['id'] ?>" <?= (int) ($item['folder_id'] ?? 0) === (int) $folder['id'] ? 'selected' : '' ?>><?= e($folder['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="flex gap-2">
                                <button class="text-xs text-blue-600 hover:underline">Save</button>
                                <?php if ($canDelete): ?>
                                <button name="_method" value="DELETE" onclick="return confirm('Delete this file?')" class="text-xs text-red-600 hover:underline">Delete</button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (!$items): ?>
            <p class="text-sm text-slate-500">No media in this folder yet. Upload files above — do not use cPanel File Manager for routine media.</p>
            <?php endif; ?>

            <?php if (($pages ?? 1) > 1): ?>
            <nav class="flex items-center justify-between text-sm">
                <div>
                    <?php if ($page > 1): ?>
                    <a href="<?= admin_url('media') . '?' . http_build_query(array_filter(['folder' => $currentFolderId, 'q' => $search]) + ['page' => $page - 1]) ?>"
                       class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50">← Previous</a>
                    <?php endif; ?>
                </div>
                <span class="text-slate-500">Page <?= (int) $page ?> of <?= (int) $pages ?></span>
                <div>
                    <?php if ($page < $pages): ?>
                    <a href="<?= admin_url('media') . '?' . http_build_query(array_filter(['folder' => $currentFolderId, 'q' => $search]) + ['page' => $page + 1]) ?>"
                       class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50">Next →</a>
                    <?php endif; ?>
                </div>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

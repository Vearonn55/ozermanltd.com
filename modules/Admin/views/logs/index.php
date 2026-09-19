<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-2">
        <h2 class="font-semibold text-sm">Log files</h2>
        <p class="text-xs text-slate-500 break-all"><?= e($logDir) ?></p>
        <?php if (!$files): ?>
        <p class="text-sm text-slate-500">No log files found. PHP/app logs written to <code>storage/logs/</code> will appear here.</p>
        <?php endif; ?>
        <ul class="space-y-1">
            <?php foreach ($files as $file): ?>
            <li>
                <a href="<?= admin_url('logs?file=' . urlencode($file['name'])) ?>"
                   class="text-sm <?= $selected === $file['name'] ? 'text-blue-700 font-medium' : 'text-slate-600 hover:underline' ?>">
                    <?= e($file['name']) ?>
                </a>
                <div class="text-[11px] text-slate-400"><?= number_format($file['size'] / 1024, 1) ?> KB</div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="lg:col-span-3 bg-slate-900 text-slate-100 rounded-xl p-4 overflow-auto max-h-[70vh]">
        <pre class="text-xs whitespace-pre-wrap font-mono"><?= e($content !== '' ? $content : 'Select a log file.') ?></pre>
    </div>
</div>

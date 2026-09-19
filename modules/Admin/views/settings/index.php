<?php
$maintenance = '0';
foreach ($grouped['system'] ?? [] as $row) {
    if ($row['key'] === 'maintenance_mode') {
        $maintenance = (string) $row['value'];
    }
}
?>
<form method="POST" action="<?= admin_url('settings') ?>" class="space-y-6 max-w-3xl">
    <?= csrf_field() ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <h2 class="font-semibold">System</h2>
        <label class="flex items-center gap-2 text-sm">
            <input type="hidden" name="maintenance_mode" value="0">
            <input type="checkbox" name="maintenance_mode" value="1" <?= $maintenance === '1' ? 'checked' : '' ?> class="rounded">
            Maintenance mode
        </label>
        <p class="text-xs text-slate-500">Stored in <code>settings</code> — not a hardcoded constant. Wire public middleware when enabling for production.</p>
    </div>

    <?php foreach ($grouped as $group => $rows): ?>
        <?php if ($group === 'system') continue; ?>
        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h2 class="font-semibold capitalize"><?= e($group) ?></h2>
            <?php foreach ($rows as $row): ?>
            <div>
                <label class="block text-sm font-medium mb-1"><?= e($row['label'] ?: $row['key']) ?></label>
                <input type="text" name="settings[<?= (int) $row['id'] ?>]" value="<?= e((string) $row['value']) ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">Save settings</button>
</form>

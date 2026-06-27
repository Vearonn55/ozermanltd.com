<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="<?= admin_url('contacts') ?>" class="text-sm text-blue-600 hover:underline">&larr; Back to messages</a>
        <?php $status = $item['status']; require APP_PATH . '/Views/admin/partials/status-badge.php'; ?>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-lg font-semibold mb-4"><?= e($item['subject'] ?? 'Contact Message') ?></h2>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
            <div><dt class="text-slate-500">Name</dt><dd class="font-medium"><?= e($item['name']) ?></dd></div>
            <div><dt class="text-slate-500">Email</dt><dd><a href="mailto:<?= e($item['email']) ?>" class="text-blue-600 hover:underline"><?= e($item['email']) ?></a></dd></div>
            <?php if ($item['phone']): ?><div><dt class="text-slate-500">Phone</dt><dd><?= e($item['phone']) ?></dd></div><?php endif; ?>
            <?php if ($item['country']): ?><div><dt class="text-slate-500">Country</dt><dd><?= e($item['country']) ?></dd></div><?php endif; ?>
            <div><dt class="text-slate-500">Received</dt><dd><?= date('F j, Y \a\t g:i A', strtotime($item['created_at'])) ?></dd></div>
            <?php if ($item['ip_address']): ?><div><dt class="text-slate-500">IP</dt><dd class="font-mono text-xs"><?= e($item['ip_address']) ?></dd></div><?php endif; ?>
        </dl>
        <div class="border-t border-slate-100 pt-4">
            <p class="text-sm text-slate-700 whitespace-pre-wrap"><?= e($item['message']) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-sm font-semibold mb-3">Update Status</h3>
        <form method="POST" action="<?= admin_url('contacts/' . $item['id'] . '/status') ?>" class="flex items-center gap-3">
            <?= csrf_field() ?>
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <?php foreach (['new', 'read', 'replied', 'archived'] as $st): ?>
                <option value="<?= $st ?>" <?= $item['status'] === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">Update</button>
        </form>
    </div>

    <form method="POST" action="<?= admin_url('contacts/' . $item['id']) ?>" onsubmit="return confirm('Delete this message?')">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="text-red-600 hover:text-red-700 text-sm">Delete message</button>
    </form>
</div>

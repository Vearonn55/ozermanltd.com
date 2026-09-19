<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <div class="text-sm text-slate-600">
        <?= (int) ($pendingCount ?? 0) ?> pending review<?= ((int) ($pendingCount ?? 0) === 1) ? '' : 's' ?>
    </div>
    <form method="GET" action="<?= admin_url('comments') ?>" class="flex gap-2">
        <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
            <option value="">All statuses</option>
            <?php foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'spam' => 'Spam'] as $value => $label): ?>
            <option value="<?= $value ?>" <?= ($statusFilter ?? '') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="bg-slate-800 text-white text-sm px-3 py-2 rounded-lg">Filter</button>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
            <tr>
                <th class="px-4 py-3 font-medium">Author</th>
                <th class="px-4 py-3 font-medium">Article</th>
                <th class="px-4 py-3 font-medium">Review</th>
                <th class="px-4 py-3 font-medium">Status</th>
                <th class="px-4 py-3 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php if (empty($items)): ?>
            <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">No reviews yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($items as $item): ?>
            <tr>
                <td class="px-4 py-3 align-top">
                    <div class="font-medium text-slate-900"><?= e($item['author_name']) ?></div>
                    <div class="text-xs text-slate-500"><?= e($item['author_email']) ?></div>
                    <div class="text-xs text-slate-400 mt-1"><?= e(date('M j, Y H:i', strtotime($item['created_at']))) ?></div>
                </td>
                <td class="px-4 py-3 align-top">
                    <?php if (!empty($item['article_slug'])): ?>
                    <a href="<?= e(admin_preview_url('news/' . $item['article_slug'])) ?>" target="_blank" class="text-blue-600 hover:underline">
                        <?= e($item['article_title'] ?? 'Article') ?>
                    </a>
                    <?php else: ?>
                    <span class="text-slate-500">#<?= (int) $item['news_id'] ?></span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3 align-top max-w-md">
                    <p class="text-slate-700 whitespace-pre-wrap"><?= e($item['content']) ?></p>
                </td>
                <td class="px-4 py-3 align-top">
                    <?php $status = $item['status']; require ADMIN_MODULE_PATH . '/views/partials/status-badge.php'; ?>
                </td>
                <td class="px-4 py-3 align-top space-y-1">
                    <?php if ($item['status'] !== 'approved'): ?>
                    <form method="POST" action="<?= admin_url('comments/' . $item['id'] . '/status') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="status" value="approved">
                        <button class="text-emerald-600 hover:underline text-xs">Approve</button>
                    </form>
                    <?php endif; ?>
                    <?php if ($item['status'] !== 'rejected'): ?>
                    <form method="POST" action="<?= admin_url('comments/' . $item['id'] . '/status') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="status" value="rejected">
                        <button class="text-amber-600 hover:underline text-xs">Reject</button>
                    </form>
                    <?php endif; ?>
                    <form method="POST" action="<?= admin_url('comments/' . $item['id'] . '/status') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="status" value="spam">
                        <button class="text-orange-600 hover:underline text-xs">Spam</button>
                    </form>
                    <form method="POST" action="<?= admin_url('comments/' . $item['id']) ?>" onsubmit="return confirm('Delete this review?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="text-red-600 hover:underline text-xs">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php admin_partial('pagination', ['page' => $page, 'pages' => $pages, 'action' => admin_url('comments'), 'q' => '', 'statusFilter' => $statusFilter]); ?>

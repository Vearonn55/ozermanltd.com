<div class="flex items-center gap-2 mb-6">
    <?php
    $filters = ['' => 'All', 'new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'archived' => 'Archived'];
    foreach ($filters as $val => $label):
        $active = ($currentStatus ?? '') === $val;
    ?>
    <a href="<?= admin_url('contacts' . ($val !== '' ? '?status=' . $val : '')) ?>"
       class="px-3 py-1.5 rounded-lg text-sm font-medium <?= $active ? 'bg-blue-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
        <?= e($label) ?>
        <?php if ($val === ''): ?>(<?= (int) $statusCounts['all'] ?>)<?php endif; ?>
        <?php if ($val === 'new'): ?>(<?= (int) $statusCounts['new'] ?>)<?php endif; ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <?php if (empty($items)): ?>
    <p class="p-8 text-center text-slate-500 text-sm">No messages found.</p>
    <?php else: ?>
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-600">From</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Subject</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Status</th>
                <th class="text-left px-4 py-3 font-medium text-slate-600">Date</th>
                <th class="text-right px-4 py-3 font-medium text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($items as $item): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3">
                    <p class="font-medium"><?= e($item['name']) ?></p>
                    <p class="text-xs text-slate-500"><?= e($item['email']) ?></p>
                </td>
                <td class="px-4 py-3 text-slate-600"><?= e($item['subject'] ?? '(no subject)') ?></td>
                <td class="px-4 py-3"><?php $status = $item['status']; require APP_PATH . '/Views/admin/partials/status-badge.php'; ?></td>
                <td class="px-4 py-3 text-slate-500"><?= date('M j, Y H:i', strtotime($item['created_at'])) ?></td>
                <td class="px-4 py-3 text-right">
                    <a href="<?= admin_url('contacts/' . $item['id']) ?>" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php
/**
 * Revision history panel shown on edit forms.
 *
 * @var array       $revisions    rows from RevisionRepository::listFor()
 * @var int|null    $restoredFrom revision id currently loaded into the form
 * @var string      $editUrl      base edit URL, e.g. admin_url('news/5/edit')
 */
?>
<div class="bg-white rounded-xl border border-slate-200 p-6 space-y-3">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Revisions</h2>
        <span class="text-xs text-slate-400" data-autosave-status></span>
    </div>

    <?php if (!empty($restoredFrom)): ?>
    <div class="rounded-lg bg-amber-50 border border-amber-200 text-amber-800 px-3 py-2 text-sm">
        The form is pre-filled from revision #<?= (int) $restoredFrom ?>. Click <strong>Save</strong> to apply it, or
        <a href="<?= e($editUrl) ?>" class="underline">discard</a>.
    </div>
    <?php endif; ?>

    <?php if (empty($revisions)): ?>
    <p class="text-sm text-slate-500">No revisions yet. A snapshot is stored every time you save, and drafts are autosaved while you type.</p>
    <?php else: ?>
    <ul class="divide-y divide-slate-100 text-sm">
        <?php foreach ($revisions as $rev): ?>
        <li class="py-2 flex items-center justify-between gap-3">
            <div>
                <span class="font-medium text-slate-700">
                    <?= $rev['is_autosave'] ? 'Autosave' : 'Revision' ?> #<?= (int) $rev['id'] ?>
                </span>
                <span class="text-slate-400">
                    · <?= e($rev['user_name'] ?? 'Unknown') ?> · <?= e(date('M j, Y H:i', strtotime($rev['created_at']))) ?>
                </span>
            </div>
            <a href="<?= e($editUrl . '?revision=' . (int) $rev['id']) ?>"
               class="text-blue-600 hover:underline shrink-0">Restore</a>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>

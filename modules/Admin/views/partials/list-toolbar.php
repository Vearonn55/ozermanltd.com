<?php
/**
 * Search + status filter toolbar for index views.
 *
 * @var string $action        list URL (admin_url path)
 * @var string $q             current search term
 * @var string $statusFilter  current status filter value
 * @var array  $statusOptions value => label, e.g. ['published' => 'Published', 'draft' => 'Draft']
 * @var int    $total         total matching rows
 * @var string $createUrl     URL of the create form
 * @var string $createLabel   label of the create button
 */
?>
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <form method="GET" action="<?= e($action) ?>" class="flex flex-wrap items-center gap-2">
        <input type="search" name="q" value="<?= e($q ?? '') ?>" placeholder="Search…"
               class="form-control" style="width:14rem">
        <?php if (!empty($statusOptions)): ?>
        <select name="status" class="form-select" style="width:auto" onchange="this.form.submit()">
            <option value="">All statuses</option>
            <?php foreach ($statusOptions as $value => $label): ?>
            <option value="<?= e((string) $value) ?>" <?= ($statusFilter ?? '') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <button class="btn btn-secondary">Filter</button>
        <?php if (($q ?? '') !== '' || ($statusFilter ?? '') !== ''): ?>
        <a href="<?= e($action) ?>" class="btn btn-link">Reset</a>
        <?php endif; ?>
        <span class="text-sm ml-2" style="color:var(--cui-muted)"><?= (int) ($total ?? 0) ?> item(s)</span>
    </form>
    <?php if (!empty($createUrl)): ?>
    <a href="<?= e($createUrl) ?>" class="btn btn-primary">+ <?= e($createLabel ?? 'New') ?></a>
    <?php endif; ?>
</div>

<?php
/**
 * @var int    $page  current page (1-based)
 * @var int    $pages total pages
 * @var string $action base list URL
 * Preserves q and status query params.
 */
if (($pages ?? 1) <= 1) {
    return;
}
$query = array_filter([
    'q' => $q ?? '',
    'status' => $statusFilter ?? '',
], static fn($v) => $v !== '');
$pageUrl = static function (int $p) use ($action, $query): string {
    return $action . '?' . http_build_query($query + ['page' => $p]);
};
?>
<nav class="flex items-center justify-between mt-4 text-sm">
    <div>
        <?php if ($page > 1): ?>
        <a href="<?= e($pageUrl($page - 1)) ?>" class="btn btn-secondary">← Previous</a>
        <?php endif; ?>
    </div>
    <span style="color:var(--cui-muted)">Page <?= (int) $page ?> of <?= (int) $pages ?></span>
    <div>
        <?php if ($page < $pages): ?>
        <a href="<?= e($pageUrl($page + 1)) ?>" class="btn btn-secondary">Next →</a>
        <?php endif; ?>
    </div>
</nav>

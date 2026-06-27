<?php
$success = get_flash('success');
$error = get_flash('error');
?>
<?php if ($success): ?>
<div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
    <?= e($success) ?>
</div>
<?php endif; ?>
<?php if ($error): ?>
<div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
    <?= e($error) ?>
</div>
<?php endif; ?>

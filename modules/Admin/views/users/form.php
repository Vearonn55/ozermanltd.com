<?php $isEdit = $item !== null; ?>
<form method="POST" action="<?= $isEdit ? admin_url('users/' . $item['id']) : admin_url('users') ?>" class="space-y-6 max-w-xl">
    <?= csrf_field() ?>
    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" name="name" required value="<?= e($item['name'] ?? '') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" required value="<?= e($item['email'] ?? '') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Role</label>
            <select name="role" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <?php foreach ($roles as $role): ?>
                <option value="<?= e($role) ?>" <?= ($item['role'] ?? 'editor') === $role ? 'selected' : '' ?>><?= e($role) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <?php foreach (['active', 'inactive', 'suspended'] as $st): ?>
                <option value="<?= $st ?>" <?= ($item['status'] ?? 'active') === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                <?php endforeach; ?>
            </select>
            <p class="text-xs text-slate-500 mt-1">Suspended/inactive users lose all sessions immediately.</p>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1"><?= $isEdit ? 'New password (optional)' : 'Password' ?></label>
            <input type="password" name="password" <?= $isEdit ? '' : 'required' ?> class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
    </div>
    <div class="flex gap-3">
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg"><?= $isEdit ? 'Update' : 'Create' ?></button>
        <a href="<?= admin_url('users') ?>" class="text-sm text-slate-600 hover:underline self-center">Cancel</a>
    </div>
</form>

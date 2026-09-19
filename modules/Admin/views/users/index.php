<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-4 py-3 border-b flex items-center justify-between">
        <h2 class="font-semibold">Users</h2>
        <a href="<?= admin_url('users/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">New user</a>
    </div>
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-slate-500">
            <tr>
                <th class="text-left px-4 py-2">Name</th>
                <th class="text-left px-4 py-2">Email</th>
                <th class="text-left px-4 py-2">Role</th>
                <th class="text-left px-4 py-2">Status</th>
                <th class="text-left px-4 py-2">Last login</th>
                <th class="px-4 py-2"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr class="border-t">
                <td class="px-4 py-3"><?= e($item['name']) ?></td>
                <td class="px-4 py-3"><?= e($item['email']) ?></td>
                <td class="px-4 py-3"><?= e($item['role']) ?></td>
                <td class="px-4 py-3"><?= e($item['status']) ?></td>
                <td class="px-4 py-3 text-slate-500"><?= e($item['last_login_at'] ?? '—') ?></td>
                <td class="px-4 py-3 text-right space-x-3">
                    <a href="<?= admin_url('users/' . $item['id'] . '/edit') ?>" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="<?= admin_url('users/' . $item['id']) ?>" class="inline" onsubmit="return confirm('Delete this user?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (!empty($siteUsesDatabase)): ?>
<div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
    Public site is reading content from <strong>MySQL</strong>. Changes to News, Projects, and Sectors appear after save (hard refresh if needed).
</div>
<?php else: ?>
<div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
    Public site is using <strong>dummy data</strong> — CMS changes will not appear. Set <code>DB_USE_DUMMY_DATA=false</code> in .env and ensure MySQL is running.
</div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <?php
    $cards = [
        ['label' => 'Pages', 'value' => $counts['pages'], 'href' => 'pages', 'color' => 'blue'],
        ['label' => 'News Articles', 'value' => $counts['news'], 'sub' => $counts['news_published'] . ' published', 'href' => 'news', 'color' => 'emerald'],
        ['label' => 'Projects', 'value' => $counts['projects'], 'href' => 'projects', 'color' => 'purple'],
        ['label' => 'Sectors', 'value' => $counts['sectors'], 'href' => 'sectors', 'color' => 'amber'],
    ];
    foreach ($cards as $card):
    ?>
    <a href="<?= admin_url($card['href']) ?>" class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition-shadow">
        <p class="text-sm text-slate-500"><?= e($card['label']) ?></p>
        <p class="text-3xl font-bold text-slate-900 mt-1"><?= (int) $card['value'] ?></p>
        <?php if (!empty($card['sub'])): ?>
        <p class="text-xs text-slate-400 mt-1"><?= e($card['sub']) ?></p>
        <?php endif; ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Analytics Overview</h2>
            <a href="<?= admin_url('analytics') ?>" class="text-sm text-blue-600 hover:underline">View all</a>
        </div>
        <dl class="grid grid-cols-2 gap-4">
            <?php
            $metrics = [
                'Visitors' => $analytics['visitors'] ?? 0,
                'Events' => $analytics['events'] ?? 0,
                'Page Views' => $analytics['page_views'] ?? 0,
                'Consents' => $analytics['consents'] ?? 0,
            ];
            foreach ($metrics as $label => $value):
            ?>
            <div class="bg-slate-50 rounded-lg p-4">
                <dt class="text-xs text-slate-500 uppercase tracking-wide"><?= e($label) ?></dt>
                <dd class="text-2xl font-bold text-slate-900 mt-1"><?= number_format((int) $value) ?></dd>
            </div>
            <?php endforeach; ?>
        </dl>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Recent Contact Messages</h2>
            <a href="<?= admin_url('contacts') ?>" class="text-sm text-blue-600 hover:underline">
                <?= (int) $counts['contacts_new'] ?> new
            </a>
        </div>
        <?php if (empty($recentContacts)): ?>
        <p class="text-sm text-slate-500">No contact messages yet.</p>
        <?php else: ?>
        <ul class="divide-y divide-slate-100">
            <?php foreach ($recentContacts as $contact): ?>
            <li class="py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-900"><?= e($contact['name']) ?></p>
                    <p class="text-xs text-slate-500"><?= e($contact['email']) ?> · <?= e(date('M j, Y', strtotime($contact['created_at']))) ?></p>
                </div>
                <div class="flex items-center gap-2">
                    <?php $status = $contact['status']; require APP_PATH . '/Views/admin/partials/status-badge.php'; ?>
                    <a href="<?= admin_url('contacts/' . $contact['id']) ?>" class="text-blue-600 text-sm hover:underline">View</a>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</div>

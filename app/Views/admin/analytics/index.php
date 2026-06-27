<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <?php
    $cards = [
        ['label' => 'Visitors', 'value' => $summary['visitors'] ?? 0],
        ['label' => 'Total Events', 'value' => $summary['events'] ?? 0],
        ['label' => 'Page Views', 'value' => $summary['page_views'] ?? 0],
        ['label' => 'Consents', 'value' => $summary['consents'] ?? 0],
    ];
    foreach ($cards as $card):
    ?>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-xs text-slate-500 uppercase tracking-wide"><?= e($card['label']) ?></p>
        <p class="text-3xl font-bold text-slate-900 mt-1"><?= number_format((int) $card['value']) ?></p>
    </div>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Top Pages</h2>
        <?php if (empty($topPages)): ?>
        <p class="text-sm text-slate-500">No page view data yet.</p>
        <?php else: ?>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500">
                    <th class="pb-2 font-medium">Path</th>
                    <th class="pb-2 font-medium text-right">Views</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($topPages as $page): ?>
                <tr>
                    <td class="py-2 font-mono text-xs"><?= e($page['page_path']) ?></td>
                    <td class="py-2 text-right font-medium"><?= number_format((int) $page['views']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Consent Breakdown</h2>
        <?php if (empty($consent['total'])): ?>
        <p class="text-sm text-slate-500">No consent records yet.</p>
        <?php else: ?>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-slate-500">Total records</dt><dd class="font-medium"><?= number_format((int) $consent['total']) ?></dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Analytics accepted</dt><dd class="font-medium text-emerald-600"><?= number_format((int) ($consent['analytics_yes'] ?? 0)) ?></dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Analytics declined</dt><dd class="font-medium text-red-600"><?= number_format((int) ($consent['analytics_no'] ?? 0)) ?></dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Marketing accepted</dt><dd class="font-medium text-emerald-600"><?= number_format((int) ($consent['marketing_yes'] ?? 0)) ?></dd></div>
        </dl>
        <?php endif; ?>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 p-6">
    <h2 class="text-lg font-semibold mb-4">Recent Events</h2>
    <?php if (empty($recentEvents)): ?>
    <p class="text-sm text-slate-500">No events recorded yet.</p>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left px-3 py-2 font-medium text-slate-600">Event</th>
                    <th class="text-left px-3 py-2 font-medium text-slate-600">Page</th>
                    <th class="text-left px-3 py-2 font-medium text-slate-600">Locale</th>
                    <th class="text-left px-3 py-2 font-medium text-slate-600">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($recentEvents as $event): ?>
                <tr>
                    <td class="px-3 py-2"><span class="font-mono text-xs bg-slate-100 px-2 py-0.5 rounded"><?= e($event['event_name']) ?></span></td>
                    <td class="px-3 py-2 text-slate-500 font-mono text-xs"><?= e($event['page_path'] ?? '—') ?></td>
                    <td class="px-3 py-2"><?= e($event['locale'] ?? '—') ?></td>
                    <td class="px-3 py-2 text-slate-500"><?= date('M j, H:i', strtotime($event['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php if (!empty($eventsByDay)): ?>
<div class="bg-white rounded-xl border border-slate-200 p-6 mt-6">
    <h2 class="text-lg font-semibold mb-4">Events (Last 14 Days)</h2>
    <div class="space-y-2">
        <?php
        $maxEvents = max(array_column($eventsByDay, 'events')) ?: 1;
        foreach ($eventsByDay as $day):
            $pct = round(((int) $day['events'] / $maxEvents) * 100);
        ?>
        <div class="flex items-center gap-3 text-sm">
            <span class="w-24 text-slate-500 flex-shrink-0"><?= date('M j', strtotime($day['day'])) ?></span>
            <div class="flex-1 bg-slate-100 rounded-full h-4 overflow-hidden">
                <div class="bg-blue-500 h-full rounded-full" style="width: <?= $pct ?>%"></div>
            </div>
            <span class="w-12 text-right font-medium"><?= (int) $day['events'] ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

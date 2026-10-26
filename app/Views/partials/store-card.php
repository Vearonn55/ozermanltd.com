<?php
/** @var array $store */
$address = t_filled($store['address'] ?? []);
$hours = t_filled($store['hours'] ?? []);
$phone = trim((string) ($store['phone'] ?? ''));
$email = trim((string) ($store['email'] ?? ''));
?>
<article class="oz-card reveal">
    <div class="oz-card__media h-48">
        <img src="<?= e($store['image']) ?>" alt="<?= e(t($store['name'])) ?>" loading="lazy">
    </div>
    <div class="p-6">
        <p class="text-xs uppercase tracking-[0.16em] text-orange-600 mb-2"><?= e(t($store['city'])) ?></p>
        <h2 class="font-display text-xl font-bold text-brand-800 mb-3"><?= e(t($store['name'])) ?></h2>
        <ul class="space-y-2.5 text-sm text-brand-600">
            <?php if ($address !== ''): ?>
            <li class="flex gap-2">
                <svg class="w-4 h-4 mt-0.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span><?= e($address) ?></span>
            </li>
            <?php endif; ?>
            <?php if ($phone !== ''): ?>
            <li class="flex gap-2">
                <svg class="w-4 h-4 mt-0.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <a href="tel:<?= e($phone) ?>" class="hover:text-orange-600 transition-colors"><?= e($phone) ?></a>
            </li>
            <?php endif; ?>
            <?php if ($email !== ''): ?>
            <li class="flex gap-2">
                <svg class="w-4 h-4 mt-0.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <a href="mailto:<?= e($email) ?>" class="hover:text-orange-600 transition-colors"><?= e($email) ?></a>
            </li>
            <?php endif; ?>
            <?php if ($hours !== ''): ?>
            <li class="flex gap-2">
                <svg class="w-4 h-4 mt-0.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span><?= e($hours) ?></span>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</article>

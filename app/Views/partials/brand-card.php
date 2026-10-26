<?php
/** @var array $partner */
?>
<article class="oz-card reveal">
    <div class="oz-card__media">
        <?php if (trim((string) ($partner['image'] ?? '')) !== ''): ?>
        <img src="<?= e($partner['image']) ?>" alt="<?= e($partner['name']) ?>" loading="lazy">
        <?php endif; ?>
    </div>
    <div class="oz-card__body p-6">
        <?php if (t($partner['tagline'] ?? []) !== ''): ?>
        <p class="text-xs uppercase tracking-[0.16em] text-orange-600 mb-2"><?= e(t($partner['tagline'])) ?></p>
        <?php endif; ?>
        <h2 class="font-display text-xl font-bold text-brand-800 mb-3 break-words"><?= e($partner['name']) ?></h2>
        <p class="text-sm text-brand-600 leading-relaxed break-words"><?= e(t($partner['description'] ?? [])) ?></p>
        <?php if (!empty($partner['url'])): ?>
        <a href="<?= e($partner['url']) ?>" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 mt-4 text-brand-800 font-semibold hover:text-orange-600 transition-colors duration-200">
            <?= e(t(['en' => 'Visit brand website', 'tr' => 'Marka sitesini ziyaret et', 'ar' => 'زيارة موقع العلامة'])) ?>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
        <?php endif; ?>
    </div>
</article>

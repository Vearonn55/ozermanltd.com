<?php
$footerMenus = content()->footerMenus(app_locale());
$footerHref = static function (array $link): string {
    $url = (string) ($link['url'] ?? '');
    return preg_match('#^https?://#', $url) === 1 ? $url : url($url);
};
?>
<footer class="site-footer bg-brand-900 text-brand-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <div class="lg:col-span-1">
                <a href="<?= url('') ?>" class="inline-block mb-6">
                    <img src="<?= asset('images/brand/logo-stacked-dark.png') ?>"
                         alt="Özerman Ticaret"
                         width="160"
                         height="80"
                         decoding="async"
                         fetchpriority="low"
                         class="h-20 w-auto object-contain">
                </a>
                <p class="text-sm text-brand-400 leading-relaxed mb-6">
                    <?= e(t([
                        'en' => 'An importer limited company connecting trusted brands with retail markets through disciplined trade and local presence.',
                        'tr' => 'Güvenilir markaları disiplinli ticaret ve yerel varlıkla perakende pazarlara bağlayan ithalatçı limited şirket.',
                        'ar' => 'شركة استيراد محدودة تربط العلامات الموثوقة بأسواق التجزئة عبر تجارة منضبطة وحضور محلي.',
                    ])) ?>
                </p>
                <div class="flex gap-3">
                    <?php foreach (config('social', []) as $platform => $link): ?>
                    <a href="<?= e($link) ?>" target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-sm bg-brand-800 flex items-center justify-center text-brand-400 hover:bg-orange-500 hover:text-white transition-colors duration-200">
                        <span class="text-xs font-bold uppercase"><?= e(substr($platform, 0, 2)) ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php foreach (['footer_col1', 'footer_col2'] as $location):
                $column = $footerMenus[$location] ?? null;
                if ($column === null) {
                    continue;
                }
            ?>
            <div>
                <h4 class="text-brand-100 font-semibold mb-4 text-sm uppercase tracking-wider">
                    <?= e(t($column['title'])) ?>
                </h4>
                <ul class="space-y-2.5">
                    <?php foreach ($column['links'] as $link): ?>
                    <li>
                        <a href="<?= e($footerHref($link)) ?>"<?= ($link['target'] ?? '_self') === '_blank' ? ' target="_blank" rel="noopener"' : '' ?>
                           class="text-sm text-brand-400 hover:text-orange-400 transition-colors duration-200">
                            <?= e(t($link['label'])) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>

            <div>
                <h4 class="text-brand-100 font-semibold mb-4 text-sm uppercase tracking-wider">
                    <?= e(t(['en' => 'Contact', 'tr' => 'İletişim', 'ar' => 'اتصل بنا'])) ?>
                </h4>
                <ul class="space-y-3 text-sm text-brand-400">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span><?= e(config('contact.address')) ?></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:<?= e(config('contact.phone')) ?>" class="hover:text-orange-400 transition-colors"><?= e(config('contact.phone')) ?></a>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:<?= e(config('contact.email')) ?>" class="hover:text-orange-400 transition-colors"><?= e(config('contact.email')) ?></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-brand-800 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm text-brand-500">
                &copy; <?= date('Y') ?> Özerman Ticaret.
                <?= e(t(['en' => 'All rights reserved.', 'tr' => 'Tüm hakları saklıdır.', 'ar' => 'جميع الحقوق محفوظة.'])) ?>
            </p>
            <div class="flex flex-wrap gap-6 text-sm text-brand-500">
                <?php foreach (($footerMenus['footer_col3']['links'] ?? []) as $link): ?>
                <a href="<?= e($footerHref($link)) ?>"<?= ($link['target'] ?? '_self') === '_blank' ? ' target="_blank" rel="noopener"' : '' ?>
                   class="hover:text-orange-400 transition-colors">
                    <?= e(t($link['label'])) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</footer>

<footer class="bg-brand-950 text-brand-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <!-- Brand -->
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-brand-800 rounded-sm flex items-center justify-center">
                        <span class="text-gold-500 font-display font-bold text-lg">O</span>
                    </div>
                    <div>
                        <span class="font-display font-bold text-xl text-white">Ozerman</span>
                        <span class="block text-xs text-brand-400 tracking-widest uppercase -mt-0.5">Ltd</span>
                    </div>
                </div>
                <p class="text-sm text-brand-400 leading-relaxed mb-6">
                    <?= e(t([
                        'en' => 'A diversified international business group building excellence across trading, construction, real estate, and energy.',
                        'tr' => 'Ticaret, inşaat, gayrimenkul ve enerji alanlarında mükemmellik inşa eden çeşitlendirilmiş uluslararası iş grubu.',
                        'ar' => 'مجموعة أعمال دولية متنوعة تبني التميز في التجارة والبناء والعقارات والطاقة.',
                    ])) ?>
                </p>
                <div class="flex gap-3">
                    <?php foreach (config('social', []) as $platform => $link): ?>
                    <a href="<?= e($link) ?>" target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-full bg-brand-800 flex items-center justify-center text-brand-400 hover:bg-gold-500 hover:text-brand-950 transition-colors">
                        <span class="text-xs font-bold uppercase"><?= e(substr($platform, 0, 2)) ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">
                    <?= e(t(['en' => 'Quick Links', 'tr' => 'Hızlı Bağlantılar', 'ar' => 'روابط سريعة'])) ?>
                </h4>
                <ul class="space-y-2.5">
                    <?php
                    $footerLinks = [
                        ['label' => ['en' => 'About Us', 'tr' => 'Hakkımızda', 'ar' => 'من نحن'], 'url' => 'about-us'],
                        ['label' => ['en' => 'Sectors', 'tr' => 'Sektörler', 'ar' => 'القطاعات'], 'url' => 'sectors'],
                        ['label' => ['en' => 'Projects', 'tr' => 'Projeler', 'ar' => 'المشاريع'], 'url' => 'projects'],
                        ['label' => ['en' => 'News', 'tr' => 'Haberler', 'ar' => 'الأخبار'], 'url' => 'news'],
                    ];
                    foreach ($footerLinks as $link): ?>
                    <li>
                        <a href="<?= url($link['url']) ?>" class="text-sm text-brand-400 hover:text-gold-400 transition-colors">
                            <?= e(t($link['label'])) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Sectors -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">
                    <?= e(t(['en' => 'Our Sectors', 'tr' => 'Sektörlerimiz', 'ar' => 'قطاعاتنا'])) ?>
                </h4>
                <ul class="space-y-2.5">
                    <?php
                    $sectorLinks = [
                        ['en' => 'Trading', 'tr' => 'Ticaret', 'ar' => 'التجارة'],
                        ['en' => 'Construction', 'tr' => 'İnşaat', 'ar' => 'البناء'],
                        ['en' => 'Real Estate', 'tr' => 'Gayrimenkul', 'ar' => 'العقارات'],
                        ['en' => 'Energy', 'tr' => 'Enerji', 'ar' => 'الطاقة'],
                    ];
                    $sectorSlugs = ['trading', 'construction', 'real-estate', 'energy'];
                    foreach ($sectorLinks as $i => $label): ?>
                    <li>
                        <a href="<?= url('sectors/' . $sectorSlugs[$i]) ?>" class="text-sm text-brand-400 hover:text-gold-400 transition-colors">
                            <?= e(t($label)) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">
                    <?= e(t(['en' => 'Contact', 'tr' => 'İletişim', 'ar' => 'اتصل بنا'])) ?>
                </h4>
                <ul class="space-y-3 text-sm text-brand-400">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 text-gold-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span><?= e(config('contact.address')) ?></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gold-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:<?= e(config('contact.phone')) ?>" class="hover:text-gold-400 transition-colors"><?= e(config('contact.phone')) ?></a>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gold-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:<?= e(config('contact.email')) ?>" class="hover:text-gold-400 transition-colors"><?= e(config('contact.email')) ?></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-brand-800 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm text-brand-500">
                &copy; <?= date('Y') ?> Ozerman Ltd.
                <?= e(t(['en' => 'All rights reserved.', 'tr' => 'Tüm hakları saklıdır.', 'ar' => 'جميع الحقوق محفوظة.'])) ?>
            </p>
            <div class="flex gap-6 text-sm text-brand-500">
                <a href="#" class="hover:text-gold-400 transition-colors">
                    <?= e(t(['en' => 'Privacy Policy', 'tr' => 'Gizlilik Politikası', 'ar' => 'سياسة الخصوصية'])) ?>
                </a>
                <a href="#" class="hover:text-gold-400 transition-colors">
                    <?= e(t(['en' => 'Terms of Use', 'tr' => 'Kullanım Koşulları', 'ar' => 'شروط الاستخدام'])) ?>
                </a>
            </div>
        </div>
    </div>
</footer>

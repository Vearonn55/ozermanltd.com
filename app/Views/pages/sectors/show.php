<section class="relative h-[50vh] min-h-[400px] overflow-hidden">
    <img src="<?= e($sector['image']) ?>" alt="<?= e(t($sector['name'])) ?>" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-brand-950/90 via-brand-900/60 to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <nav class="text-sm text-brand-300 mb-4">
                <a href="<?= url('') ?>" class="hover:text-white"><?= e(t(['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'])) ?></a>
                <span class="mx-2">/</span>
                <a href="<?= url('sectors') ?>" class="hover:text-white"><?= e(t(['en' => 'Sectors', 'tr' => 'Sektörler', 'ar' => 'القطاعات'])) ?></a>
                <span class="mx-2">/</span>
                <span class="text-white"><?= e(t($sector['name'])) ?></span>
            </nav>
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white"><?= e(t($sector['name'])) ?></h1>
        </div>
    </div>
</section>

<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-16">
            <div class="lg:col-span-2">
                <h2 class="font-display text-2xl font-bold text-brand-900 mb-6 accent-line">
                    <?= e(t(['en' => 'Overview', 'tr' => 'Genel Bakış', 'ar' => 'نظرة عامة'])) ?>
                </h2>
                <p class="text-brand-600 leading-relaxed text-lg mb-10"><?= e(t($sector['overview'])) ?></p>

                <h2 class="font-display text-2xl font-bold text-brand-900 mb-6 accent-line">
                    <?= e(t(['en' => 'Services', 'tr' => 'Hizmetler', 'ar' => 'الخدمات'])) ?>
                </h2>
                <div class="text-brand-600 leading-relaxed whitespace-pre-line"><?= e(t($sector['services'])) ?></div>
            </div>
            <div>
                <div class="bg-brand-50 p-8 rounded-sm border border-brand-100 sticky top-28">
                    <h3 class="font-display text-lg font-semibold text-brand-900 mb-6">
                        <?= e(t(['en' => 'Interested in this sector?', 'tr' => 'Bu sektörle ilgileniyor musunuz?', 'ar' => 'مهتم بهذا القطاع؟'])) ?>
                    </h3>
                    <p class="text-sm text-brand-500 mb-6">
                        <?= e(t([
                            'en' => 'Lorem ipsum dolor sit amet. Contact our team to discuss partnership opportunities.',
                            'tr' => 'Lorem ipsum dolor sit amet. Ortaklık fırsatlarını görüşmek için ekibimizle iletişime geçin.',
                            'ar' => 'تواصل مع فريقنا لمناقشة فرص الشراكة.',
                        ])) ?>
                    </p>
                    <a href="<?= url('contact') ?>"
                       class="block w-full text-center bg-brand-900 hover:bg-brand-800 text-white font-semibold px-6 py-3 rounded-sm transition-colors">
                        <?= e(t(['en' => 'Contact Us', 'tr' => 'İletişim', 'ar' => 'اتصل بنا'])) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($projects)): ?>
<section class="py-20 bg-brand-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-2xl font-bold text-brand-900 mb-10 accent-line">
            <?= e(t(['en' => 'Related Projects', 'tr' => 'İlgili Projeler', 'ar' => 'مشاريع ذات صلة'])) ?>
        </h2>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach (array_slice(array_values($projects), 0, 3) as $project): ?>
            <a href="<?= url('projects/' . $project['slug']) ?>" class="group card-hover">
                <img src="<?= e($project['image']) ?>" alt="<?= e(t($project['title'])) ?>"
                     class="w-full h-48 object-cover rounded-sm mb-4 group-hover:scale-105 transition-transform duration-500" loading="lazy">
                <h3 class="font-display text-lg font-semibold text-brand-900 group-hover:text-gold-600 transition-colors">
                    <?= e(t($project['title'])) ?>
                </h3>
                <p class="text-sm text-brand-500 mt-1"><?= e($project['location']) ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
